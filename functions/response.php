<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__response')){
    function __response($response = null){
        if(!class_exists('__response')){
            require_once(__PATH . 'classes/response.php');
        }
        return new __response($response);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__response_error')){
    function __response_error($data = '', $code = 0, $message = ''){
        if(!$code){
            $code = 500;
        }
        if(!$message){
            $message = get_status_header_desc($code);
        }
        if(!$message){
            $message = __('Something went wrong.');
        }
        $success = false;
        return __response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__response_success')){
    function __response_success($data = '', $code = 0, $message = ''){
        if(!$code){
            $code = 200;
        }
        if(!$message){
            $message = get_status_header_desc($code);
        }
        if(!$message){
            $message = 'OK';
        }
        $success = true;
        return __response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_response')){
    function __seems_response($response = []){
        return __array_keys_exist(['code', 'data', 'message', 'success'], $response);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_successful')){
    function __seems_successful($data = null){
        if(!is_numeric($data)){
            if($data instanceof __response){
                $data = $data->code;
            } else {
                return false;
            }
        } else {
            $data = (int) $data;
        }
        return ($data >= 200 and $data < 300);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_wp_http_requests_response')){
    function __seems_wp_http_requests_response($data = null){
        return (__array_keys_exist(['body', 'cookies', 'filename', 'headers', 'http_response', 'response'], $data) and ($data['http_response'] instanceof WP_HTTP_Requests_Response));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
