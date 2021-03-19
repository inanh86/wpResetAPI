<?php 
if(!defined('ABSPATH')) exit;

function docFile($filename) {
    $file = dirname( API_PLUGIN_FILE ) .'/src'.$filename;
    $file = file_get_contents($file);
    return json_decode($file);
}
/**
 * Kiểm tra đây có phải là số điện thoại hày không
 * @param string $phone
 */
function is_phone_number($phone) {
    return preg_match('/^[0-9]{9}+$/', $phone);
}