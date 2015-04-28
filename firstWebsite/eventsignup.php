<?php 
require_once 'core/init.php';

$eventid = Input::get('event');
$event = new Event($eventid);
$user = new User();
if(!$user->isLoggedIn()){?>
	<script> window.alert("Log in first"); </script>
<?php
	Redirect::to('index.php', '.05'); 
}
if(Input::exists()){
	if($event->privacy()){ #If public, run this
		$user->signup($eventid);
	} else{ #Else run this
		if($user->isPartofClub($event->sponsor())){ #If user is in club
			$user->signup($eventid);
		}else{
			echo "Sign up Failed";
		}
	}
}
//Redirect::to('viewevents.php', '2');

//echo "<a href = 'event.php?event=$eventid'> 
//			$event->data()->ename</a><br>";

?>

<script> javascript:window.location.reload(history.go(-1)); </script>