<?
  return $this->DB->selectValues("SELECT id FROM `projekty` WHERE data_sprawdzenia='0000-00-00 00:00:00'");

  // return $this->DB->selectValues("SELECT id FROM `dokumenty` WHERE _temp_obraz='0'");
  
  /*
  $folder = 'dokumenty';
  
  $result = json_decode( file_get_contents("http://sejmometr.pl/".$folder.".json") );
  if( is_array($result) ) foreach($result as &$item) $item = $folder.'/'.$item;
  
  
  return $result;
  */
  // return array( $result[0] );
?>