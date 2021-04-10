<?php
/*
Author: IFWP
Author URI: https://github.com/ifwp
Description: A collection of useful functions for your WordPress theme's __functions.php.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network: true
Plugin Name: __functions
Plugin URI: https://github.com/ifwp/__functions
Requires at least: 5.0
Requires PHP: 5.6
Text Domain: __functions
Version: 1.4.10
*/

if(defined('ABSPATH')){
    require_once(plugin_dir_path(__FILE__) . 'functions.php');
    $__fs = __filesystem();
    if(is_wp_error($__fs)){
        __add_admin_notice('<strong>__' . strtolower(__('Error')) . '</strong>: ' . $__fs->get_error_message());
    }
    unset($__fs);
    __build_update_checker('https://github.com/ifwp/__functions', __FILE__, '__functions');
    __on('after_setup_theme', function(){
        $file = get_stylesheet_directory() . '/__functions.php';
        if(file_exists($file)){
            require_once($file);
        }
    });
}
