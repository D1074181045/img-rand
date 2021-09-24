<?php
	if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]))
		$is_https = $_SERVER["HTTP_X_FORWARDED_PROTO"] === 'https';
	else if (isset($_SERVER['HTTPS']))
		$is_https = $_SERVER["HTTPS"] === 'on';
	else
		$is_https = false;

	if (!$is_https) {
		$location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('Location: ' . $location);
		exit;
	}
?>