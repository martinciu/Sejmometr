<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  return $this->DB->selectAssocs("SELECT id, typ, autor_id, autor_typ, funkcja, skrot, SUBSTRING(text, 1, 100) as 'txt' FROM wypowiedzi WHERE punkt_id='$id' ORDER BY ord ASC");
?>