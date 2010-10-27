<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $zalacznik = $this->DB->selectValue("SELECT zalacznik FROM druki WHERE id='$id'");
  if( $zalacznik=='1' ) {
    return $this->S('druki/przypisz_zalacznik', $id);
  } else {
    return $this->S('druki/przypisz_druk', $id);
  }
?>