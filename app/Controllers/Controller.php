<?php 
	namespace App\Controllers;
	
	use App\Middleware\HttpsProtocolMiddleware;

	class Controller {
		public function __call($method, $args){
			echo 'has not this function'.$method;
		}
		
		public function __construct(){
			HttpsProtocolMiddleware::run();
		}
		
		public function render($path, array $args = []){
			extract($args);
			require($GLOBALS['baseDir'] . "/views/$path.php");
		}

		public function get_url_params() {
			$query = $_SERVER['QUERY_STRING'];
            $array = array();

			if (!$query) return $array;

			$params = explode('&', $query);
			
			foreach ($params as $param) {
				if (strpos($param, '=') === false) $param .= '=';
				
				list($key, $value) = explode('=', $param, 2);
				$array[$key][] = $value;
			}
			
			return $array;
		}

		public function get_curl($url) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_REFERER, $url); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			
			$content = curl_exec($ch);
			$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			
			curl_close($ch);

			return array(
				'content' => $content, 
				'contentType' => $contentType
			);
		}

		public function show_image($array) {
			if (!$this->is_image_type($array['contentType'])){
				return false;
			}
			
			header('Content-Type:' . $array['contentType']);
			echo $array['content'];

            return true;
		}
		
		public function is_image_type($contentType) {
			return strpos(strtolower($contentType), 'image') !== false;
		}
		
		public function index(){
			if (isset($this->get_url_params()['img']))
				$this->render('index', ['img_url_items' => $this->get_url_params()['img']]);
			else
				$this->render('index');
		}
		
		public function show(){
			$nt_img_url = 'http://' . $_SERVER['HTTP_HOST'] . '/asset/img/nt_img_url.png';
			
			if (isset($this->get_url_params()['img'])) {
				$img_array = $this->get_url_params()['img'];
				$url = $img_array[mt_rand(0, count($img_array) - 1)];
				
				if (!$this->show_image($this->get_curl($url))){
					$this->show_image($this->get_curl($nt_img_url));
				}
			} else {
				$this->show_image($this->get_curl($nt_img_url));
			}
		}
	}