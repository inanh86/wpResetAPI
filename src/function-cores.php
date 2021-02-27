<?php 
if(!defined('ABSPATH')) exit;

function docFile($filename) {
    $file = dirname( API_PLUGIN_FILE ) .'/src'.$filename;
    $file = file_get_contents($file);
    return json_decode($file);
}