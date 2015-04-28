<?php
class User{
	private $_db, #Instance of the database
			$_data, #Data for the user
			$_sessionName,
			$_isLoggedIn;
	
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)){
					$this->_isLoggedIn = true;
				} else{
					$user->logout(); #Log out 
				}
			}
		} else{
			$this->find($user);
		}
	}
	
	public function create($fields){
		if($this->_db->insert('users', $fields)){
			throw new Exception('There was a problem with account creation');
		}
	}
	
	public function find($userName = null){
		if($userName){
			$field = (is_numeric($userName)) ? 'pid' : 'fname';
			$data = $this->_db->get('person', array($field, '=', $userName));
			
			if($data->count()){
				$this->_data = $data->position();
				return true;
			}
		}
		return false;
	} #Finds the pid and stores it in the data row
	
	public function login($username = null, $password = null){
		
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->pid);
		}else{
			$user = $this->find($username);
			
			if($user){
				if($this->data()->passwd === md5($password)){
					Session::put($this->_sessionName, $this->data()->pid);
					return true;
				}
				else{
					//echo "Password is wrong";
				}
			}else{
				//echo "Username is wrong";
			}
			
			return false;
		}
	}
	
	public function signup($eventid){
		$check = $this->_db->get('sign_up', array('pid', '=', $this->_data->pid));
		$truth = true;
		
		if($check->count()){
			foreach($check->results() as $result){
				if($result->eid == $eventid){
					echo "You have already signed up!";
					$truth = false;
				}
			}
		}
		if($truth){
			echo "Sign up Successful!";
			if($this->_db->insert('sign_up', array(
				'pid' => $this->_data->pid,
				'eid' => $eventid
			))){
				throw new Exception('Problem with Event signup');
			}
		}
	} #Need validation
		
	public function hasPermissionToViewEvent($eventid){
		$event = new Event($eventid);
		
		$eventdata = $event->data(); #event id
		if($eventdata->is_public_e == '1'){
			return true;
		}elseif($this->isPartofClub($eventdata->sponsored_by)){
			return true;
		}
		return false;
	}
	
	public function roleInClub($clubid){
		$roles = $this->_db->get('role_in', array('clubid', '=', $clubid));
		if($roles->count()){
			foreach($roles->results() as $role){
				if($this->data()->pid == $role->pid){
					return $role->role;
				}
			}
		}
		return false;
	}
	
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	
	public function logout(){
		Session::delete($this->_sessionName);
	}
	
	public function data(){
		return $this->_data;
	}
	
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
	
	public function isPartofClub($clubid){
		$data = $this->_db->get('member_of', array('pid', '=', $this->_data->pid));
		
		if($data->count()){
			foreach($data->results() as $dat){
				if($dat->clubid == $clubid){
					return true;
				}
			}
		}else{
			$data = $this->_db->get('advisor_of', array('pid', '=', $this->_data->pid));
			if($data->count()){
			foreach($data->results() as $dat){
				if($dat->clubid == $clubid){
					return true;
				}
			}
		}
		}
		return false;
	}
	
}

?>