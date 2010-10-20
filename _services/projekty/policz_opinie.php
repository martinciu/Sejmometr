<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $opinie_bas = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_bas_ WHERE projekt_id='$id'");
  
  $ilosc_opinii = $opinie_bas;
  
  $this->DB->update_assoc('projekty', array(
    'ilosc_opinii' => $ilosc_opinii,
  ), $id);
?>