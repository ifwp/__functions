<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__destroy_other_sessions')){
    function __destroy_other_sessions(){
        __one('init', 'wp_destroy_other_sessions');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__support_sessions')){
    function __support_sessions(){
        __one('init', function(){
			if(!session_id()){
        		session_start();
        	}
		}, 9);
		__one('wp_login', function(){
			if(session_id()){
        		session_destroy();
        	}
		});
        __one('wp_logout', function(){
			if(session_id()){
        		session_destroy();
        	}
		});
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
