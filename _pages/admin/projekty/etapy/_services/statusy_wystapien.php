<?
  $dzien_id = $_PARAMS;
  return $this->DB->selectValues("SELECT DISTINCT(status) FROM `dni_modele` WHERE `dzien_id`='$dzien_id' AND `typ`='1'");  
?>