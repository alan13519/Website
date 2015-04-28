<?php
class Event{
	private $_db,
			$_data,
			$_privacy,
			$_sponsor;
			//$_comments;
	
	public function __construct($event = null){
		$this->_db = DB::getInstance();
		if(!$this->find($event)){
			//Redirect::to(404);
		}else{
			$this->find($event);
		}
	}
	
	public function create($fields){
		if($this->_db->insert('event', $fields)){
			throw new Exception('There was a problem with Event creation');
		}
	}
	
	public function find($eventName = null){
		if($eventName){
			$field = (is_numeric($eventName)) ? 'eid' : 'ename';
			$data = $this->_db->get('event', array($field,'=',$eventName));
			//echo $field;
			if($data->count()){
				$this->_data = $data->position();
				$this->_sponsor = $this->data()->sponsored_by;
				$this->_privacy = $this->data()->is_public_e; #0 is not 1 is public
				return true;
			}
		}
		return false;
	} #Finds the event and stores the row in _data
	
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	
	public function sponsor(){
		return $this->_sponsor;
	}
	
	public function privacy(){
		return $this->_privacy;
	} # 0 not public 1 yes
	
	public function data(){
		return $this->_data;
	}
	
	public function amountOfSignUps(){
		$data = $this->_db->get('sign_up', array('eid', '=', $this->_data->eid));
		return $data->count();
	} #Returns total amount of people who signed up
	
	public function comments($privacy = 0){
		$data = $this->_db->get('event_comment', array('eid','=',$this->data()->eid));
		//echo $this->data()->eid;
		if($data->count()){
			if($privacy == 1){
				$index = 1;
				foreach($data->results() as $commentid){
					$comment = new Comment($commentid->comment_id);
					echo $index . ': ';
					$index++;
					echo $comment->returnComment() . ' - ';
					$commenter = $comment->returnCommenter();
					$usr = new User($commenter);
					echo "<a href = 'profile.php?user=$commenter'</a>";
					echo $usr->data()->fname . ' ' . $usr->data()->lname . '</a>';
					echo '<br><br>';
				}
			} else{
				echo "(Some messages hidden)<br><br>";
				$index = 1;
				foreach($data->results() as $commentid){
					$comment = new Comment($commentid->comment_id);
					if(!$comment->privacy()){
						echo $index . ': ';
						$index++;
						echo $comment->returnComment() . ' - ';
						$commenter = $comment->returnCommenter();
						$usr = new User($commenter);
						echo "<a href = 'profile.php?user=$commenter'</a>";
						echo $usr->data()->fname . ' ' . $usr->data()->lname . '</a>';
						echo '<br><br>';
					}
				}
			}
		}
		echo "<br><br>";
	} #Privacy 0 = Private, 1 = Public
}

?>