<?php include("http2https.php"); ?>
<?php
	function render($path, array $args = []){
		extract($args);
		require($path);
	}

	function get_url_params() {
		$query = $_SERVER['QUERY_STRING'];
		
		if (!$query) return;
			
		$params = explode('&', $query);
		$array = array();
		
		foreach ($params as $param) {
			if (strpos($param, '=') === false) $param += '=';
			
			list($key, $value) = explode('=', $param, 2);
			$array[$key][] = $value;
		}
		return $array;
	}

	function get_curl($url) {
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

	function show_image($array) {
		if (!is_image_type($array['contentType'])){
			return false;
		}
		
		header('Content-Type:' . $array['contentType']);
		echo $array['content'];
	}
	
	function is_image_type($contentType) {
		return strpos(strtolower($contentType), 'image') !== false;
	}

	class Controller {
		public function __call($method,$args){
			echo 'has not this function'.$method;
		}
		
		public function Index(){
			if (isset(get_url_params()['img']))
				render('../View/index.php', ['img_url_items' => get_url_params()['img']]);
			else
				render('../View/index.php');
		}
		
		public function Show(){
			if (isset(get_url_params()['img'])) {
				$img_array = get_url_params()['img'];
				$url = $img_array[random_int(0, count($img_array) - 1)];
				
				if (!show_image(get_curl($url))){
					$nt_img_url = 'http://' . $_SERVER['HTTP_HOST'] . '/img/nt_img_url.png';
					show_image(get_curl($nt_img_url));
				}
			}
		}
	}
?>