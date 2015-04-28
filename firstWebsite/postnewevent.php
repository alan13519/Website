<?php
require_once 'core/init.php';


echo "<h1>Event Creation Form: </h1><br><br>";

$currclub = Input::get('club');

$user = new User();
//if($user->roleInClub($currclub) != 'Admin'){
//	Redirect::to('index.php');
//}

?>
<form method = 'post' action = 'postnewevent.php?club=<?php echo $currclub; ?>'>
	<input type='text' name='eventname' placeholder='Event Name' value="<?php echo escape(Input::get('eventname')); ?>" ><br>
	<input type='text' name='location' placeholder='Location' value="<?php echo escape(Input::get('location')); ?>"><br>
	<textarea maxlength='255' name='description' placeholder='Event Description' value="<?php echo escape(Input::get('description')); ?>"></textarea><br>
	<input type='text' name='date' placeholder='Date: YYYYMMDD' value="<?php echo escape(Input::get('date')); ?>">
	<input type='text' name='time' placeholder='Time: HHMMSS' value="<?php echo escape(Input::get('time')); ?>"><br>
	<input type='submit' name='submit' value='Create Event'>
	<input type='checkbox' name='private' value='private' unchecked> Private<br>
</form>
		
<?php
if(isset($_POST['submit'])){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'eventname' => array(
			'required' => true,
			'max' => 50
		),
		'location' => array(
			'required' => true
		),
		'description' => array(
			'required' => true
		),
		'date' => array(
			'required' => true,
			'numeric' => true,
			'intmin' => (int)date('Ymd')
		),
		'time' => array(
			'required' => true,
			'numeric' => true,
			'intmax' => 246060 //Date(H:i:s)
		)
	));
	if($validation->passed()){
		$event = new Event();
		echo Input::get('private');
		if(Input::get('private') == 'private'){
			$public = 1;
		}else{
			$public = 0;
		}
		try{
			$d = Input::get('date') . Input::get('time');
			$newDate = substr($d, 0, 4) .'-'. substr($d, 4,2) .'-'. substr($d,6,2) .' '. substr($d,8,2) .':'. substr($d,10,2) .':'. substr($d,12,2);
			$event->create(array(
				'eid' => 'DEFAULT',
				'ename' => Input::get('eventname'),
				'description' => Input::get('description'),
				'edatetime' => $newDate,
				'location' => Input::get('location'),
				'is_public_e' => $public,
				'sponsored_by' => $currclub
			));
			?>
			<script> window.alert("Successful Registration");</script>
			<?php
		} catch(Exception $err){
			die($err->getMessage());
		}
	}else{
		foreach($validation->errors() as $error){
			echo $error, '<br>';
		}
	}
}


echo 	"<br><li><a href = 'index.php'>Go Back</a></li>";
?>