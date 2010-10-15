<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  return $this->DB->selectAssoc("SELECT projekty.id, druki.numer, druki_autorzy.autor, projekty.tytul, druki.numer FROM druki LEFT JOIN projekty ON druki.id=projekty.druk_id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id WHERE projekty.id='$id'");  
?>