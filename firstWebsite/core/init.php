<?php
echo "<link rel='stylesheet' type='text/css' href='CSS/theme.css?version=1'/> ";
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'dbhost' => "localhost",
		'dbuser' => 'root',
		'dbpass' => '',
		'dbname' => 'clubhub'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

spl_autoload_register(function($class){
		require_once 'class/' . $class . '.php';
}); #Auto loads classes

require_once 'functions/sanitize.php';

if(mysqli_connect_errno()){ #Test if there is a connection error
	die("Database connection failed: " . mysqli_connect_error() .
	" (" . mysqli_connect_errno() . ")"
	);
}
?>