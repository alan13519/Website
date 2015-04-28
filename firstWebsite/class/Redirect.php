<?php
class Redirect{
	public static function to($location = null, $timer = 0){
		if($location){
			if(is_numeric($location)){
				switch($location){
					case 404:
						//header('HTTP/1.0 404 Not Found');
						//include 'includes/errors/404.php';
						//Redirect::to('includes/errors/404.php');
						exit();
					break;
				}
			}
			if($timer > 0){
				header('refresh:' . $timer . ';Url= ' . $location);
			}
			else{
				header('Location: ' . $location);
			}
			exit();
		}
	}
}

?>