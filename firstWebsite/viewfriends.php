<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}
echo "<h1>Friends:</h1>";
?>
		
<nav id = "menu">
	<ul>
		<li><a href = 'index.php'>Home</a></li>
		<li><a href = 'clubs.php'>Clubs</a></li>
		<li><a href = 'viewevents.php'>View Events</a></li>
		<li><a href = 'viewfriends.php'>Friends</a></li>
		<li><a href = 'logout.php'>Log Out</a></li>
	</ul>
</nav>

<br><br><br>

<h3>Follow User</h3>
<form method = 'post' action = 'viewfriends.php'>
	<input type='text' name='search' placeholder='User Id'>
	<input type='submit' name='submit' value='Follow User'>
</form>