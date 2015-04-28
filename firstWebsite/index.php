<?php
require_once 'core/init.php';
/*
echo Config::get('mysql/dbhost');

$user = DB::getInstance()->get('person', array('pid', '!=', '2'));

if(!$user->count()){
	echo 'No User';
} else{
		echo $user->fname, ' ' , $user->lname , '<br>';
	}	
}
$user = DB::getInstance()->insert('person', array(
	'pid' => '21',
	'passwd' => md5('1234'),
	'fname' => 'Alan',
	'lname' => 'Ni'
));
*/
if(Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}

?>
<p><h1>Welcome To Your Club Hub <a href = 'profile.php?user=<?php 
echo escape($user->data()->pid); ?>'><?php 
echo escape($user->data()->fname); ?></a> !</h1></p>

<nav id = "menu">
	<ul>
		<li><a href = 'index.php'>Home</a></li>
		<li><a href = 'viewclubs.php'>Clubs</a></li>
		<li><a href = 'viewevents.php'>View Events</a></li>
		<li><a href = 'viewfriends.php'>Friends</a></li>
		<li><a href = 'logout.php'>Log Out</a></li>
	</ul>
</nav>

