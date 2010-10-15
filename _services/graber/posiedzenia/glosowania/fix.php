<?
  $id = $_PARAMS;
  
  list($dzien_id, $punkt) = $this->DB->selectRow("SELECT glosowania.dzien_id, glosowania_modele.punkt FROM glosowania LEFT JOIN glosowania_modele ON glosowania.id=glosowania_modele.glosowanie_id WHERE glosowania.id='$id'");
  if( empty($dzien_id) || empty($punkt) ) return false;
  
  $punkt_id = $this->DB->selectValue("SELECT id FROM punkty_glosowania WHERE `dzien_id`='$dzien_id' AND `sejm_id`='$punkt'");
  
  if( empty($punkt_id) ) return false;
  
  $this->DB->update_assoc("glosowania", array('punkt_id'=>$punkt_id), $id);
  
  $ilosc_glosowan = $this->DB->selectCount("SELECT COUNT(*) FROM glosowania WHERE punkt_id='$punkt_id'");
  $this->DB->update_assoc("punkty_glosowania", array('akcept'=>'0', 'ilosc_glosowan'=>$ilosc_glosowan), $punkt_id);
?>