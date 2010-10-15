<?
  $dzien_id = $_PARAMS;
  return $this->DB->selectValues("SELECT DISTINCT(status) FROM `glosowania` WHERE `dzien_id`='$dzien_id'");
?>