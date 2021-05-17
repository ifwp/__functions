<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__add_external_rule')){
    function __add_external_rule($regex = '', $query = ''){
        if(!array_key_exists('__external_rules', $GLOBALS)){
			$GLOBALS['__external_rules'] = [];
		}
		$rule = [
			'query' => $query,
            'regex' => $regex,
		];
		$md5 = __md5($rule);
		if(!array_key_exists($md5, $GLOBALS['__external_rules'])){
			$GLOBALS['__external_rules'][$md5] = $rule;
		}
		__one('admin_init', function(){
            if(current_user_can('manage_options')){
				if(!is_array($GLOBALS['__external_rules'])){
					return;
				}
				$add_admin_notice = false;
				foreach($GLOBALS['__external_rules'] as $rule){
					$regex = str_replace(home_url('/'), '', $rule['regex']);
					$query = str_replace(home_url('/'), '', $rule['query']);
					if(!__external_rule_exists($regex, $query)){
						$add_admin_notice = true;
						break;
					}
				}
				if($add_admin_notice){
					__add_admin_notice(sprintf(__('You should update your %s file now.'), '<code>.htaccess</code>') . ' ' . sprintf('<a href="%s">%s</a>', esc_url(admin_url('options-permalink.php')), __('Flush permalinks')) . '.');
				}
            }
		});
		__one('generate_rewrite_rules', function($wp_rewrite){
			if(!is_array($GLOBALS['__external_rules'])){
				return;
			}
			foreach($GLOBALS['__external_rules'] as $rule){
				$regex = str_replace(home_url('/'), '', $rule['regex']);
				$query = str_replace(home_url('/'), '', $rule['query']);
				$wp_rewrite->add_external_rule($regex, $query);
			}
		});
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__external_rule_exists')){
    function __external_rule_exists($regex = '', $query = ''){
        if(!array_key_exists('__rewrite_rules', $GLOBALS)){
			$GLOBALS['__rewrite_rules'] = array_filter(extract_from_markers(get_home_path() . '.htaccess', 'WordPress'));
		}
        $regex = str_replace(home_url('/'), '', $regex);
    	$regex = str_replace('.+?', '.+', $regex);
    	$query = str_replace(home_url('/'), '', $query);
    	$rule = 'RewriteRule ^' . $regex . ' ' . __home_root() . $query . ' [QSA,L]';
    	return in_array($rule, $GLOBALS['__rewrite_rules']);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
