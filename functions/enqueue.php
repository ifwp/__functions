<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_ace')){
    function __enqueue_ace(){
        wp_enqueue_script('ace', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js', [], '1.4.12', true);
        wp_enqueue_script('ace-language-tools', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.min.js', ['ace'], '1.4.12', true);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_bootstrap')){
    function __enqueue_bootstrap($ver = 4, $bundle = true){
        switch($ver){
            case 4:
                if($bundle){
                    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', ['jquery'], '4.6.0', true);
                } else {
                    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js', ['jquery'], '4.6.0', true);
                }
                wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css', [], '4.6.0');
                break;
            case 5:
                if($bundle){
                    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.0.1', true);
                } else {
                    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js', ['jquery'], '5.0.1', true);
                }
                wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css', [], '5.0.1');
                break;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_bs_custom_file_input')){
    function __enqueue_bs_custom_file_input(){
        wp_add_inline_script('bs-custom-file-input', 'jQuery(function(){ bsCustomFileInput.init(); });');
        wp_enqueue_script('bs-custom-file-input', 'https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js', ['jquery'], '1.3.4', true);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_dashicons')){
    function __enqueue_dashicons(){
        wp_enqueue_style('dashicons');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_fontawesome')){
    function __enqueue_fontawesome($ver = 5, $pro = false){
        switch($ver){
            case 3:
                wp_enqueue_style('fontawesome', 'https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', [], '3.2.1');
                break;
            case 4:
                wp_enqueue_style('fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', [], '4.7.0');
                break;
            case 5:
                if($pro){
                    wp_enqueue_style('fontawesome', 'https://pro.fontawesome.com/releases/v5.15.3/css/all.css', [], '5.15.3');
                } else {
                    wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.15.3/css/all.css', [], '5.15.3');
                }
                break;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_fontawesome_kit')){
    function __enqueue_fontawesome_kit($kit = ''){
        $url = wp_http_validate_url($kit);
        if($url){
            wp_enqueue_script('fontawesome', $url);
        } else {
            wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/' . rtrim($kit, '.js') . '.js');
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_functions')){
    function __enqueue_functions(){
        wp_enqueue_script('__functions', plugin_dir_url(__FILE__) . 'functions.js', ['jquery'], filemtime(plugin_dir_path(__FILE__) . 'functions.js'), true);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_jquery')){
    function __enqueue_jquery(){
        wp_enqueue_script('jquery');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_popper')){
    function __enqueue_popper($ver = 1){
        switch($ver){
            case 1:
                wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', [], '1.16.1', true);
                break;
            case 2:
                wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js', [], '2.9.1', true);
                break;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_select2')){
    function __enqueue_select2(){
        wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js', [], '4.0.13', true);
        wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', [], '4.0.13');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_stylesheet')){
    function __enqueue_stylesheet(){
        wp_enqueue_style(get_stylesheet(), get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
