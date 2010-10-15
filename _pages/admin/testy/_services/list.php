<?
  return $this->DB->selectValues("SELECT id FROM `druki` WHERE tempA=0 AND `typ_id`=7 AND autorA_id='Rzad'");

  // return $this->DB->selectValues("SELECT id FROM `dokumenty` WHERE _temp_obraz='0'");
  
  /*
  $folder = 'dokumenty';
  
  $result = json_decode( file_get_contents("http://sejmometr.pl/".$folder.".json") );
  if( is_array($result) ) foreach($result as &$item) $item = $folder.'/'.$item;
  
  
  return $result;
  */
  // return array( $result[0] );
?>