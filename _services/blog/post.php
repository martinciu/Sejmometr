<?
  $id = $_PARAMS;
  if( !is_numeric($id) ) return false;
  
  return $this->DB->selectAssoc("SELECT id, tytul, url_title, opis, tresc, autor, DATE(data_zapisania) as 'data_zapisu', 'ofensywa' as 'img' FROM blog WHERE id='$id'");
?>