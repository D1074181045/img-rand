<?php include("./Controller/controller.php"); ?>
<?php
	$routes = [
		'' => ['Controller', 'Index'],
		'show' => ['Controller', 'Show'],
	];

	if(isset($_SERVER['REDIRECT_URL'])){
        $path = ltrim($_SERVER['REDIRECT_URL'], '/');
        $path_arr = explode('/', $path);
	}
	
	if(isset($path_arr[0])){
	    $key = $path_arr[0];
	    unset($path_arr[0]);
	} else {
	    $key = '';
	}
	
	if(isset($path_arr[1])){
	    $parameters = array_values($path_arr);
	}
	
	if(isset($routes[$key])){
		$route = $routes[$key];
		if($route instanceof Closure){
			$route();
		} else {
			$controller = new $route[0];
			$action = $route[1];
			if(isset($parameters)){
				$controller->$action($parameters);
			} else {
				$controller->$action();
			}
		}
	} else {
	    echo '404';
	}
?>