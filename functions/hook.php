<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__did')){
    function __did($tag = ''){
        return did_action($tag);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__do')){
    function __do($tag = '', ...$arg){
        return do_action($tag, ...$arg);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__off')){
    function __off($tag = '', $function_to_remove = '', $priority = 10){
        return remove_filter($tag, $function_to_remove, $priority);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__on')){
    function __on($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        add_filter($tag, $function_to_add, $priority, $accepted_args);
        return _wp_filter_build_unique_id($tag, $function_to_add, $priority);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__one')){
    function __one($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        if(!array_key_exists('__hooks', $GLOBALS)){
            $GLOBALS['__hooks'] = [];
        }
        if(!array_key_exists($tag, $GLOBALS['__hooks'])){
            $GLOBALS['__hooks'][$tag] = [];
        }
        $idx = _wp_filter_build_unique_id($tag, $function_to_add, $priority);
        if($function_to_add instanceof Closure){
            $md5 = __md5_closure($function_to_add);
            if(is_wp_error($md5)){
                $md5 = md5($idx);
            }
        } else {
            $md5 = md5($idx);
        }
        if(array_key_exists($md5, $GLOBALS['__hooks'][$tag])){
            return $GLOBALS['__hooks'][$tag][$md5];
        } else {
            add_filter($tag, $function_to_add, $priority, $accepted_args);
            $GLOBALS['__hooks'][$tag][$md5] = $idx;
            return $idx;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
