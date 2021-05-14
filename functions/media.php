<?php

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

if(!function_exists('__in_uploads')){
    function __in_uploads($filename = ''){
        $upload_dir = wp_get_upload_dir();
        return (strpos($filename, $upload_dir['basedir']) === 0);
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

if(!function_exists('__larger_image_sizes')){
    function __larger_image_sizes(){
        __add_image_size('HD', 1280, 1280);
        __add_image_size('Full HD', 1920, 1920);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__maybe_generate_attachment_metadata')){
    function __maybe_generate_attachment_metadata($attachment = null){
        $attachment = get_post($attachment);
		if(!$attachment or $attachment->post_type != 'attachment'){
			return false;
		}
		wp_raise_memory_limit('image');
		wp_maybe_generate_attachment_metadata($attachment);
		return true;
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

if(!function_exists('__sideload')){
    function __sideload($file = '', $filename = '', $parent = 0, $dir = ''){
        $filesystem = __filesystem();
        if(is_wp_error($filesystem)){
            return $filesystem;
        }
        if(!$filesystem->exists($file)){
            return __error(__('File does not exist! Please double check the name and try again.'));
        }
        if($dir){
            $dir = untrailingslashit($dir);
            if(!$filesystem->exists($dir)){
                return __error(__('File does not exist! Please double check the name and try again.'));
            }
            if(!__in_uploads($dir)){
                return __error(sprintf(__('Unable to locate needed folder (%s).'), 'uploads'));
            }
        } else {
            $upload_dir = wp_upload_dir();
            $dir = $upload_dir['path'];
        }
        $filename = __filename($filename);
        $filename = wp_unique_filename($dir, $filename);
        $destination = $dir . '/' . $filename;
        if(!__copy($file, $destination)){
            return __error(__('Filesystem error.'));
        }
        return __upload($destination, $parent);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('__upload')){
    function __upload($file = '', $parent = 0){
        $filesystem = __filesystem();
        if(is_wp_error($filesystem)){
            return $filesystem;
        }
        if(!$filesystem->exists($file)){
            return __error(__('File does not exist! Please double check the name and try again.'));
        }
        if(!__in_uploads($file)){
            return __error(sprintf(__('Unable to locate needed folder (%s).'), 'uploads'));
        }
        $filename = __filename($file);
        $filetype_and_ext = wp_check_filetype_and_ext($file, $filename);
        if(!$filetype_and_ext['type']){
            return __error(__('Sorry, this file type is not permitted for security reasons.'));
        }
        $upload_dir = wp_get_upload_dir();
        $post_id = wp_insert_attachment([
            'guid' => str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $file),
            'post_mime_type' => $filetype_and_ext['type'],
            'post_status' => 'inherit',
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
        ], $file, $parent, true);
        if(is_wp_error($post_id)){
            return $post_id;
        }
        __maybe_generate_attachment_metadata($post_id);
        return $post_id;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
