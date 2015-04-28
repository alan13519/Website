<?php
require_once 'core/init.php';
#Only people who are members can view other people's websites
$currUser = new User();

if(!$currUser->isLoggedIn()){
	Redirect::to('index.php');
} #Check if the person is logged in, if not then kick them out
if(!$username = Input::get('user')){
	Redirect::to('index.php');
} else{
	if($currUser->data()->pid === $username){
		echo '<h1>Your Profile Page</h1>';
	}else{
		echo '<h1>Profile Page</h1>';
	}
	$user = new User($username);
	echo '<h3>' . ucfirst($user->data()->fname) . ' ' . ucfirst($user->data()->lname) . '</h3>'; #Capital first letter of string
	$studentstatus = DB::getInstance()->get('student', array('pid', '=', $user->data()->pid));
	if($studentstatus->count()){
		foreach($studentstatus->results() as $status){
			echo 'Current status: ' . $status->class;
		}
	}
	if(!$user->exists()){
		//echo "Does not exist";
		Redirect::to(404);
	}

}

?>
<nav id = "menu">
<ul>
	<li><a href = 'index.php'>Home</a></li>
	<li><a href = 'viewclubs.php'>Clubs</a></li>
	<li><a href = 'viewevents.php'>View Events</a></li>
	<li><a href = 'viewfriends.php'>Friends</a></li>
	<li><a href = 'logout.php'>Log Out</a></li>
</ul>
</nav>


<?php
$clubmemberof = DB::getInstance()->get('member_of', array('pid', '=', $user->data()->pid));
if(!$clubmemberof->count()){
	echo "<h3>Currently not a member of any clubs</h3>";
}else{
	$clubmemberof = DB::getInstance()->get('member_of', array('pid', '=', $user->data()->pid));
	echo "<h3>Clubs currently in:</h3>";
	foreach($clubmemberof->results() as $clubs){
		$clubname = DB::getInstance()->get('club', array('clubid', '=', $clubs->clubid ));
		foreach($clubname->results() as $club){
			echo "<a href = 'club.php?club=$club->clubid'> 
			$club->cname</a><br>";
		} #need better implementation
		//echo $clubs->clubid . '<br>';
	}
}

$advisorstatus = DB::getInstance()->get('advisor', array('pid', '=', $user->data()->pid));
if($advisorstatus->count()){
	$advisor = $advisorstatus->position(1); //Get the telephone number
	echo 'Telephone number: ' . $advisor->phone;
	echo '<br><br>';
	$clubadvised = DB::getInstance()->get('advisor_of', array('pid', '=', $advisor->pid));
	if($clubadvised->count()){
		$cbid = $clubadvised->position(1); //Get club id
		$clubname = DB::getInstance()->get('club', array('clubid', '=', $cbid->clubid ));
		//$club = $clubname->position(1); //Get club name
		echo 'Current Advisor of: ';
		foreach($clubname->results() as $club){
			echo "<a href = 'club.php?club=$club->clubid'> 
			$club->cname</a><br>";
		} 
	}
}




?>



	