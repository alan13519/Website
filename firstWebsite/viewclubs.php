<?php 
require_once 'core/init.php';

//user = new User();
//if($user->isLoggedIn()){
	$clubsDB = DB::getInstance();
	$clubs = $clubsDB->get('club', array('clubid', '!=', '0'));
	echo "<h1>Current Clubs Available:</h1>";
//} else{
//	Redirect::to(404);
//}

if(isset($_POST['submit'])){
	$clubs = $clubsDB->get('club_topics', array('topic', '=', Input::get('search')));
	if(!$clubs->count()){
		echo "<br><br>No Results for Clubs<br><br>";
	}else{
		$index = 1;
		echo "<br>";
		echo "	<form method = 'post' action = 'viewclubs.php'>
				<input type='text' name='search' placeholder='Search'>
				<input type='submit' name='submit' value='Search'>
				</form>";
		foreach($clubs->results() as $club){
			$cb = new Club($club->clubid);
			$club = $cb->data();
			echo "<a href = 'club.php?club=$club->clubid'> 
			$club->cname</a><br>";
			$index++;
		}
	}
}else{
	if(!$clubs->count()){
	echo "There are currently no clubs";
	}else{
		$index = 1;
		echo "<br>";
		echo "	<form method = 'post' action = 'viewclubs.php'>
				<input type='text' name='search' placeholder='Search'>
				<input type='submit' name='submit' value='Search'>
				</form>";
		foreach($clubs->results() as $club){
			//echo $index . ": " . $club->cname. '<br><br>';
			echo "<a href = 'club.php?club=$club->clubid'> 
			$club->cname</a><br>";
			$index++;
		}
	}
}

	
echo "<br><li><a href = 'index.php'>Go Back</a></li>";
echo "<li><a href = 'viewclubs.php'>View All Clubs</a></li>";
?>