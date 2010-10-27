<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
    
  // $this->S('druki/przypisz_zalacznik', $id);
  
  $this->S('graber/projekty/pobierz', $id);
?>