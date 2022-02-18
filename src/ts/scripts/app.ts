import "whatwg-fetch";
import offset from "dom-helpers/offset";
import { animate } from "animate.js";

declare let AjaxyLiveSearchSettings: any;
class SFResults {
	element: HTMLUListElement;
	items = [];
	parent: any;
	constructor(data, parent) {
		this.items = []; 
		this.parent = parent;
		this.element = document.createElement("ul");

		const { options, input } = this.parent;
		if (this.isString(data)) {
			let results = [];
			data.split(",").map((val) => {
				var dm = val.split(":");
				if (dm.length == 2) {
					if (dm[1].indexOf(input.value) == 0) {
						results.push({ value: dm[0], label: dm[1] });
					}
				} else if (dm.length == 1) {
					if (val.indexOf(input.value) == 0) {
						results.push({ value: val, label: val });
					}
				}
			});

			results.map(({ label, value }) => {
				let child = document.createElement("li");
				child.classList.add("sf-lnk");
				child.innerHTML = label;
				child.setAttribute("data-value", value);
				this.element.appendChild(child);
				this.items.push(child);
			});
		} else {
			Object.keys(data).map((key) => {
				let results = data[key];
				results.map(({ all, class_name, template, title }) => {
					if (all && all.length) {
						if (title) {
							let li = document.createElement("li");
							li.classList.add("sf-header");
							li.innerText = title;
							this.element.appendChild(li);
						}

						let li = document.createElement("li");
						let div = document.createElement("div");
						div.classList.add("sf-result-container");

						let ul = document.createElement("ul");
						all.map((result) => {
							let child = document.createElement("li");
							child.classList.add("sf-lnk", class_name);
							child.innerHTML = this.replaceResults(result, template);
							ul.appendChild(child);
							this.items.push(child);
						});

						div.appendChild(ul);
						li.appendChild(div);

						this.element.appendChild(li);
					}
				});
			});
		}

		if (!options.callback) {
			var nTemplate = AjaxyLiveSearchSettings.more;

			nTemplate = nTemplate.replace(/{search_value_escaped}/g, `${input.value}`);
			nTemplate = nTemplate.replace(/{search_url_escaped}/g, options.searchUrl.replace("%s", encodeURI(input.value)));
			nTemplate = nTemplate.replace(/{search_value}/g, `${input.value}`);
			nTemplate = nTemplate.replace(/{total}/g, this.items.length);

			let child = document.createElement("li");
			child.classList.add("sf-lnk", "sf-more");
			if (this.items.length == 0) {
				child.classList.add("sf-selected");
			}
			child.innerHTML = nTemplate;
			this.element.appendChild(child);

			this.items.push(child);
		}

		this.loadLiveEvents(options.callback);
	}

	loadLiveEvents(callback) {
		this.items.map((item) => {
			item.addEventListener("mouseover", (e) => {
				this.items.map((xitem) => {
					xitem != item && xitem.classList.remove("sf-selected");
				});
				item.classList.add("sf-selected");
			});
		});

		if (callback) {
			this.items.map((item) => {
				item.addEventListener("click", (e) => {
					try {
						(window[callback] as any)(item, this);
					} catch (e) {
						alert(e);
					}
				});
			});
		}

		window.addEventListener("keydown", (event) => {
			if (this.parent.mainElem.style.display != "none") {
				if (event.key == "38" || event.key == "40") {
					var sindex = -1;
					event.stopPropagation();
					event.preventDefault();

					this.items.some((item, index) => {
						if (item.classList.contains("sf-selected")) {
							sindex = index;
							return true;
						}
						return false;
					});

					if (event.key == "40") {
						if (sindex + 1 < this.items.length && this.items[sindex + 1]) {
							this.items.map((item) => item.classList.remove("sf-selected"));
							this.items[sindex + 1].classList.add("sf-selected");
						}
					} else if (event.key == "38") {
						if (sindex - 1 > 0 && this.items[sindex - 1]) {
							this.items.map((item) => item.classList.remove("sf-selected"));
							this.items[sindex - 1].classList.add("sf-selected");
						} else {
							this.items.map((item) => item.classList.remove("sf-selected"));
							this.items[0].classList.add("sf-selected");
						}
					}
				} else if (event.key == "27") {
					this.parent.mainElem.style.display = "none";
				} else if (event.key == "13") {
					let item = this.parent.mainElem.querySelector("li.sf-loadingselected a");
					var b = item ? item.href : null;
					if (b && b != "") {
						if (callback) {
							(window[callback] as any)(item, this);
						} else {
							window.location.href = b;
						}
						return false;
					}
				}
			}
		});
	}

	replaceResults(results, template) {
		for (var s in results) {
			template = template.replace(new RegExp("{" + s + "}", "g"), results[s]);
		}
		return template;
	}

	isString = (str) => Object.prototype.toString.call(str) === "[object String]";
}
class SF {
	input = null;
	timeout = null;
	options = null;
	mainElem: HTMLDivElement;
	resultsElem: HTMLDivElement;
	valElem: HTMLDivElement;
	moreElem: HTMLDivElement;
	results: any[];

	defaults = {
		delay: 500,
		leftOffset: 0,
		topOffset: 5,
		text: "Search For",
		iwidth: 180,
		width: 315,
		ajaxUrl: "",
		ajaxData: false, //function to extend data sent to server
		searchUrl: "",
		expand: false,
		callback: false,
		rtl: false,
		search: false,
	};

	constructor(selector: string, options) {
		this.input = document.querySelector(selector);
		if (!this.input) {
			console.warn(`Ajaxy Live Search can't find input ${selector}`);
			return;
		}
		this.timeout = null;
		this.options = Object.assign(this.defaults, options);

		this.attr({ placeholder: options.text, autocomplete: "off" }, this.input);

		this.mainElem = document.createElement("div");
		this.mainElem.classList.add("sf-container");

		if (this.options.rtl) {
			this.mainElem.classList.add("sf-rtl");
		}

		this.mainElem.style.position = "absolute";
		this.mainElem.style.display = "none";
		this.mainElem.style.width = `${options.width}`;
		this.mainElem.style.zIndex = "9999";

		let mainCont = document.createElement("div");
		mainCont.classList.add("sf-sb-cont");

		let topCont = document.createElement("div");
		topCont.classList.add("sf-sb-top");

		let bottomCont = document.createElement("div");
		bottomCont.classList.add("sf-sb-bottom");

		mainCont.appendChild(topCont);

		this.resultsElem = document.createElement("div");
		this.resultsElem.classList.add("sf-results");

		this.valElem = document.createElement("div");
		this.valElem.classList.add("sf-val");

		this.resultsElem.appendChild(this.valElem);

		mainCont.appendChild(this.resultsElem);
		mainCont.appendChild(bottomCont);

		this.mainElem.appendChild(mainCont);

		document.body.appendChild(this.mainElem);

		this.loadEvents();
	}

	attr(attrs, elm) {
		Object.keys(attrs).map((attr) => {
			elm.setAttribute(attr, attrs[attr]);
		});
	}

	loader(value) {
		let ul = document.createElement("ul");
		let li = document.createElement("li");
		li.classList.add("sf-lnk", "sf-more", "sf-selected");

		let link = document.createElement("a");
		link.classList.add("sf-loading");
		link.href = this.options.searchUrl.replace("%s", encodeURI(value));
		li.appendChild(link);
		ul.appendChild(li);

		this.valElem.innerHTML = "";
		this.valElem.appendChild(ul);
	}

	loadResults() {
		if (this.input.value != "") {
			this.loader(this.input.value);

			this.mainElem.style.display = "block";
			this.adjustPosition();

			let data = new FormData();
			data.append("action", "ajaxy_sf");
			data.append("value", this.input.value);

			if (this.options.ajaxData && window[this.options.ajaxData]) {
				data = (window[this.options.ajaxData] as any)(data);
			}
			if (this.options.search) {
				let nResults = new SFResults(this.options.search, this);

				this.valElem.innerHTML = "";
				this.valElem.appendChild(nResults.element);

				this.mainElem.style.display = "block";
			} else {
				fetch(this.options.ajaxUrl, {
					body: data,
					method: "post",
				})
					.then((response) => {
						response
							.json()
							.then((results) => {
								this.parseResults(results);
							})
							.catch((e) => {
								this.parseResults([]);
							});
					})
					.catch((e) => {
						this.parseResults([]);
					});
			}
		} else {
			this.mainElem.style.display = "none";
		}
	}

	adjustPosition() {
		var pos = this.bounds(this.input, this.options);
		if (!pos || this.mainElem.style.display == "none") {
			this.mainElem.style.display = "none";
			return false;
		}
		if (Math.ceil(pos.left) + parseInt(this.options.width, 10) > window.innerWidth) {
			this.mainElem.style.width = `${window.innerWidth - pos.left - 20}px`;
		}

		if (this.options.rtl) {
			this.mainElem.style.top = `${pos.bottom}px`;
			this.mainElem.style.left = `${pos.right}px`;
			this.mainElem.style.right = `auto`;
		} else {
			this.mainElem.style.top = `${pos.bottom}px`;
			this.mainElem.style.left = `${pos.left}px`;
			this.mainElem.style.right = `auto`;
		}
	}

	parseResults(results) {
		let nResults = new SFResults(results, this);

		this.valElem.innerHTML = "";
		this.valElem.appendChild(nResults.element);

		this.mainElem.style.display = "block";
	}
	bounds(elem, options) {
		var elmOffset = offset(elem);
		if (elmOffset) {
			return {
				top: elmOffset.top,
				left: elmOffset.left + options.leftOffset,
				bottom: elmOffset.top + elem.clientHeight + options.topOffset,
				right: elmOffset.left - this.mainElem.clientWidth + elem.clientWidth,
			};
		}
	}

	loadEvents() {
		document.addEventListener("click", () => {
			this.mainElem.style.display = "none";
		});

		window.addEventListener("resize", () => {
			if (this.mainElem.style.display == "none") {
				return;
			}
			this.adjustPosition();
		});		
        
        
        window.addEventListener("scroll", () => {
			if (this.mainElem.style.display == "none") {
				return;
			}
			this.adjustPosition();
		});

		this.input.addEventListener("keyup", (event) => {
			if (event.key != "38" && event.key != "40" && event.key != "13" && event.key != "27" && event.key != "39" && event.key != "37") {
				if (this.timeout != null) {
					clearTimeout(this.timeout);
				}

				this.mainElem.classList.add("sf-focused");

				this.timeout = setTimeout(() => {
					this.loadResults();
				}, this.options.delay);
			}
		});

		this.input.addEventListener("focus", () => {
			this.input.classList.add("sf-focused");
			if (this.options.expand > 0) {
				animate(
					this.input,
					{
						width: this.options.iwidth,
					},
					500
				);
			}
		});
		this.input.addEventListener("blur", () => {
			this.input.classList.remove("sf-focused");
			if (this.options.expand > 0) {
				animate(
					this.input,
					{
						width: this.options.expand,
					},
					500
				);
			}
		});
	}
}

let ready = (fn) => {
	if (document.readyState != "loading") {
		fn();
	} else {
		document.addEventListener("DOMContentLoaded", fn);
	}
};

ready(() => {
	AjaxyLiveSearchSettings.boxes.map((box) => {
		new SF(box.selector, box.options);
	});
});
