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

if(!function_exists('__error')){
    function __error($message = '', $data = ''){
        if(!$message){
            $message = __('Something went wrong.');
        }
        return new WP_Error('__error', $message, $data);
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

if(!function_exists('__read_file_chunk')){
    function __read_file_chunk($handle = null, $chunk_size = 0){
        $giant_chunk = '';
    	if(is_resource($handle) and is_int($chunk_size)){
    		$byte_count = 0;
    		while(!feof($handle)){
                $length = apply_filters('__file_chunk_lenght', (8 * KB_IN_BYTES));
    			$chunk = fread($handle, $length);
    			$byte_count += strlen($chunk);
    			$giant_chunk .= $chunk;
    			if($byte_count >= $chunk_size){
    				return $giant_chunk;
    			}
    		}
    	}
        return $giant_chunk;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__remove_whitespaces')){
    function __remove_whitespaces($str = ''){
        return trim(preg_replace('/[\r\n\t\s]+/', ' ', $str));
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

if(!function_exists('__seems_false')){
    function __seems_false($data = ''){
        return in_array((string) $data, ['off', 'false', '0', ''], true);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__seems_true')){
    function __seems_true($data = ''){
        return in_array((string) $data, ['on', 'true', '1'], true);
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
