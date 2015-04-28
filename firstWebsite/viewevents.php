<?php
require_once 'core/init.php';
$user = new User();
$db = DB::getInstance();
$logged = false;
if($user->isLoggedIn()){ #If the person is a member
	echo "<h1>My Events</h1><br>";
	$events = $db->get('event', array('is_public_e', '<', '2')); #rows of all events
	$logged = true;
}else{
	echo "<h1>Public Events</h1><br>";
	$events = $db->get('event', array('is_public_e', '=', '1')); #rows of all public events
}
echo "	<form method = 'post' action = 'viewevents.php'>
			<input type='text' name='search' placeholder='Search'>
			<input type='submit' name='submit' value='Search'>
		</form>";
	
if(isset($_POST['submit'])){ #Currently only allows one input at a time
	$searchedEvents = $db->get('event', array('description', 'like', '%' . Input::get('search') . '%'));
	//$searchedETitle = DB::getInstance()->get('event', array('ename', 'like', '%' . Input::get('search') . '%'));
	if(!$searchedEvents->count() /*&& !$searchedETitle->count()*/){
		echo "No Results for Events<br><br>";
	} else{
		$index = 1;
		foreach($searchedEvents->results() as $sEvents){
			if($logged){ #If user is logged in
				$sponsor = $sEvents->sponsored_by;
				if($user->hasPermissionToViewEvent($sEvents->eid)){
					echo $index .': ' . "<a href = 'event.php?event=$sEvents->eid'> 
					$sEvents->ename</a>" . ' located at ' . $sEvents->location . ' during ' . $sEvents->edatetime . '<br><br>';
					$index++;
				}
			}else{ #Else display only public
				echo $index .': ' . "<a href = 'event.php?event=$sEvents->eid'> 
				$sEvents->ename</a>" . ' located at ' . $sEvents->location . ' during ' . $sEvents->edatetime . '<br><br>';
				$index++;
			}
		}
	}
}else{
	if(!$events->count()){
		echo "There are no Events currently<br><br>";
	} else{
		$index = 1;
		if($logged){ #If user is logged in
			foreach($events->results() as $event){
				$sponsor = $event->sponsored_by;
				if($user->hasPermissionToViewEvent($event->eid)){
					echo $index .': ' . "<a href = 'event.php?event=$event->eid'> 
					$event->ename</a>" . ' located at ' . $event->location . ' during ' . $event->edatetime . '<br><br>';
					$index++;
				}
			}
		}else{ #Else display only public
			foreach($events->results() as $event){
				echo $index .': ' . "<a href = 'event.php?event=$event->eid'> 
				$event->ename</a>" . ' located at ' . $event->location . ' during ' . $event->edatetime . '<br><br>';
				$index++;
			}
		}
	}
}

echo 	"<li><a href = 'index.php'>Go Back</a></li>
		<li><a href = 'viewevents.php'>View All Events</a></li><br><br>";
?>

