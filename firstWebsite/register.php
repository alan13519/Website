<?php
require_once 'core/init.php';

//var_dump(Token::check(Input::get('token')));

if(Input::exists()){
	//if(Token::check(Input::get('token'))){
		// echo Input::get('user');
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'user' => array(
				'required' => true,
				'min' => 1,
				'max' => 30,
				'unique' => 'pid'
			),
			'pass' => array(
				'required' => true,
				'min' => 4,
				'max' => 32
			),
			'repass' => array(
				'required' => true,
				'matches' => 'pass'
			),
			'fname' => array(
				'required' => true,
				'max' => 50
			),
			'lname' => array(
				'required' => true,
				'max' => 100
			)
		));
		if($validation->passed()){
			$user = new User();
			try {
				$user->create(array(
					'pid' => Input::get('user'),
					'passwd' => md5(Input::get('pass')),
					'fname' => Input::get('fname'),
					'lname' => Input::get('lname')	
				));
				
				Session::flash('home', 'You have been registered');
				//header('Location: index.php');
				Redirect::to('index.php');
				
			} catch(Exception $err){
				die($err->getMessage()); #Kills script
			}
		} else{
			foreach($validation->errors() as $error){
				echo $error, '<br>';
			}
		}
	//} //else{
		//echo 'CSRF ATTACK<br/>';
		//var_dump(Token::check(Input::get('token'))); #Boolean false
	//}
}
//var_dump($_POST);
//var_dump($_SESSION);
?>

<h1>Registration</h1><br>
<form method = 'post' action = ''>
	<div class = "field">
		<input type="text" name="fname" id = "fname" placeholder="First Name" value = "<?php echo escape(Input::get('fname')); ?>" autocomplete = "off">
		<input type="text" name="lname" id = "lname" placeholder="Last Name" value = "<?php echo escape(Input::get('lname')); ?>" autocomplete = "off"><br><br>
		<input type="text" name="user" id = "pid" placeholder="Username" value = "<?php echo escape(Input::get('user')); ?>" autocomplete = "off"><br> 
	</div>
	<div class = "field">
		<input type="password" name="pass" id = "pass" placeholder="Password" autocomplete = "off"><br>
		<input type="password" name="repass" id = "repass" placeholder="Confirm Password" value = "" autocomplete = "off"><br><br>
	</div>
	<input type="hidden" name="token" value ="<?php echo Token::generate(); ?>">
	<input type="submit" name="register" class="register register-submit" value="Register">
</form>

<li><a href = 'index.php'>Go Back</a></li>