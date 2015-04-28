<?php 
require_once 'core/init.php';

echo '<h1>Event</h1>';

$user = new User();
$eventid = Input::get('event');
$event = new Event($eventid);
echo '<h3>Event: ' . $event->data()->ename . '</h3>';

echo '<h3>Location: ' . $event->data()->location . '</h3>';

$club = new Club($event->sponsor());

$clubdata = $club->data();
echo '<h3>Sponsored by: ' . "<a href = 'club.php?club=$clubdata->clubid'> $clubdata->cname</a><br>".'</h3>';

echo 'People Signed up: <br><br>';
$signed = DB::getInstance()->get('sign_up', array('eid', '=', $eventid));
if(!$signed->count()){
	echo 'No body has signed up yet<br>';
} else{
	foreach($signed->results() as $member){
		$mem = new User($member->pid);
		echo "<a href = 'profile.php?user=$member->pid'</a>";
		echo $mem->data()->fname . ' ' . $mem->data()->lname . '</a><br>';
	}
}

echo '<br><br>';

echo "	<form method = 'post' action = 'eventsignup.php?event=$eventid'>
			<input type='submit' name='signup' value= 'Sign Up'>
		</form>
	";

echo "	
		<form method = 'post' action = 'event.php?event=$eventid'>
			<textarea maxlength='255' name='message'></textarea><br>
			<input type='submit' name='submit' value='Leave a Comment'>
			<input type='checkbox' name='private' value='private' unchecked> Private<br>
		</form>
		
		";

echo "<h5> Comments: </h5><br>";



if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'message' => array('max' => 255)
	));
	
	if($validation->passed()){
		$comment = new Comment();
		if(Input::get('private') == 'private'){
			$privacy = 0;
		}else{
			$privacy = 1;
		}
		try{
			$newID = $comment->getNewID();
			$comment->create(array(
				'comment_id' => $newID,
				'commenter' => $user->data()->pid,
				'ctext' => Input::get('message'),
				'is_public_c' => $privacy
			), 'event_comment', 'eid', $eventid);
		} catch(Exception $err){
			die($err->getMessage()); #Kills script
		}
	} else{
		foreach($validation->errors() as $error){
			echo $error, '<br>';
		}
	}
	header("refresh:0");
}
if($user->isLoggedIn() && $user->isPartofClub($clubdata->clubid)){
	$event->comments(1);
} else{
	$event->comments(0);
}

#Event Comments

echo 	"<br><li><a href = 'index.php'>Go Back</a></li><br>
		<li><a href = 'viewevents.php'>View All Events</a></li><br><br>";

		//"<a href = 'club.php?club=$club->clubid'> 
			//$club->cname</a><br>";
?>
