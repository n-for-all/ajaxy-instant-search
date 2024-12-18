<?php

if (!defined('ABSPATH')) exit;

/** @var \Ajaxy\LiveSearch\SF $AjaxyLiveSearch */
global $AjaxyLiveSearch;

$styles = $AjaxyLiveSearch->get_styles(
    [
        'search_label' => 'Search',
        'width' => 300,
        'delay' => 500,
        'border-width' => 1,
        'border-type' => 'solid',
        'border-color' => '#000',
        'results_width' => 300,
        'search_url' => '',
        'credits' => 1,
        'show_category' => 1,
        'show_post_category' => 1,
        'post_types' => []
    ]
);
?>
<div class="ajaxy-wrap">
    <h2><?php esc_html_e('Select the search settings below and click generate shortcode.', "ajaxy-instant-search"); ?></h2>
    <div class="ajaxy-form-left">
        <h3><?php esc_html_e('Search Settings', "ajaxy-instant-search"); ?></h3>
        <div>
            <input type="hidden" name="action" value="ajaxy_sf_shortcode" />
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Show Categories', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input type="checkbox" name="sf[style][show_category]" checked="checked" value="1" />
                            <span class="description"><?php esc_html_e('Show the categories in the search results.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Show Post Categories', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input type="checkbox" name="sf[style][show_post_category]" checked="checked" value="1" />
                            <span class="description"><?php esc_html_e('Show post of found categories in the search results.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Post types', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <ul class="ajaxy-sf-select">
                                <?php
                                $post_types = get_post_types('', 'objects');
                                foreach ($post_types as $post_type) {
                                ?>
                                    <li><input type="checkbox" name="sf[style][post_types][]" value="<?php echo esc_attr($post_type->name); ?>" /><?php echo esc_html($post_type->label); ?></li>
                                <?php
                                }
                                ?>
                            </ul>
                            <span class="description"><?php esc_html_e('Select which post types to search, don\'t select any if you want to search all.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3><?php esc_html_e('Search Form Box', "ajaxy-instant-search"); ?></h3>
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Use Right to Left styles', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input type="checkbox" name="sf[style][rtl]" <?php checked($styles['rtl_theme'] > 0, true); ?> value="1" />
                            <span class="description"><?php esc_html_e('Check this in case you want to use rtl themes to support right to left languages like arabic.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Search label', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input type="text" value="<?php echo esc_attr($styles['search_label']); ?>" name="sf[style][label]" class="regular-text">
                            <p class="description"><?php esc_html_e('This label appears inside the search form and will be hidden when the user clicks inside.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Width', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input style="width:40px" type="text" value="<?php echo esc_attr($styles['width']); ?>" name="sf[style][width]" class="regular-text">
                            <p class="description"><?php esc_html_e('The width of the search form (width is per pixel) - the value should be integer.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Delay time', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input style="width:40px" type="text" value="<?php echo esc_attr($styles['delay']); ?>" name="sf[style][delay]" class="regular-text">
                            <p class="description"><?php esc_html_e('The delay time before showing the results (this will allow the user to input more text before searching) -  <b>(in millisecond, i.e 5000 = 5sec)</b>.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Border width', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input style="width:40px" type="text" value="<?php echo esc_attr($styles['border-width']); ?>" name="sf[style][b_width]" class="regular-text">
                            <p class="description"><?php esc_html_e('The width of the search form border.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Border type', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <select name="sf[style][b_type]">
                                <option value="solid" <?php selected($values['border-type'], 'solid'); ?>><?php esc_html_e('solid', "ajaxy-instant-search"); ?></option>
                                <option value="dotted" <?php selected($values['border-type'], 'dotted'); ?>><?php esc_html_e('dotted', "ajaxy-instant-search"); ?></option>
                                <option value="dashed" <?php selected($values['border-type'], 'dashed'); ?>><?php esc_html_e('dashed', "ajaxy-instant-search"); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('The type of the search form border.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Border color', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input style="width:52px" type="text" value="<?php echo esc_attr($values['border-color']); ?>" name="sf[style][b_color]" class="regular-text">
                            <p class="description"><?php esc_html_e('The color of the search form border (color value is hexa-decimal).', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <h3><?php esc_html_e('Search Results box', "ajaxy-instant-search"); ?></h3>
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Width', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input style="width:40px" type="text" value="<?php echo esc_attr($styles['results_width']); ?>" name="sf[style][results_width]" class="regular-text">
                            <select name="sf[style][results_width_unit]">
                                <option value="px" <?php selected($styles['results_width_unit'], 'px'); ?>><?php esc_html_e('Pixels', "ajaxy-instant-search"); ?></option>
                                <option value="%" <?php selected($styles['results_width_unit'], '%'); ?>><?php esc_html_e('Percent', "ajaxy-instant-search"); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('The width of the results box (width is per pixel) - the value should be integer.', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3><?php esc_html_e('More results box', "ajaxy-instant-search"); ?></h3>
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label><?php esc_html_e('Search Url', "ajaxy-instant-search"); ?></label></th>
                        <td>
                            <input type="text" value="<?php echo esc_attr($styles['search_url']); ?>" name="sf[style][url]" class="regular-text">
                            <p class="description"><?php esc_html_e('This search URL for the "See more results"', "ajaxy-instant-search"); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h3><?php esc_html_e('Credits', "ajaxy-instant-search"); ?></h3>
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td colspan="2">
                            <input type="checkbox" name="sf[style][credits]" <?php checked($styles['credits'], 1); ?> value="1" />
                            <span class="description"><?php esc_html_e('Author "Powered by" link and credits.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="ajaxy-form-right">
        <h3><?php esc_html_e('Shortcode', "ajaxy-instant-search"); ?></h3>
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td scope="row">
                            <button class="button-primary" name="sf_submit" type="submit"><?php esc_html_e('Generate shortcode', "ajaxy-instant-search"); ?></button>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <textarea id="shortcode-text" style="width:99%;min-height:150px"></textarea>
                            <span class="description"><?php esc_html_e('Copy the shortcode to where you want it to appear.', "ajaxy-instant-search"); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>