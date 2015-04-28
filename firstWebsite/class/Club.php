<?php
class Club{
	private $_db,
			$_data,
			//$_advisors,
			//$_members,
			$_comments;
	
	
	public function __construct($club = null){
		$this->_db = DB::getInstance();
		if(!$this->find($club)){
			//Redirect::to(404);
		}else{
			$this->find($club);
		}
	}
	
	public function create($fields){
		if($this->_db->insert('club', $fields)){
			throw new Exception('There was a problem with Club creation');
		}
	}
	
	public function find($clubName = null){
		if($clubName){
			$field = (is_numeric($clubName)) ? 'clubid' : 'cname';
			$data =  $this->_db->get('club', array($field, '=', $clubName));
			
			if($data->count()){
				$this->_data = $data->position();
				return true;
			}
		}
		return false;
	} #Finds the club and stores the row in _data
	
	//public function listAdvisors(){
	//	return true; 
	//}
	
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	
	public function data(){
		return $this->_data;
	}
	
	public function comments($privacy = 0){
		$data = $this->_db->get('club_comment', array('clubid','=',$this->data()->clubid));
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
				echo "(Some messages may be hidden)<br><br>";
				foreach($data->results() as $commentid){
					$comment = new Comment($commentid->comment_id);
					$index = 1;
					foreach($data->results() as $commentid){
						if($comment->privacy()){ #If comment is not public
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
		}
		echo "<br><br>";
	} #Privacy 0 = Private, 1 = Public
}

?>