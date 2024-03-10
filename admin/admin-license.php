<?php

/** @var \Ajaxy\LiveSearch\SF $AjaxyLiveSearch */
global $AjaxyLiveSearch;
$message = false;

$value = isset($_POST['sf']) ? $_POST['sf']['license'] : '';
$licensed = $AjaxyLiveSearch->is_licensed();
if ($licensed) {
    $message = "Your product is activated!";
} elseif (trim($value) != "") {
    $active = $AjaxyLiveSearch->activate_license($value);
    $message = $active ? "Your product is activated!" : "The license key you provided is invalid";
}
?>
<?php if ($message) : ?>
    <div id="message" class="updated">
        <p><?php echo $message; ?></p>
    </div>
<?php endif; ?>
<div class="ajaxy-wrap">
    <h3><?php _e('License'); ?></h3>
    <div>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('License Key'); ?></label></th>
                    <td>
                        <input type="password" value="<?php echo $value; ?>" name="sf[license]" class="regular-text">
                        <p class="description"><?php _e('Please enter the license key from your account'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br class="clear" />
    <input class="button-primary" name="sf_submit" type="submit" value="Activate" />
    <br class="clear" />
</div>