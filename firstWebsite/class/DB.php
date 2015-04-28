<?php
class DB{
	private static $_instance = null; #Going to store instance of the database if it is available
	private $_pdo, #Stores PDO object
			$_query, #Stores last query that was executed
			$_error = false,  #Represents if there was an error
			$_results, #Stores result sets from query
			$_count = 0; #Stores the count of results
			
	private function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/dbhost'). ';dbname=' . Config::get('mysql/dbname'), Config::get('mysql/dbuser'), Config::get('mysql/dbpass'));
			//$this->_pdo = new PDO('mysql:host=localhost;dbname=clubhub','root','');
			//echo Config::get('mysql/dbhost');
			//echo Config::get('mysql/dbname');
			//echo Config::get('mysql/dbuser');
			//echo Config::get('mysql/dbpass');
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		} #If it isn't created create a new DB
		return self::$_instance;
	}
	
	public function query($sql, $params = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$index = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($index, $param);
					$index++;
				}
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); #Fetches the object of results
				$this->_count = $this->_query->rowCount(); #Updates the query count
				
			}else{
				$this->_error = true;
			}
		}
		return $this;
	}
	
	public function action($action, $table, $where = array()){
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=', '!=', 'like');
			$field     = $where[0];
			$operator  = $where[1];
			$value     = $where[2];
			
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				if(!$this->query($sql, array($value))->error()){
					return $this;
				} #If you don't get an error
			}
		}
		return false;
	}
	
	public function get($table, $where){
		return $this->action('select *', $table, $where);
	} #get('person', array('pid', '=', '1'));
	
	public function delete($table, $where){
		return $this->action('delete', $table, $where);
	} 
	
	public function insert($table, $fields = array()){
		//if(count($fields)){
			$keys = array_keys($fields);
			$values = ''; #keep track of variables in query
			$index = 1;
			
			foreach($fields as $field){
				$values .= '?';
				if($index < count($fields)){
					//echo $fields['sponsored_by'];
					$values .= ', ';
				}
				$index++;
			}
			
			$sql = "insert into {$table} (`"  . implode('`, `' , $keys). "`) VALUES ({$values})";
			
			if($this->query($sql, $fields)->error()){
				return true;
			}
		//}
		
		return false;
	}
	
	public function update($table, $key, $fields){
		$set = '';
		$index = 1;
		
		foreach($fields as $name => $value){
			$set .= "P{$name} = ?"; #Bind
			if($index < count($fields)){
				$set .= ',';
			}
			$index++;
		}
		$sql = "update {$table} set {$set} where pid = {$key} ";
		
		if(!$this->query($sql, $fields)->error()){
			return true;
		}
		return false;
	} #update('person', 1, array('passwd' => md5('newpass'));
	
	public function results(){
		return $this->_results;
	}
	
	public function position($pos = '0'){
		return $this->results()[0];
	} #Returns a position from results, currently not working
	
	public function error(){
		return $this->_error;
	}
	
	public function count(){
		return $this->_count;
	}
	
}
?>