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

if(!function_exists('__add_image_size')){
    function __add_image_size($name = '', $width = 0, $height = 0, $crop = false){
        if(!isset($GLOBALS['__image_sizes'])){
            $GLOBALS['__image_sizes'] = [];
        }
		$size = sanitize_title($name);
        if(!array_key_exists($size, $GLOBALS['__image_sizes'])){
            $GLOBALS['__image_sizes'][$size] = $name;
			add_image_size($size, $width, $height, $crop);
        }
        __one('image_size_names_choose', function($sizes){
            if(!is_array($GLOBALS['__image_sizes'])){
                return $sizes;
            }
			foreach($GLOBALS['__image_sizes'] as $size => $name){
				$sizes[$size] = $name;
			}
            return $sizes;
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__admin_enqueue_ace')){
    function __admin_enqueue_ace(){
        __one('admin_enqueue_scripts', function(){
            wp_enqueue_script('ace', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js', [], '1.4.12', true);
            wp_enqueue_script('ace-language-tools', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.min.js', ['ace'], '1.4.12', true);
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__admin_enqueue_functions')){
    function __admin_enqueue_functions(){
        __one('admin_enqueue_scripts', function(){
            wp_enqueue_script('__functions', plugin_dir_url(__FILE__) . 'functions.js', ['jquery'], filemtime(plugin_dir_path(__FILE__) . 'functions.js'), true);
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

if(!function_exists('__attachment_url_to_postid')){
    function __attachment_url_to_postid($url = ''){
        // original
        $post_id = __guid_to_postid($url);
        if($post_id){
            return $post_id;
        }
        // resized
        preg_match('/^(.+)(-\d+x\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
        if($matches){
            $url = $matches[1];
            if(isset($matches[3])){
                $url .= $matches[3];
            }
            $post_id = __guid_to_postid($url);
            if($post_id){
                return $post_id;
            }
        }
        // scaled
        preg_match('/^(.+)(-scaled)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
        if($matches){
            $url = $matches[1];
            if(isset($matches[3])){
                $url .= $matches[3];
            }
            $post_id = __guid_to_postid($url);
            if($post_id){
                return $post_id;
            }
        }
        // edited
        preg_match('/^(.+)(-e\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
        if($matches){
            $url = $matches[1];
            if(isset($matches[3])){
                $url .= $matches[3];
            }
            $post_id = __guid_to_postid($url);
            if($post_id){
                return $post_id;
            }
        }
        return 0;
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

if(!function_exists('__current_time')){
    function __current_time($type = 'U', $offset_or_tz = ''){ // If $offset_or_tz is an empty string, the output is adjusted with the GMT offset in the WordPress option.
        if('timestamp' === $type){
            $type = 'U';
        }
        if('mysql' === $type){
            $type = 'Y-m-d H:i:s';
        }
        $timezone = $offset_or_tz ? __timezone($offset_or_tz) : wp_timezone();
        $datetime = new DateTime('now', $timezone);
        return $datetime->format($type);
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

if(!function_exists('__date_convert')){
    function __date_convert($string = '', $fromtz = '', $totz = '', $format = 'Y-m-d H:i:s'){
        $datetime = date_create($string, __timezone($fromtz));
        if($datetime === false){
            return gmdate($format, 0);
        }
        return $datetime->setTimezone(__timezone($totz))->format($format);
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
            $args['filename'] = $download_dir . '/' . uniqid() . '-' . preg_replace('/\?.*/', '', basename($url));
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

if(!function_exists('__download_dir')){
    function __download_dir(){
        $upload_dir = wp_get_upload_dir();
        $download_dir = $upload_dir['basedir'] . '/__downloads';
        if(!wp_mkdir_p($download_dir)){
            return __error(__('Could not create directory.'));
        }
        if(!wp_is_writable($download_dir)){
            return __error(__('Destination directory for file streaming does not exist or is not writable.'));
        }
        return $download_dir;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__download_url')){
    function __download_url(){
        $upload_dir = wp_get_upload_dir();
        return $upload_dir['baseurl'] . '/__downloads';
    }
}

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
        __one('wp_enqueue_scripts', function() use($ver, $bundle){
            switch($ver){
                case 4:
                    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css', [], '4.6.0');
                    if($bundle){
                        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', ['jquery'], '4.6.0', true);
                    } else {
                        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js', ['jquery'], '4.6.0', true);
                    }
                    break;
                case 5:
                    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css', [], '5.0.0-beta3');
                    if($bundle){
                        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js', [], '5.0.0-beta3', true);
                    } else {
                        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js', [], '5.0.0-beta3', true);
                    }
                    break;
            }
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_bs_custom_file_input')){
    function __enqueue_bs_custom_file_input(){
        __one('wp_enqueue_scripts', function(){
            wp_add_inline_script('bs-custom-file-input', 'jQuery(function(){ bsCustomFileInput.init(); });');
            wp_enqueue_script('bs-custom-file-input', 'https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js', ['jquery'], '1.3.4', true);
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_dashicons')){
    function __enqueue_dashicons(){
        __one('wp_enqueue_scripts', function(){
            wp_enqueue_style('dashicons');
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_fontawesome')){
    function __enqueue_fontawesome($ver = 5, $pro = false){
        __one('wp_enqueue_scripts', function() use($ver, $pro){
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
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_fontawesome_kit')){
    function __enqueue_fontawesome_kit($kit = ''){
        __one('wp_enqueue_scripts', function() use($kit){
            $url = wp_http_validate_url($kit);
            if($url){
                wp_enqueue_script('fontawesome', $url);
            } else {
                wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/' . rtrim($kit, '.js') . '.js');
            }
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_functions')){
    function __enqueue_functions($context = 'both'){
        __one('wp_enqueue_scripts', function(){
            wp_enqueue_script('__functions', plugin_dir_url(__FILE__) . 'functions.js', ['jquery'], filemtime(plugin_dir_path(__FILE__) . 'functions.js'), true);
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_jquery')){
    function __enqueue_jquery(){
        __one('wp_enqueue_scripts', function(){
            wp_enqueue_script('jquery');
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_popper')){
    function __enqueue_popper($ver = 1){
        __one('wp_enqueue_scripts', function() use($ver){
            switch($ver){
                case 1:
                    wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', [], '1.16.1', true);
                    break;
                case 2:
                    wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js', [], '2.9.1', true);
                    break;
            }
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__enqueue_stylesheet')){
    function __enqueue_stylesheet(){
        __one('wp_enqueue_scripts', function(){
            wp_enqueue_style(get_stylesheet(), get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
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

if(!function_exists('__fix_audio_video_type')){
    function __fix_audio_video_type(){
        __one('wp_check_filetype_and_ext', function($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime){
            if($wp_check_filetype_and_ext['ext'] and $wp_check_filetype_and_ext['type']){
                return $wp_check_filetype_and_ext;
            }
            if(strpos($real_mime, 'audio/') === 0 or strpos($real_mime, 'video/') === 0){
                $filetype = wp_check_filetype($filename);
                if(in_array(substr($filetype['type'], 0, strcspn($filetype['type'], '/')), ['audio', 'video'])){
                    $wp_check_filetype_and_ext['ext'] = $filetype['ext'];
                    $wp_check_filetype_and_ext['type'] = $filetype['type'];
                }
            }
            return $wp_check_filetype_and_ext;
        }, 10, 5);
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

if(!function_exists('__guid_to_postid')){
    function __guid_to_postid($guid = ''){
        global $wpdb;
		if($guid){
			$str = "SELECT ID FROM $wpdb->posts WHERE guid = %s";
			$sql = $wpdb->prepare($str, $guid);
			$post_id = $wpdb->get_var($sql);
			if($post_id){
				return (int) $post_id;
			}
		}
		return 0;
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

if(!function_exists('__in_uploads')){
    function __in_uploads($filename = ''){
        $upload_dir = wp_get_upload_dir();
        return (strpos($filename, $upload_dir['basedir']) === 0);
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

if(!function_exists('__is_extension_allowed')){
    function __is_extension_allowed($extension = ''){
        foreach(wp_get_mime_types() as $exts => $mime){
            if(preg_match('!^(' . $exts . ')$!i', $extension)){
                return true;
            }
        }
        return false;
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

if(!function_exists('__larger_image_sizes')){
    function __larger_image_sizes(){
        __add_image_size('HD', 1280, 1280);
        __add_image_size('Full HD', 1920, 1920);
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

if(!function_exists('__maybe_generate_attachment_metadata')){
    function __maybe_generate_attachment_metadata($attachment = null){
        $attachment = get_post($attachment);
		if(!$attachment or $attachment->post_type != 'attachment'){
			return false;
		}
		wp_raise_memory_limit('admin');
		wp_maybe_generate_attachment_metadata($attachment);
		return wp_get_attachment_metadata($attachment->ID);
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

if(!function_exists('__offset_or_tz')){
    function __offset_or_tz($offset_or_tz = ''){ // Default GMT offset or timezone string. Must be either a valid offset (-12 to 14) or a valid timezone string.
        if(is_numeric($offset_or_tz)){
            return [
                'gmt_offset' => $offset_or_tz,
                'timezone_string' => '',
            ];
        } else {
            if(preg_match('/^UTC[+-]/', $offset_or_tz)){ // Map UTC+- timezones to gmt_offsets and set timezone_string to empty.
                return [
                    'gmt_offset' => intval(preg_replace('/UTC\+?/', '', $offset_or_tz)),
                    'timezone_string' => '',
                ];
            } else {
                if(in_array($offset_or_tz, timezone_identifiers_list())){
                    return [
                        'gmt_offset' => 0,
                        'timezone_string' => $offset_or_tz,
                    ];
                } else {
                    return [
                        'gmt_offset' => 0,
                        'timezone_string' => 'UTC',
                    ];
                }
            }
        }
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

if(!function_exists('__seems_mysql_date')){
	function __seems_mysql_date($pattern = ''){
        return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $pattern);
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
    function __support_sessions($defaults = true){
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

if(!function_exists('__timezone')){
    function __timezone($offset_or_tz = ''){
        return new DateTimeZone(__timezone_string($offset_or_tz));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__timezone_string')){
    function __timezone_string($offset_or_tz = ''){
        $offset_or_tz = __offset_or_tz($offset_or_tz);
        $timezone_string = $offset_or_tz['timezone_string'];
        if($timezone_string){
            return $timezone_string;
        }
        $offset = floatval($offset_or_tz['gmt_offset']);
        $hours = intval($offset);
        $minutes = ($offset - $hours);
        $sign = ($offset < 0) ? '-' : '+';
        $abs_hour = abs($hours);
        $abs_mins = abs($minutes * 60);
        $tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);
        return $tz_offset;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__upload')){
    function __upload($file = '', $parent = 0){
        $filetype_and_ext = wp_check_filetype_and_ext($file, $file);
        if(!$filetype_and_ext['type']){
            return __error(__('Sorry, this file type is not permitted for security reasons.'));
        }
        $upload_dir = wp_get_upload_dir();
        $post_id = wp_insert_attachment([
            'guid' => str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $file),
            'post_mime_type' => $filetype_and_ext['type'],
            'post_status' => 'inherit',
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($file)),
        ], $file, $parent, true);
        if(is_wp_error($post_id)){
            return $post_id;
        }
        return $post_id;
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
