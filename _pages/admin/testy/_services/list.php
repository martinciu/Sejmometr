<?
  return $this->DB->selectValues("SELECT id FROM poslowie");

  /*
  return $this->DB->selectValues("SELECT id FROM punkty_wypowiedzi WHERE przeliczono='0'");
  

  $where = '1';
  // $where = "akcept='0'";
  return $this->DB->selectValues("SELECT id FROM `projekty` WHERE $where ORDER BY data_dodania ASC");
  */




  // return $this->DB->selectValues("SELECT id FROM `dokumenty` WHERE _temp_obraz='0'");
  
  /*
  $folder = 'dokumenty';
  
  $result = json_decode( file_get_contents("http://sejmometr.pl/".$folder.".json") );
  if( is_array($result) ) foreach($result as &$item) $item = $folder.'/'.$item;
  
  
  return $result;
  */
  // return array( $result[0] );
?>