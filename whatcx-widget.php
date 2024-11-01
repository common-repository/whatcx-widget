<?php 
/**
* Plugin Name: WhatCX Widget
* Plugin URI: https://www.whatcx.com/
* Description: WhatCX Widget plugin is an official plugin maintained by the WhatCX team that helps you to add WhatsApp widgets on your WordPress sites in a hassle-free manner and connect with people out there by activating and setting up your API key. 
* Version: 1.0.1
* Author: EdgeCX
* License: GPLv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: whatcx-widget
**/


include('api-action.php');

function whatcx_widget_my_plugin_menu() {
	add_menu_page( 'WhatCX Widget', 'WhatCX Widget', 'manage_options', 'whatcx-widget', 'whatcx_widget_plugin',plugins_url( 'whatcx-widget/assets/img/icon.png' ),100);
}
add_action( 'admin_menu', 'whatcx_widget_my_plugin_menu' );


function whatcx_widget_load_whatcx_scripts(){
    wp_enqueue_script('whatcx-widget-js','https://widget.whatcx.com/widget.js');
    wp_enqueue_style('whatcx-widget-css','https://widget.whatcx.com/widget.css');
}

$getActiveWidget = get_option('whatcx_widget_active');
if(!empty($getActiveWidget)){
    add_action('wp_enqueue_scripts', 'whatcx_widget_load_whatcx_scripts');

    add_filter('script_loader_tag', function ($tag, $handle, $src){
        if($handle == 'whatcx-widget-js'){
            $fetch = get_option('whatcx_widget_active');
            $widgetID = null;
            $widgetNonse = null;
            // Set secret keys
            $secret_key = wp_salt('NONCE_SALT'); // Change this!
            $secret_iv = wp_salt('auth'); // Change this!
            $key = hash('sha256',$secret_key);
            $iv = substr(hash('sha256',$secret_iv),0,16);
            $widgetID = openssl_decrypt(base64_decode($fetch['widgetID']),"AES-256-CBC",$key,0,$iv);
            $widgetNonse = openssl_decrypt(base64_decode($fetch['widgetNonse']),"AES-256-CBC",$key,0,$iv);
           
            
            $tag = '<script defer src="'.$src.'" id="whatcx-widget-script" data-id="'.$widgetID.'" data-nonce="'.$widgetNonse.'"></script>';
        }
        return $tag;
        
    },10,3);
}

function whatcx_widget_plugin() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}    
    include('form.php');
}




?>