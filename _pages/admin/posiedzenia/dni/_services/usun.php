<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $posiedzenie_id = $this->DB->selectValue("SELECT posiedzenie_id FROM posiedzenia_dni WHERE id='$id'");
  
  
  $this->DB->q("DELETE FROM dni_modele WHERE dzien_id='$id'");
  $this->DB->q("DELETE FROM wypowiedzi WHERE dzien_id='$id'");
  $this->DB->q("DELETE FROM glosowania WHERE dzien_id='$id'");
  $this->DB->q("DELETE FROM posiedzenia_dni WHERE id='$id'");
  
  $this->S('graber/posiedzenia/sprawdz', $posiedzenie_id);
  
  
  
  return 1;
?>