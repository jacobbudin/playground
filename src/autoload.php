<?php

require __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function($class) {
	if ($class[0] == '\\') {
		$class = substr($class, 1);
	}

	$path = sprintf('%s/%s.php', __DIR__, implode('/', explode('\\', $class)));

	if (is_file($path)) {
		require_once($path);
	}
});
