<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__download')){
    function __download($url = '', $args = []){
        $args = wp_parse_args($args, [
            'filename' => '',
            'timeout' => 300,
        ]);
        if($args['filename']){
            if(!__in_uploads($args['filename'])){
                return __error(sprintf(__('Unable to locate needed folder (%s).'), 'uploads'));
            }
        } else {
            $download_dir = __download_dir();
            if(is_wp_error($download_dir)){
                return $download_dir;
            }
            $args['filename'] = $download_dir . '/' . uniqid() . '-' . __filename($url); // do not use wp_unique_filename before the {@see 'init'} action hook; everything will break.
        }
        $args['stream'] = true;
        $args['timeout'] = __sanitize_timeout($args['timeout']);
        $response = __remote($url, $args)->get();
        if(!$response->success){
            @unlink($args['filename']);
            return $response->to_wp_error();
        }
        return $args['filename'];
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__remote')){
    function __remote($url = '', $args = []){
        if(!class_exists('__remote')){
            require_once(__PATH . 'classes/remote.php');
        }
        return new __remote($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__support_authorization_header')){
    function __support_authorization_header(){
        __one('mod_rewrite_rules', function($rules){
            $rule = 'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]';
            if(strpos($rule, $rules) === false){
                $rules = str_replace('RewriteEngine On', 'RewriteEngine On' . "\n" . $rule, $rules);
            }
            return $rules;
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
