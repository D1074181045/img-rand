<?php 
	use App\Controllers\Controller;
	
	function url($regex, $method) {
        $path = isset($_SERVER['REDIRECT_URL'])
				? ltrim($_SERVER['REDIRECT_URL'], '/') : '';
		
		if ($match = preg_match("/$regex/", $path, $out_array)){
			if($method instanceof Closure) {
				$method();
			} else {
				$controller = new $method[0];
				$action = $method[1];
				if(isset($out_array[1])){
					$controller->$action($out_array[1]);
				} else {
					$controller->$action();
				}
			}
			exit;
		}
	}

	url('^$', [Controller::class, 'index']);
	url('^show$', [Controller::class, 'show']);
	url('(.+)', function () {
	    header("Location: /");
	});