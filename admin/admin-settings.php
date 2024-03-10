<?php

/** @var \Ajaxy\LiveSearch\SF $AjaxyLiveSearch */
global $AjaxyLiveSearch;
$message = false;

$values = (array)$AjaxyLiveSearch->get_styles();

if (isset($_POST['sf_rsubmit']) && wp_verify_nonce($_POST['_wpnonce'])) {
    $AjaxyLiveSearch->clear_styles();
    $AjaxyLiveSearch->remove_template('more');
} elseif (isset($_POST['sf_submit']) && wp_verify_nonce($_POST['_wpnonce'])) {
    $styles = $_POST['sf']['style'];
    $templates = $_POST['sf']['template'];

    $values = array_replace($values, [
        'search_label' => $styles['label'] ?? __('Search'),
        'input_id' => $styles['input_id'],
        'width' => (int)$styles['width'],
        'credits' => (int)$styles['credits'] ?? 0,
        'aspect_ratio' => (int)$styles['aspect_ratio'] ?? 0,
        'hook_search_form' => (int)$styles['hook_form'] ?? 0,
        'rtl_theme' => (int)$styles['rtl'] ?? 0,
        'delay' => (int)$styles['delay'],
        'border-width' => (int)$styles['b_width'] ?? 0,
        'border-type' => $styles['b_type'] ?? 'none',
        'border-color' => $styles['b_color'] ?? '000000',
        'search_url' => $styles['url'] ?? '',
        'results_width' => (int)$styles['results_width'] ?? 0,
        'results_width_unit' => $styles['results_width_unit'] ?? 'px',
        'excerpt' => (int)$styles['excerpt'] ?? 10,
        'css' => $styles['css'] ?? '',
        'thumb_width' => (int)$styles['thumb_width'] ?? 50,
        'thumb_height' => (int)$styles['thumb_height'] ?? 50,
    ]);


    $AjaxyLiveSearch->set_styles($values);
    $AjaxyLiveSearch->set_templates('more', $templates['more_results']);
    $message = "Settings saved";
}
?>
<?php if ($message) : ?>
    <div id="message" class="updated">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>
<div class="ajaxy-wrap">
    <h3><?php _e('Search Form Box'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Allow Ajaxy to hook search form'); ?></label></th>
                    <td>
                        <input type="checkbox" name="sf[style][hook_form]" <?php echo  $values['hook_search_form'] > 0 ? 'checked="checked"' : ''; ?> />
                        <span class="description"><?php _e('unCheck this in case you want to use your theme search form and use the ID box.'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Use Right to Left styles when needed'); ?></label></th>
                    <td>
                        <input type="checkbox" name="sf[style][rtl]" <?php echo $values['rtl_theme'] > 0 ? 'checked="checked"' : ''; ?> />
                        <span class="description"><?php _e('Check this in case you want to use rtl themes to support right to left languages like arabic.'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Search label'); ?></label></th>
                    <td>
                        <input type="text" value="<?php echo $values['search_label']; ?>" name="sf[style][label]" class="regular-text">
                        <p class="description"><?php _e('This label appears inside the search form and will be hidden when the user clicks inside.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Input ID or class name'); ?></label></th>
                    <td>
                        <input type="text" value="<?php echo $values['input_id']; ?>" name="sf[style][input_id]" class="regular-text">
                        <p class="description"><?php _e('keep this blank to use ajaxy search form, or else put the id of the search or the class name in the form (#ID for id (# before the id) or else (.className) ( "." before the className).'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Width'); ?></label></th>
                    <td>
                        <input style="width:90px" type="text" value="<?php echo  $values['width']; ?>" name="sf[style][width]" class="regular-text">
                        <p class="description"><?php _e('The width of the search form (width is per pixel) - the value should be integer.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Delay time'); ?></label></th>
                    <td>
                        <input style="width:90px" type="text" value="<?php echo  $values['delay']; ?>" name="sf[style][delay]" class="regular-text">
                        <p class="description"><?php _e('The delay time before showing the results (this will allow the user to input more text before searching) -  <b>(in millisecond, i.e 5000 = 5sec)</b>.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Border width'); ?></label></th>
                    <td>
                        <input style="width:90px" type="text" value="<?php echo  $values['border-width']; ?>" name="sf[style][b_width]" class="regular-text">
                        <p class="description"><?php _e('The width of the search form border.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Border type'); ?></label></th>
                    <td>
                        <select name="sf[style][b_type]">
                            <option value="solid" <?php echo ($values['border-type'] == 'solid' ? 'selected="selected"' : ""); ?>><?php _e('solid'); ?></option>
                            <option value="dotted" <?php echo ($values['border-type'] == 'dotted' ? 'selected="selected"' : ""); ?>><?php _e('dotted'); ?></option>
                            <option value="dashed" <?php echo ($values['border-type'] == 'dashed' ? 'selected="selected"' : ""); ?>><?php _e('dashed'); ?></option>
                        </select>
                        <p class="description"><?php _e('The type of the search form border.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Border color'); ?></label></th>
                    <td>
                        <input style="width:52px" type="text" value="<?php echo $values['border-color']; ?>" name="sf[style][b_color]" class="regular-text">
                        <p class="description"><?php _e('The color of the search form border (color value is hexa-decimal).'); ?></p>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <h3><?php _e('Search Results box'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Width'); ?></label></th>
                    <td>
                        <input style="width:90px" type="number" value="<?php echo  $values['results_width']; ?>" name="sf[style][results_width]" class="regular-text">
                        <select name="sf[style][results_width_unit]">
                            <option value="px" <?php echo ($values['results_width_unit'] == 'px' ? 'selected="selected"' : ""); ?>><?php _e('Pixels'); ?></option>
                            <option value="%" <?php echo ($values['results_width_unit'] == '%' ? 'selected="selected"' : ""); ?>><?php _e('Percent'); ?></option>
                        </select>
                        <p class="description"><?php _e('The width of the results box (width is per pixel/percent) - the value should be integer.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Total words'); ?></label></th>
                    <td>
                        <input style="width:90px" type="text" value="<?php echo  $values['excerpt']; ?>" name="sf[style][excerpt]" class="regular-text">
                        <p class="description"><?php _e('The post content total number of words to be shown under each result.'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Thumb size'); ?></label></th>
                    <td>
                        <label><?php _e('height'); ?></label>
                        <input style="width:90px" type="text" value="<?php echo  $values['thumb_height']; ?>" name="sf[style][thumb_height]" class="regular-text">
                        <label><?php _e('X width'); ?></label>
                        <input style="width:90px" type="text" value="<?php echo  $values['thumb_width']; ?>" name="sf[style][thumb_width]" class="regular-text">
                        <input type="checkbox" name="sf[style][aspect_ratio]" <?php echo  $values['aspect_ratio'] > 0 ? 'checked="checked"' : ''; ?> /><label><?php _e('Maintain aspect ratio'); ?></label>
                        <br class="clear" />
                        <p class="description"><?php _e('The thumbnail size used in the post template it will modify {post_image_html} only, Maintaining aspect ratio is relatively slow so be aware, modifing the thumb size will need some css changes.'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3><?php _e('More results box'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Search Url'); ?></label></th>
                    <td>
                        <input type="text" value="<?php echo $values['search_url']; ?>" name="sf[style][url]" class="regular-text">
                        <p class="description"><?php _e('This search URL for the "See more results"'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2">
                        <textarea style="width:99%; height:150px" name="sf[template][more_results]" class="regular-text"><?php echo $AjaxyLiveSearch->get_templates('more', 'more'); ?></textarea>
                        <br class="clear" />
                        <p class="description"><?php _e('More results text (allowed parameters ( <b>{search_value}</b> <b>{search_value_escaped}</b> <b>{search_url_escaped}</b>).'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3><?php printf(_('Custom styles (%s)'), '<a href="http://www.w3schools.com/css/css_syntax.asp" target="_blank" rel="nofollow">CSS</a>'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td colspan="2">
                        <textarea style="width:99%; height:150px" name="sf[style][css]" class="regular-text"><?php echo $values['css']; ?></textarea>
                        <br class="clear" />
                        <p class="description"><?php _e('Custom styles to be added in the plugin css. add ( .screen-reader-text { display:none; } ) if you want to hide the search form title.'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3><?php _e('Credits'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td colspan="2">
                        <input type="checkbox" name="sf[style][credits]" <?php echo  $values['credits'] == 1 ? 'checked="checked"' : ''; ?> />
                        <span class="description"><?php _e('Author "Powered by" link and credits.'); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br class="clear" />
    <input class="button-primary" name="sf_submit" type="submit" value="Save Changes" />
    <input class="button-primary" onclick="return confirm('<?php _e('Are you sure you want to reset all your settings?'); ?>');" name="sf_rsubmit" type="submit" value="<?php _e('Reset Setting to defaults'); ?>" />
    <br class="clear" />
</div>