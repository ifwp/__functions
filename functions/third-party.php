<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__build_update_checker')){
    function __build_update_checker(...$args){
        $r = __require_plugin_update_checker();
        if(is_wp_error($r)){
            return $r;
        }
        return Puc_v4_Factory::buildUpdateChecker(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__html')){
    function __html(...$args){
        $r = __require_simplehtmldom();
        if(is_wp_error($r)){
            return $r;
        }
        return str_get_html(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_cloudflare')){
    function __seems_cloudflare(){
        return isset($_SERVER['HTTP_CF_RAY']);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__serializable_closure')){
    function __serializable_closure($closure = null){
        $r = __require_closure();
        if(is_wp_error($r)){
            return $r;
        }
        return new Opis\Closure\SerializableClosure($closure);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__tgmpa')){
    function __tgmpa(...$args){
        $r = __require_tgm_plugin_activation();
        if(is_wp_error($r)){
            return $r;
        }
        return tgmpa(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__xlsx')){
    function __xlsx(...$args){
        $r = __require_php_xlsxwriter();
        if(is_wp_error($r)){
            return $r;
        }
        return new XLSXWriter(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__zoom_jwt')){
	function __zoom_jwt($api_key = '', $api_secret = ''){
        $r = __require_php_jwt();
        if(is_wp_error($r)){
            return $r;
        }
        $payload = [
            'exp' => time() + DAY_IN_SECONDS,
            'iss' => $api_key,
        ];
        return Firebase\JWT\JWT::encode($payload, $api_secret);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
