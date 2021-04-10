<?php

if(!class_exists('__require')){
    final class __require {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public $dir = '', $error = null, $success = false, $url = '';

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($url = '', $dir = ''){
            $download_dir = __download_dir();
            if(is_wp_error($download_dir)){
                $this->error = $download_dir;
                return;
            }
            $uuid4 = __md5_to_uuid4(md5($url));
            $to = $download_dir . '/' . $uuid4;
            if($dir){
                $dir = $to . '/' . ltrim(untrailingslashit($dir), '/');
            } else {
                $dir = $to;
            }
            $filesystem = __filesystem();
            if(is_wp_error($filesystem)){
                $this->error = $filesystem;
                return;
            }
            if(!$filesystem->dirlist($dir, false)){
                $file = __download($url);
                if(is_wp_error($file)){
                    $this->error = $file;
                    return;
                }
                $result = unzip_file($file, $to);
                if(is_wp_error($result)){
                    $this->error = $result;
                    $filesystem->rmdir($to, true);
                    @unlink($file);
                    return;
                }
                @unlink($file);
                if(!$filesystem->dirlist($dir, false)){
                    $this->error = __error(__('Destination directory for file streaming does not exist or is not writable.'));
                    return;
                }
            }
            $this->dir = $dir;
            $this->success = true;
            $this->url = __download_url() . '/' . $uuid4;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
