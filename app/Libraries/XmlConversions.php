<?

class XmlConversions {
	
	var $xmlObj = null;
    
    function __construct(){
    }
    
    public static function from_array($arr, $xml = NULL)
    {
        $first = $xml;
        if($xml === NULL) $xml = new SimpleXMLElement('<content/>');
        foreach ($arr as $k => $v)
        {
            is_array($v)
            ? self::from_array($v, $xml->addChild($k))
            : $xml->addChild($k, $v);
        }
       return ($first === NULL) ? $xml->asXML() : $xml->asXML();
     }
    
    
    public function to_array($xml)
	{
      $xmlObj = simplexml_load_string($xml);
	  $this->xmlObj = $xmlObj;
	  return $xmlObj;
	}//function XMLToArray
	
	public function getAttributes($xmlTag,$index,$attr = NULL)
	{
      	$item = $this->xmlObj->{$xmlTag}[$index];
		if(is_object($item)){
			if($attr != NULL){ return $item[$attr];}
			return $item->attributes();
		}else{
			return "false";			
		}
	}//function XMLToArray
	
  	

	
	public function to_oldarray($xml,$retType = NULL)//DELETE ME
    
    {
         $xmlparser = xml_parser_create();
        xml_parse_into_struct($xmlparser,$xml,$values,$index);
		xml_parser_free($xmlparser);
        if($retType == "i"){return $index;}else{return $values;}
		
    }
	
}