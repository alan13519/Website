<?php 
require_once 'core/init.php';


if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'user' => array('required' => true),
		'pass' => array('required' => true)
	));
	
	if($validation->passed()){
		$user = new User();
		$login = $user->login(Input::get('user'), Input::get('pass'));
		
		if($login){
			echo 'Login Successful';
			Redirect::to('index.php');
		} else{
			echo '<p>Username and/or password is incorrect</p>';
		}
		
	} else{
		echo "Username and password required";
	}
}
?>


<h1>Log-in</h1><br>
<form method = 'post' action = 'login.php'>
	<input type="text" name="user" placeholder="Username">
	<input type="password" name="pass" placeholder="Password">
	<input type="submit" name="login" class="login login-submit" value="Sign In">
</form>
<div id = "register_new_user">
	<a href="register.php">Register</a>
</div>

<aside id = "public">
	<!-- Public events -->
	<h1>Public</h1>
	<li><a href = 'viewevents.php'>View Events</a></li>
	<li><a href = 'viewclubs.php'>View Clubs</a></li>
</aside>