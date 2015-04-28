<?php
class Comment {
	private $_db,
			$_data,
			$_privacy,
			$_comment,
			$_commenter;
			
	public function __construct($comment = null){
		$this->_db = DB::getInstance();
		if(!$this->find($comment)){
			//Redirect::to(404);
		}else{
			$this->find($comment);
		}
	}
	
	public function create($fields, $where, $idtype, $id){
		if($this->_db->insert('comment', $fields) ){
			throw new Exception('There was a problem with Comment creation');
		}
		if($this->_db->insert($where, array(
			'comment_id' => $fields['comment_id'],
			$idtype => $id
		))){
			throw new Exception('There was a problem with Comment creation ');
		}
		

	} #create a comment at a location needs a bit more
	
	public function find($commentName = null){
		if($commentName){
			$field = (is_numeric($commentName)) ? 'comment_id' : 'ctext';
			$data = $this->_db->get('comment', array($field,'=',$commentName));
			if($data->count()){
				$this->_data = $data->position();
				//echo $this->_data->is_public_c;
				$this->_privacy = $this->data()->is_public_c;
				$this->_comment = $this->data()->ctext;
				$this->_commenter = $this->data()->commenter;
				return true;
			}
		}
		return false;
	} #Finds the event and stores the row in _data
	
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	
	public function data(){
		return $this->_data;
	}
	
	public function getNewID(){
		$index = 1;
		$truth = false;
		while(true){
			$data = $this->_db->get('comment', array('comment_id' ,'=' ,$index)); #Get everything from comment
			#select * from 'comment'
			if($data->count()){
				$index++;
			}else{
				return $index;
			}
		}
		return false;
	} #Get a new id 
	
	public function returnComment(){
		return $this->_comment; 
	}
	
	public function returnCommenter(){
		return $this->_commenter;
	}
	
	public function privacy(){
		return $this->_privacy;
	} #1 = public; 0 = private
}

?>