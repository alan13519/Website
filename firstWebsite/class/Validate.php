<?php 
class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	
	public function check($source, $items = array()){
		foreach($items as $item => $rules){
			foreach($rules as $rule => $rule_value){
				$value = trim($source[$item]);
				$item = escape($item);
				if($rule === 'required' && empty($value)){
					$this->addError("{$item} is required");
				} else if(!empty($value)){
					switch($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this->addError("{$item} has to have a minimum length of {$rule_value}");
							}
						break;
						case 'max': 
							if(strlen($value) > $rule_value){
								$this->addError("{$item} has to have a maximum length of {$rule_value}");
							}
						break;
						case 'intmin':
							if($value < $rule_value){
								$this->addError("{$item} has to have a minimum value of {$rule_value}");
							}
						break;
						case 'intmax': 
							if($value > $rule_value){
								$this->addError("{$item} has to have a maximum value of {$rule_value}");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$item} must match {$rule_value}");
							}
						break;
						case 'unique':
							//$check = $this->_db->get($rule_value, array('pid', '=', $value));
							$check = $this->_db->get('person', array($rule_value, '=', $value));
							if($check->count()){
								$this->addError("Username already exists.");
							}
						break;
						case 'numeric':
							if(!is_numeric($value)){
								$this->addError("{$item} is not a valid number");
							}
						break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}
	
	private function addError($error){
		$this->_errors[] = $error;
	}
	
	public function errors(){
		return $this->_errors;
	}
	
	public function passed(){
		return $this->_passed;
	}
}

?>