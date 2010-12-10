<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  
  
  $data = $this->DB->selectRows("SELECT projekty_etapy.projekt_id, punkty_wypowiedzi.id FROM projekty_etapy JOIN punkty_wypowiedzi ON projekty_etapy.etap_id=punkty_wypowiedzi.id WHERE projekty_etapy.typ_id=2 AND punkty_wypowiedzi.dzien_id='$id'");
  foreach( $data as $d ) {
    list($projekt_id, $punkt_id) = $d;
    
    $this->DB->q("DELETE FROM projekty_etapy WHERE projekt_id='$projekt_id' AND etap_id='$punkt_id'");
    $this->DB->q("DELETE FROM projekty_punkty_wypowiedzi WHERE projekt_id='$projekt_id' AND punkt_id='$punkt_id'");
    $this->DB->update_assoc('projekty', array('alert'=>'1'), $projekt_id);
  }
  
  
  
  $data = $this->DB->selectRows("SELECT projekty_etapy.projekt_id, punkty_glosowania.id FROM projekty_etapy JOIN punkty_glosowania ON projekty_etapy.etap_id=punkty_glosowania.id WHERE projekty_etapy.typ_id=3 AND punkty_glosowania.dzien_id='$id'");
  foreach( $data as $d ) {
    list($projekt_id, $punkt_id) = $d;
    
    $this->DB->q("DELETE FROM projekty_etapy WHERE projekt_id='$projekt_id' AND etap_id='$punkt_id'");
    $this->DB->q("DELETE FROM projekty_punkty_glosowania WHERE projekt_id='$projekt_id' AND punkt_id='$punkt_id'");
    $this->DB->update_assoc('projekty', array('alert'=>'1'), $projekt_id);
  }
  
  
  $this->DB->q("DELETE FROM `punkty_wypowiedzi` WHERE `dzien_id`='$id'");
  $this->DB->q("DELETE FROM `punkty_glosowania` WHERE `dzien_id`='$id'");
  $this->DB->q("DELETE FROM `punkty_wypowiedzi_druki` WHERE punkt_id IN (SELECT id FROM punkty_wypowiedzi WHERE dzien_id='$id')");
  $this->DB->q("DELETE FROM `punkty_glosowania_druki` WHERE punkt_id IN (SELECT id FROM punkty_glosowania WHERE dzien_id='$id')");
  $this->DB->q("DELETE FROM `posiedzenia_dni` WHERE id='$id'");
  
  
  $this->S('liczniki/nastaw/projekty-etapy');
  return 1;
?>