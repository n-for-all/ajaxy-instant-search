/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { mediaAndText as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import metadata from "./block.json";
import save from "./save";

const { name, title, category } = metadata;

export { metadata, name };

export const settings = {
	icon,
	title,
	category,
	example: {
		attributes: {
			mediaType: "image",
			mediaUrl: "https://s.w.org/images/core/5.3/Biologia_Centrali-Americana_-_Cantorchilus_semibadius_1902.jpg",
		}
	},
	edit,
	save,
};
