<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_closure')){
    function __require_closure(){
        if(class_exists('Opis\Closure\SerializableClosure')){
            return true;
        }
        $library = __require('https://github.com/opis/closure/archive/3.6.2.zip', 'closure-3.6.2');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/autoload.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_php_jwt')){
    function __require_php_jwt(){
        if(class_exists('Firebase\JWT\JWT')){
            return true;
        }
        $library = __require('https://github.com/firebase/php-jwt/archive/refs/tags/v5.2.1.zip', 'php-jwt-5.2.1');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/src/BeforeValidException.php');
        require_once($library->dir . '/src/ExpiredException.php');
        require_once($library->dir . '/src/JWK.php');
        require_once($library->dir . '/src/JWT.php');
        require_once($library->dir . '/src/SignatureInvalidException.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_php_xlsxwriter')){
    function __require_php_xlsxwriter(){
        if(class_exists('XLSXWriter')){
            return true;
        }
        $library = __require('https://github.com/mk-j/PHP_XLSXWriter/archive/refs/tags/0.38.zip', 'PHP_XLSXWriter-0.38');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/xlsxwriter.class.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_plugin_update_checker')){
    function __require_plugin_update_checker(){
        if(class_exists('Puc_v4_Factory')){
            return true;
        }
        $library = __require('https://github.com/YahnisElsts/plugin-update-checker/archive/refs/tags/v4.11.zip', 'plugin-update-checker-4.11');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/plugin-update-checker.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_simplehtmldom')){
    function __require_simplehtmldom(){
        if(class_exists('simple_html_dom')){
            return true;
        }
        $library = __require('https://github.com/simplehtmldom/simplehtmldom/archive/refs/tags/1.9.1.zip', 'simplehtmldom-1.9.1');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/simple_html_dom.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require_tgm_plugin_activation')){
    function __require_tgm_plugin_activation(){
        if(class_exists('TGM_Plugin_Activation')){
            return true;
        }
        $library = __require('https://github.com/TGMPA/TGM-Plugin-Activation/archive/refs/tags/2.6.1.zip', 'TGM-Plugin-Activation-2.6.1');
        if(!$library->success){
            return $library->to_wp_error();
        }
        require_once($library->dir . '/class-tgm-plugin-activation.php');
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
