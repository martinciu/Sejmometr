<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
 
  $this->DB->q("DELETE FROM projekty_etapy WHERE projekt_id='$id'");
  $this->DB->q("DELETE FROM projekty_druki WHERE projekt_id='$id'");
  $this->DB->q("DELETE FROM projekty_bas WHERE projekt_id='$id'");
  $this->DB->q("DELETE FROM projekty_isap WHERE projekt_id='$id'");
  $this->DB->q("DELETE FROM projekty_punkty_glosowania WHERE projekt_id='$id'");
  $this->DB->q("DELETE FROM projekty_punkty_wypowiedzi WHERE projekt_id='$id'");

  $this->DB->q("DELETE FROM projekty WHERE id='$id'");
  return $this->DB->affected_rows;
?>