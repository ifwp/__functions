<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__add_admin_notice')){
    function __add_admin_notice($admin_notice = '', $class = 'error', $is_dismissible = false){
        if(!array_key_exists('__admin_notices', $GLOBALS)){
            $GLOBALS['__admin_notices'] = [];
        }
        $md5 = md5($admin_notice);
        if(!array_key_exists($md5, $GLOBALS['__admin_notices'])){
            if(!in_array($class, ['error', 'info', 'success', 'warning'])){
                $class = 'warning';
            }
            if($is_dismissible){
                $class .= ' is-dismissible';
            }
            $GLOBALS['__admin_notices'][$md5] = '<div class="notice notice-' . $class . '"><p>' . $admin_notice . '</p></div>';
        }
        __one('admin_notices', function(){
            if(!is_array($GLOBALS['__admin_notices'])){
                return;
            }
            foreach($GLOBALS['__admin_notices'] as $admin_notice){
                echo $admin_notice;
            }
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__add_rewrite_rule')){
    function __add_rewrite_rule($regex = '', $query = ''){
        if(!array_key_exists('__rewrite_rules', $GLOBALS)){
			$GLOBALS['__rewrite_rules'] = [];
		}
		$rule = [
			'query' => $query,
            'regex' => $regex,
		];
		$md5 = __md5($rule);
		if(!array_key_exists($md5, $GLOBALS['__rewrite_rules'])){
			$GLOBALS['__rewrite_rules'][$md5] = $rule;
		}
		__one('admin_init', function(){
            if(current_user_can('manage_options')){
				if(!is_array($GLOBALS['__rewrite_rules'])){
					return;
				}
				$add_admin_notice = false;
				foreach($GLOBALS['__rewrite_rules'] as $rule){
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
			if(!is_array($GLOBALS['__rewrite_rules'])){
				return;
			}
			foreach($GLOBALS['__rewrite_rules'] as $rule){
				$regex = str_replace(home_url('/'), '', $rule['regex']);
				$query = str_replace(home_url('/'), '', $rule['query']);
				$wp_rewrite->add_external_rule($regex, $query);
			}
		});
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__are_plugins_active')){
    function __are_plugins_active($plugins = []){
        if(!is_array($plugins)){
            return false;
        }
        foreach($plugins as $plugin){
            if(!__is_plugin_active($plugin)){
                return false;
            }
        }
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__array_keys_exist')){
    function __array_keys_exist($keys = [], $array = []){
        if(!is_array($keys) or !is_array($array)){
            return false;
        }
        foreach($keys as $key){
            if(!array_key_exists($key, $array)){
                return false;
            }
        }
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__base64_urldecode')){
    function __base64_urldecode($data = '', $strict = false){
        return base64_decode(strtr($data, '-_', '+/'), $strict);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__base64_urlencode')){
    function __base64_urlencode($data = ''){
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

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

if(!function_exists('__clone_role')){
    function __clone_role($source = '', $destination = '', $display_name = ''){
        $role = get_role($source);
        if(is_null($role)){
            return null;

        }
        return add_role(sanitize_title($destination), $display_name, $role->capabilities);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__current_screen_in')){
    function __current_screen_in($ids = []){
        global $current_screen;
        if(!is_array($ids)){
            return false;
        }
        if(!isset($current_screen)){
            return false;
        }
        return in_array($current_screen->id, $ids);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__current_screen_is')){
    function __current_screen_is($id = ''){
        global $current_screen;
        if(!is_string($id)){
            return false;
        }
        if(!isset($current_screen)){
            return false;
        }
        return ($current_screen->id == $id);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__custom_login_logo')){
    function __custom_login_logo($attachment_id = 0){
        __one('login_enqueue_scripts', function() use($attachment_id){
            if(wp_attachment_is_image($attachment_id)){
                $custom_logo = wp_get_attachment_image_src($attachment_id, 'medium'); ?>
    			<style type="text/css">
    				#login h1 a,
    				.login h1 a {
    					background-image: url(<?php echo $custom_logo[0]; ?>);
    					background-size: <?php echo $custom_logo[1] / 2; ?>px <?php echo $custom_logo[2] / 2; ?>px;
    					height: <?php echo $custom_logo[2] / 2; ?>px;
    					width: <?php echo $custom_logo[1] / 2; ?>px;
    				}
    			</style><?php
            }
		});
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__destroy_other_sessions')){
    function __destroy_other_sessions(){
        __one('init', 'wp_destroy_other_sessions');
    }
}

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

if(!function_exists('__error')){
    function __error($message = '', $data = ''){
        if(!$message){
            $message = __('Something went wrong.');
        }
        return new WP_Error('__error', $message, $data);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__external_rule_exists')){
    function __external_rule_exists($regex = '', $query = ''){
        $regex = str_replace(home_url('/'), '', $regex);
    	$regex = str_replace('.+?', '.+', $regex);
    	$query = str_replace(home_url('/'), '', $query);
    	$rule = 'RewriteRule ^' . $regex . ' ' . __home_root() . $query . ' [QSA,L]';
    	$rules = array_filter(extract_from_markers(get_home_path() . '.htaccess', 'WordPress'));
    	return in_array($rule, $rules);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__filename')){
    function __filename($filename = ''){
        return preg_replace('/\?.*/', '', wp_basename($filename));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__filesystem')){
    function __filesystem(){
        global $wp_filesystem;
        if(!function_exists('get_filesystem_method')){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        if(get_filesystem_method() != 'direct'){
            return __error(__('Could not access filesystem.'));
        }
        if(!WP_Filesystem()){
            return __error(__('Filesystem error.'));
        }
        return $wp_filesystem;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__get_memory_size')){
    function __get_memory_size(){
        if(!function_exists('exec')){
            return 0;
        }
        exec('free -b', $output);
        $output = explode(' ', trim(preg_replace('/\s+/', ' ', $output[1])));
        return (int) $output[1];
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__home_root')){
    function __home_root(){
        $home_root = parse_url(home_url());
    	if(isset($home_root['path'])){
    		$home_root = trailingslashit($home_root['path']);
    	} else {
    		$home_root = '/';
    	}
    	return $home_root;
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

if(!function_exists('__is_array_assoc')){
    function __is_array_assoc($array = []){
        if(!is_array($array)){
            return false;
        }
        return (array_keys($array) !== range(0, count($array) - 1));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__is_doing_heartbeat')){
    function __is_doing_heartbeat(){
        return (defined('DOING_AJAX') and DOING_AJAX and isset($_POST['action']) and $_POST['action'] == 'heartbeat');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__is_plugin_active')){
    function __is_plugin_active($plugin = ''){
        if(!function_exists('is_plugin_active')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        return is_plugin_active($plugin);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__is_plugin_deactivating')){
    function __is_plugin_deactivating($file = ''){
        global $pagenow;
        if(!is_file($file)){
            return false;
        }
        return (is_admin() and $pagenow == 'plugins.php' and isset($_GET['action'], $_GET['plugin']) and $_GET['action'] == 'deactivate' and $_GET['plugin'] == plugin_basename($file));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__is_post_revision_or_auto_draft')){
    function __is_post_revision_or_auto_draft($post = null){
        return (wp_is_post_revision($post) or get_post_status($post) == 'auto-draft');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__ksort_deep')){
    function __ksort_deep($data = []){
        if(__is_array_assoc($data)){
            ksort($data);
            foreach($data as $index => $item){
                $data[$index] = __ksort_deep($item);
            }
        }
        return $data;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__local_login_header')){
    function __local_login_header(){
        __one('login_headertext', function($login_headertext){
			return get_option('blogname');
		});
		__one('login_headerurl', function($login_headerurl){
			return home_url();
		});
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__md5')){
    function __md5($data = null){
        if(is_object($data)){
            if($data instanceof Closure){
                return __md5_closure($data);
            } else {
                $data = json_decode(wp_json_encode($data), true);
            }
        }
        if(is_array($data)){
            $data = __ksort_deep($data);
            $data = maybe_serialize($data);
        }
        return md5($data);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__md5_closure')){
    function __md5_closure($data = null, $spl_object_hash = false){
        if($data instanceof Closure){
            $wrapper = __serializable_closure($data);
            if(is_wp_error($wrapper)){
                return $wrapper;
            }
            $serialized = maybe_serialize($wrapper);
            if(!$spl_object_hash){
                $serialized = str_replace(spl_object_hash($data), 'spl_object_hash', $serialized);
            }
            return md5($serialized);
        } else {
            return $this->error(__('Invalid object type.'));
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__md5_to_uuid4')){
    function __md5_to_uuid4($md5){
        if(strlen($md5) != 32){
            return '';
        }
        return substr($md5, 0, 8) . '-' . substr($md5, 8, 4) . '-' . substr($md5, 12, 4) . '-' . substr($md5, 16, 4) . '-' . substr($md5, 20, 12);
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

if(!function_exists('__post_type_labels')){
    function __post_type_labels($singular = '', $plural = '', $all = true){
        if(!$singular or !$plural){
            return [];
        }
        return [
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => 'Add New',
            'add_new_item' => 'Add New ' . $singular,
            'edit_item' => 'Edit ' . $singular,
            'new_item' => 'New ' . $singular,
            'view_item' => 'View ' . $singular,
            'view_items' => 'View ' . $plural,
            'search_items' => 'Search ' . $plural,
            'not_found' => 'No ' . strtolower($plural) . ' found.',
            'not_found_in_trash' => 'No ' . strtolower($plural) . ' found in Trash.',
            'parent_item_colon' => 'Parent ' . $singular . ':',
            'all_items' => ($all ? 'All ' : '') . $plural,
            'archives' => $singular . ' Archives',
            'attributes' => $singular . ' Attributes',
            'insert_into_item' => 'Insert into ' . strtolower($singular),
            'uploaded_to_this_item' => 'Uploaded to this ' . strtolower($singular),
            'featured_image' => 'Featured image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'filter_items_list' => 'Filter ' . strtolower($plural) . ' list',
            'items_list_navigation' => $plural . ' list navigation',
            'items_list' => $plural . ' list',
            'item_published' => $singular . ' published.',
            'item_published_privately' => $singular . ' published privately.',
            'item_reverted_to_draft' => $singular . ' reverted to draft.',
            'item_scheduled' => $singular . ' scheduled.',
            'item_updated' => $singular . ' updated.',
        ];
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__prepare')){
    function __prepare($str = '', ...$args){
        global $wpdb;
        if(!$args){
            return $str;
        }
        if(strpos($str, '%') === false){
            return $str;
        } else {
            return str_replace("'", '', $wpdb->remove_placeholder_escape($wpdb->prepare(...$args)));
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__remote')){
    function __remote($url = '', $args = []){
        if(!class_exists('__remote')){
            require_once(plugin_dir_path(__FILE__) . 'classes/remote.php');
        }
        return new __remote($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__remove_whitespaces')){
    function __remove_whitespaces($str = ''){
        return trim(preg_replace('/[\r\n\t\s]+/', ' ', $str));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__replace_script')){
    function __replace_script($handle = '', $src = '', $deps = [], $ver = false, $in_footer = false){
        if(wp_script_is($handle)){
            wp_dequeue_script($handle);
        }
        if(wp_script_is($handle, 'registered')){
            wp_deregister_script($handle);
        }
        wp_register_script($handle, $src, $deps, $ver, $in_footer);
        wp_enqueue_script($handle);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__replace_style')){
    function __replace_style($handle = '', $src = '', $deps = [], $ver = false){
        if(wp_style_is($handle)){
            wp_dequeue_style($handle);
        }
        if(wp_style_is($handle, 'registered')){
            wp_deregister_style($handle);
        }
        wp_register_style($handle, $src, $deps, $ver);
        wp_enqueue_style($handle);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__require')){
    function __require($url = '', $dir = ''){
        if(!class_exists('__require')){
            require_once(plugin_dir_path(__FILE__) . 'classes/require.php');
        }
        return new __require($url, $dir);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__response')){
    function __response($response = null){
        if(!class_exists('__response')){
            require_once(plugin_dir_path(__FILE__) . 'classes/response.php');
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

if(!function_exists('__sanitize_timeout')){
    function __sanitize_timeout($timeout = 0){
        $timeout = (int) $timeout;
        $max_execution_time = (int) ini_get('max_execution_time');
        if($max_execution_time){
            if(!$timeout or $timeout > $max_execution_time){
                $timeout = $max_execution_time - 1; // Prevents error 504
            }
        }
        if(__seems_cloudflare()){
            if(!$timeout or $timeout > 99){
                $timeout = 99; // Prevents error 524: https://support.cloudflare.com/hc/en-us/articles/115003011431#524error
            }
        }
        return $timeout;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_cloudflare')){
    function __seems_cloudflare(){
        return isset($_SERVER['HTTP_CF_RAY']);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_false')){
    function __seems_false($data = ''){
        return in_array((string) $data, ['off', 'false', '0', ''], true);
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

if(!function_exists('__seems_true')){
    function __seems_true($data = ''){
        return in_array((string) $data, ['on', 'true', '1'], true);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_wp_http_requests_response')){
    function __seems_wp_http_requests_response($data = null){
        return (__array_keys_exist(['body', 'cookies', 'filename', 'headers', 'http_response', 'response'], $data) and ($data['http_response'] instanceof WP_HTTP_Requests_Response));
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

if(!function_exists('__signon_without_password')){
    function __signon_without_password($username_or_email = '', $remember = false){
        if(is_user_logged_in()){
            return wp_get_current_user();
        } else {
            $idx = __one('authenticate', function($user, $username_or_email){
                if(is_null($user)){
                    if(is_email($username_or_email)){
                        $user = get_user_by('email', $username_or_email);
                    }
                    if(is_null($user)){
                        $user = get_user_by('login', $username_or_email);
                        if(is_null($user)){
                            return __error(__('The requested user does not exist.'));
                        }
                    }
                }
                return $user;
            }, 10, 2);
            $user = wp_signon([
                'remember' => $remember,
                'user_login' => $username_or_email,
                'user_password' => '',
            ]);
            __off('authenticate', $idx);
            return $user;
        }
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

if(!function_exists('__support_sessions')){
    function __support_sessions($defaults = false){
        __one('init', function() use($defaults){
			if(!session_id()){
        		session_start();
        	}
			if($defaults){
                if(!array_key_exists('__current_user_id', $_SESSION)){
                    $_SESSION['__current_user_id'] = get_current_user_id();
                }

                if(!array_key_exists('__utm', $_SESSION)){
                    $_SESSION['__utm'] = [];
	                foreach($_GET as $key => $value){
	                    if(substr($key, 0, 4) == 'utm_'){
	                        $_SESSION['__utm'][$key] = $value;
	                    }
	                }
                }
			}
		}, 9);
		__one('wp_login', function($user_login, $user) use($defaults){
			if($defaults){
				$_SESSION['__current_user_id'] = $user->ID;
			}
		}, 9, 2);
		__one('wp_logout', function(){
			if(session_id()){
        		session_destroy();
        	}
		}, 9);
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
