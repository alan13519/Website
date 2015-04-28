<?php 
require_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');
} //Kick person out if they're not logged in
$currclub = Input::get('club'); //Store current club info
$club = new Club($currclub);
echo '<h1>Club Page</h1>';

if($user->roleInClub($currclub)){
	echo "<font color ='red'>You are the Club " . $user->roleInClub($currclub) . '</font><br><br>';
	if($user->roleInClub($currclub) == 'Admin'){
		echo "
				<form method = 'post' action = 'postnewevent.php?club=$currclub'>
					<input type = 'submit' name='newevent' value='Create New Event'>
				</form>
		";
	echo "Upcoming events for the Club: <br><br>";
	$upcoming = $db->get('event', array('sponsored_by','=',$currclub));
	if($upcoming->count()){
		foreach($upcoming->results() as $upcomingEvents){
			$upEvents = new Event($upcomingEvents->eid);
			$amount = $upEvents->amountOfSignUps();
			echo "<a href = 'event.php?event=$upcomingEvents->eid'> 
					$upcomingEvents->ename</a> Users who Signed up: $amount <br>";
		}
	}else{
		echo "The Club is currently hosting no events";
	}
	}
	echo "<br><br>";
}

$advisors = $db->get('advisor_of', array('clubid','=',$currclub));
if(!$advisors->count()){
	echo 'Club Currently has no Advisor<br><br>';
}else{
	echo 'Club Advisors: <br><br>';
	foreach($advisors->results() as $advisor){
		$adv = new User($advisor->pid);
		echo "<a href = 'profile.php?user=$advisor->pid'</a>";
		echo $adv->data()->fname . '  ' . $adv->data()->lname . '</a><br>';
	}
}


echo '<br><br>';

$members = $db->get('member_of', array('clubid','=',$currclub));
if(!$members->count()){
	echo 'Club Currently has no members<br><br>';
}else{
	
	echo 'Club Members: <br><br>';
	foreach($members->results() as $member){
		$mem = new User($member->pid);
		echo "<a href = 'profile.php?user=$member->pid'</a>";
		echo $mem->data()->fname . ' ' . $mem->data()->lname . '</a>';
		if($mem->roleInClub($currclub)){
			echo ' -- Club ' . $mem->roleInClub($currclub) . '<br>';
		}else{
			echo '<br>';
		}

	}
}

echo '<br><br>';

echo "	
		<form method = 'post' action = 'club.php?club=$currclub'>
			<textarea maxlength='255' name='message'></textarea><br>
			<input type='submit' name='submit' value='Leave a Comment'>
			<input type='checkbox' name='private' value='private' unchecked> Private<br>
		</form>
		";

echo "<h5> Comments: </h5><br>";
if($user->isPartofClub($currclub)){
	$club->comments(1);
}else{
	$club->comments(0);
}
#Club Comments

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
			), 'club_comment', 'clubid', $currclub);
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

echo 	"<br><li><a href = 'index.php'>Go Back</a></li>";
?>

