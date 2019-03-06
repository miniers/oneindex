<?php 

class ImagesController{
	function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
	function index(){
		if($_FILES["file"]){
		    $filename = $_FILES["file"]['name'];
			$content = file_get_contents( $_FILES["file"]['tmp_name']);
			$remotepath =  'files/'.date('Y/m/d/').$this->generateRandomString(10).'/';
			$remotefile = $remotepath.$filename;
			$result = onedrive::upload(config('onedrive_root').$remotefile, $content);
			if($result){
				$root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).config('root_path');
				$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$url = $_SERVER['HTTP_HOST'].$root.'/'.$remotepath.rawurldecode($filename);
				$url = $http_type.str_replace('//','/', $url);
				view::json(array('url' => $url, 'error' => false));
			}else{
				view::json(array('url' => '' , 'error' => true));
			}
		}
	}

}
