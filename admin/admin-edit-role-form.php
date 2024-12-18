<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Advanced form for inclusion in the administration panels.
 *
 * @package WordPress
 * @subpackage Administration
 */

$type = sanitize_text_field($_GET['type']);
$role = false;

$role = get_role(sanitize_text_field($_GET['name']));

global $wp_roles;
$roles = $wp_roles->get_names();

$role_label = isset($roles[$role->name]) ?  $roles[$role->name] : $role->name;

/** @var \Ajaxy\LiveSearch\SF $AjaxyLiveSearch */
global $AjaxyLiveSearch;
$message = false;
if (!empty($role)) {
    $is_post = isset($_POST['sf_post']) && !empty($_POST['sf_post']) ? true : false;
    if (!empty($is_post)) {
        if (isset($_REQUEST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'sf_edit')) {
            if (isset($_POST['sf_' . $role->name]) && !empty($_POST['sf_' . $role->name])) {
                $AjaxyLiveSearch->set_templates('role_' . $role->name, $_POST['sf_' . $role->name]);
            }

            $values = array(
                'title' => sanitize_text_field($_POST['sf_title_' . $role->name] ?? ''),
                'show' => (int)(sanitize_text_field($_POST['sf_show_' . $role->name] ?? '0')),
                'search_content' => sanitize_text_field($_POST['sf_search_content_' . $role->name] ?? ''),
                'limit' => sanitize_text_field($_POST['sf_limit_' . $role->name] ?? ''),
                'order' => sanitize_text_field($_POST['sf_order_' . $role->name] ?? ''),
                'excludes' => array_map('sanitize_text_field', $_POST['sf_exclude_' . $role->name] ?? [])
            );
            if (isset($_POST['sf_order_results_' . $role->name]) && !empty($_POST['sf_order_results_' . $role->name])) {
                $values['order_results'] = sanitize_text_field(trim($_POST['sf_order_results_' . $role->name]));
            }
            if (isset($_POST['sf_ushow_' . $role->name]) && !empty($_POST['sf_ushow_' . $role->name])) {
                $values['ushow'] = sanitize_text_field(trim($_POST['sf_ushow_' . $role->name]));
            }
            $AjaxyLiveSearch->set_setting('role_' . $role->name, $values);

            $message = esc_html__("Settings saved", "ajaxy-instant-search");
        } else {
            $message = esc_html__("Settings have been already saved", "ajaxy-instant-search");
        }
    }

    $setting = (array)$AjaxyLiveSearch->get_setting('role_' . $role->name, false);
    $allowed_tags = array('ID', 'user_login', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'display_name', 'author_link');

    $title  = sprintf(esc_attr('Edit %s template & settings', "ajaxy-instant-search"), $role_label);
    $notice = '';
?>

    <div class="wrap">
        <h2><?php echo esc_html($title); ?></h2>
        <?php if ($notice) : ?>
            <div id="notice" class="error">
                <p><?php echo esc_html($notice) ?></p>
            </div>
        <?php endif; ?>
        <?php if ($message) : ?>
            <div id="message" class="updated">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        <form name="post" action="" method="post" id="post">
            <?php wp_nonce_field('sf_edit'); ?>
            <input type="hidden" name="sf_post" value="<?php echo esc_attr($role->name); ?>" />
            <div id="poststuff" class="metabox-holder has-right-sidebar">
                <div id="side-info-column" class="inner-sidebar">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="submitdiv" class="postbox ">
                            <div class="handlediv" title="<?php esc_html_e('Click to toggle', "ajaxy-instant-search"); ?>"><br></div>
                            <h3 class="hndle"><span><?php esc_html_e('Save Settings', "ajaxy-instant-search"); ?></span></h3>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">
                                    <div id="minor-publishing">
                                        <div id="misc-publishing-actions">
                                            <div class="misc-pub-section"><label><?php esc_html_e('Status:', "ajaxy-instant-search"); ?></label>
                                                <p><select name="sf_show_<?php echo esc_attr($role->name); ?>">
                                                        <option value="1" <?php selected($setting['show'], 1); ?>><?php esc_html_e('Show on search', "ajaxy-instant-search"); ?></option>
                                                        <option value="0" <?php selected($setting['show'], 0); ?>><?php esc_html_e('hide on search', "ajaxy-instant-search"); ?></option>
                                                    </select></p>
                                            </div>
                                            <div class="misc-pub-section"><label><?php esc_html_e('Search mode:', "ajaxy-instant-search"); ?></label>
                                                <p><select name="sf_search_content_<?php echo esc_attr($role->name); ?>">
                                                        <option value="0" <?php selected($setting['search_content'], 0); ?>><?php esc_html_e('Only title', "ajaxy-instant-search"); ?></option>
                                                        <option value="1" <?php selected($setting['search_content'], 1); ?>><?php esc_html_e('Title and content (Slow)', "ajaxy-instant-search"); ?></option>
                                                    </select></p>
                                            </div>
                                            <?php if ($type != 'category') : ?>
                                                <div class="misc-pub-section"><label><?php esc_html_e('Order results by:', "ajaxy-instant-search"); ?></label>
                                                    <p><select name="sf_order_results_<?php echo esc_attr($role->name); ?>">
                                                            <option value="" <?php selected($setting['order_results'], ''); ?>><?php esc_html_e('None (Default)', "ajaxy-instant-search"); ?></option>
                                                            <option value="post_title asc" <?php selected($setting['order_results'], 'post_title asc'); ?>><?php esc_html_e('Title - Ascending', "ajaxy-instant-search"); ?></option>
                                                            <option value="post_title desc" <?php selected($setting['order_results'], 'post_title desc'); ?>><?php esc_html_e('Title - Descending', "ajaxy-instant-search"); ?></option>
                                                            <option value="post_date asc" <?php selected($setting['order_results'], 'post_date asc'); ?>><?php esc_html_e('Date - Ascending', "ajaxy-instant-search"); ?></option>
                                                            <option value="post_date desc" <?php selected($setting['order_results'], 'post_date desc'); ?>><?php esc_html_e('Date - Descending', "ajaxy-instant-search"); ?></option>
                                                        </select></p>
                                                </div>
                                            <?php else : ?>
                                                <div class="misc-pub-section"><label>
                                                        <?php esc_html_e('Show "Posts under Category":', "ajaxy-instant-search"); ?>
                                                    </label>
                                                    <p><select name="sf_ushow_<?php echo esc_attr($role->name); ?>">
                                                            <option value="1" <?php selected($setting['ushow'], 1); ?>><?php esc_html_e('Show', "ajaxy-instant-search"); ?></option>
                                                            <option value="0" <?php selected($setting['ushow'], 0); ?>><?php esc_html_e('hide', "ajaxy-instant-search"); ?></option>
                                                        </select></p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="misc-pub-section " id="visibility"><label><?php esc_html_e('Order:', "ajaxy-instant-search"); ?></label>
                                                <p><input type="text" style="width:50px" value="<?php echo esc_html($setting['order']); ?>" name="sf_order_<?php echo esc_attr($role->name); ?>" /></p>
                                            </div>
                                            <div class="misc-pub-section " id="limit_results"><label><?php esc_html_e('Limit results to:', "ajaxy-instant-search"); ?></label>
                                                <p><input type="text" style="width:50px" value="<?php echo esc_html($setting['limit']); ?>" name="sf_limit_<?php echo esc_attr($role->name); ?>" /></p>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <input type="submit" name="save" id="save" class="button-primary" value="<?php esc_html_e('Save', "ajaxy-instant-search"); ?>" tabindex="5" accesskey="p">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                        $excludes = (isset($setting['excludes']) && sizeof($setting['excludes']) > 0 ? $setting['excludes'] : array());

                        ?>
                        <div id="submitdiv" class="postbox ">
                            <div class="handlediv" title="<?php esc_html_e('Click to toggle', "ajaxy-instant-search"); ?>"><br></div>
                            <?php /* translators: %s is replaced with the user role label */ ?>
                            <h3 class="hndle"><span><?php echo esc_html(sprintf(__('Excluded "%s"', "ajaxy-instant-search"), $role_label)); ?></span></h3>
                            <div class="inside">
                                <div class="submitbox">
                                    <div class="misc-pub-section">
                                        <?php
                                        $blogusers = get_users('role=' . $role->name);

                                        if (sizeof($blogusers) > 0) {
                                        ?>
                                            <h4><?php echo esc_attr($role_label); ?></h4>
                                            <div style="max-height:200px;overflow:auto">
                                                <ul>
                                                    <?php
                                                    foreach ($blogusers as $user) {
                                                    ?>
                                                        <li>
                                                            <input autocomplete="off" type="checkbox" <?php checked(in_array($user->ID, (array)$excludes), true); ?> name="sf_exclude_<?php echo esc_attr($role->name); ?>[]" value="<?php echo esc_attr($user->ID); ?>" />
                                                            <?php echo esc_html($user->display_name); ?>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <hr />
                                        <?php /* translators: %s is replaced with the role label */ ?>
                                        <p class="small"><?php echo esc_html(sprintf(__('Prevent selected "%s" from appearing in the search results', "ajaxy-instant-search"), $role_label)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="post-body">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php esc_html_e('Enter title here', "ajaxy-instant-search"); ?></label>
                                <input type="text" name="sf_title_<?php echo esc_attr($role->name); ?>" size="30" tabindex="1" value="<?php echo esc_attr(empty($setting['title']) ? $role_label : $setting['title']); ?>" id="title" autocomplete="off" />
                            </div>
                            <div class="inside">
                            </div>
                        </div>
                        <div id="postdivrich" class="postarea">
                            <?php /* translators: %s is replaced with the role label */ ?>
                            <h2><?php echo esc_html(sprintf(__('"%s" Template', "ajaxy-instant-search"), $role_label)); ?></h2>
                            <p><?php esc_html_e('Changes are live, use the tags below to customize the data replaced by each template.', "ajaxy-instant-search"); ?></p>
                            <?php wp_editor($AjaxyLiveSearch->get_templates('role_' . $role->name, $type), 'sf_' . $role->name); ?>
                            <table id="post-status-info" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td><b><?php esc_html_e('Tags:', "ajaxy-instant-search"); ?></b>
                                            {<?php echo esc_html(implode("}, {", $allowed_tags)); ?>}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br class="clear" />
            </div>
        </form>
    </div>
<?php } else {
?>
    <h3><?php esc_html_e('Oops it looks like this page is no longer available or have been deleted :(', "ajaxy-instant-search"); ?></h3>
<?php
}
?>