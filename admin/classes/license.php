<?php

namespace Ajaxy\LiveSearch\Admin\Classes;

class License
{
    public $lic;
    public $server = 'http://www.ajaxy.org';
    public $api_key = '56d4131a60f6c8.69447567';
    private $wp_option  = '_ajaxy_live_search_license';
    private $product_id = 'AJAXY-LIVE-SEARCH';
    public $err;

    public function __construct()
    {
    }
    public function check($lic = null)
    {
        if ($this->is_licensed())
            $this->lic = get_option($this->wp_option);
        else
            $this->lic = $lic;
    }

    public function is_licensed()
    {
        $lic = get_option($this->wp_option);
        if (!empty($lic)) {
            return true;
        }
        return true;
    }

    /**
     * send query to server and try to active lisence
     * @return boolean
     */
    public function active($lic)
    {
        $url = 'http://www.ajaxy.org' . '/?secret_key=' . $this->api_key . '&slm_action=slm_activate&license_key=' . $lic . '&registered_domain=' . get_bloginfo('url') . '&item_reference=' . $this->product_id;
        $response = wp_remote_get($url, array('timeout' => 20, 'sslverify' => false));

        $license_data = null;
        if (is_array($response)) {
            $json = $response['body']; // use the content
            $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', utf8_encode($json));
            $license_data = json_decode($json);
        }
        if ($license_data && $license_data->result == 'success') {
            update_option($this->wp_option, $lic);
            return true;
        } else {
            $this->err = $license_data ? $license_data->message : __('Failed to retrieve licensing information, Please try again later');
            return false;
        }
    }
}
