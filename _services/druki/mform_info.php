<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  return $this->DB->selectAssoc("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki.autorA_id, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki.id='$id'");
?>