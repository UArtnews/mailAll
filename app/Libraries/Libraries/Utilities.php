<?

class Utilities {
    
    function __construct(){
    }
    

  	public function obj_toArray($obj){
		 if (is_array($obj)) {
			 //print("running is array!!!!!!!!!\n\n");
				foreach ($obj as $key => $value) {
					if (is_array($value)) {
						$obj[$key] = $this->obj_toArray($value);
					}
					if (is_object($value)) { //instanceof stdClass
						$obj[$key] = $this->obj_toArray((array)$value);
					}
				}
			}
			if ($obj instanceof stdClass) {
				return $this->obj_toArray((array)$obj);
			}
		return $obj;
    }
	  	public function cleanupArrForExcel($data,$headers){
			$ret = array();
			$row = array();
			foreach ($data as $key => $value) {
					$row = $data[$key];
				foreach ($headers as $kkey => $kvalue) {
					$ret[$key][$kvalue] = $row[$kkey];
					if(is_array($ret[$key][$kvalue])){ $ret[$key][$kvalue]= implode("," , $ret[$key][$kvalue]);}
				}
			}
		return $ret;
    }
	
	function getTweakableByParam($param, $obj){		
		foreach ($obj as $id => $row) {
				if(isset($obj[$param])){
					return $obj[$param];
				}
				if(is_array($row)){
					foreach ($row as $key => $value) {
						//print_r($row->getAttribute("parameter"));
						if ($row->getAttribute("parameter") == $param) {

							return $row->getAttribute("value");
						}
					}					
					
				}//END of IF
			}			
			return false;
    }
	
	
	
	function generateRandomString($length = 10) {
    	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}	
	
}