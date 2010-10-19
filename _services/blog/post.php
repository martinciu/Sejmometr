<?
  $id = $_PARAMS;
  if( !is_numeric($id) ) return false;
  
  return $this->DB->selectAssoc("SELECT id, tytul, opis, tresc, autor, DATE(data_zapisania) as 'data_zapisu' FROM blog WHERE id='$id'");
?>