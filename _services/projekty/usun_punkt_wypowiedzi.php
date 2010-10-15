<?
  $punkt_id = $_PARAMS;
  if( strlen($punkt_id)!=5 ) return false;
  
  $projekty = $this->DB->selectValues("SELECT projekt_id FROM projekty_etapy WHERE ((typ_id='2' OR typ_id='3') AND etap_id='$punkt_id')");
  if( !empty($projekty) ) { return 'Ten punkt został już przypisany do projektów!'; }
  
  $this->DB->q("DELETE FROM projekty_punkty_wypowiedzi WHERE `punkt_id`='$punkt_id'");
  $this->DB->q("DELETE FROM punkty_wypowiedzi_druki WHERE `punkt_id`='$punkt_id'");
  $this->DB->q("UPDATE punkty_wypowiedzi SET `akcept`='0' WHERE `id`='$punkt_id'");
  
  $this->S('liczniki/nastaw/punkty_wypowiedzi');
?>