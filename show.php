<?php include("http2https.php"); ?>
<?php
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
	
	function _file_get_contents($url) {
		$content = @file_get_contents(
					$url,
					false,
					stream_context_create([
						'http' => [
							'ignore_errors' => true,
							'header' => 'referer: ' . $url
						],
					]));
		
		if (isset($http_response_header))
			return array($content, $http_response_header);
		else
			return array($content, null);
	}
	
	function get_image($url) {
		list($content, $http_response_header) = _file_get_contents($url);
		
		$header = array();
		
		if ($content === false) {
			$url = 'img/nt_img_url.png';

			$content = _file_get_contents($url)[0];
			
			$header[0] = 'content-type: image/png';
		} else {
			$pattern = "/^content-type:.*image.*$/i";
			$header = array_values(preg_grep($pattern, $http_response_header));
			
			if (!count($header)) {
				$url = 'img/nt_img_url.png';
				
				$content = _file_get_contents($url)[0];
				
				$header[0] = 'content-type: image/png';
			}
		} 
		
		return array($content, $header);
	}
	
	function show_image($content, $header) {
		if (count($header)) {
			header($header[0]);
			echo $content;
		}
	}
	
	if (isset(get_url_params()['img'])) {
		$img_array = get_url_params()['img'];
		$url = $img_array[rand(0, count($img_array) - 1)];

		list($content, $header) = get_image($url);
		
		show_image($content, $header);
	}
?>