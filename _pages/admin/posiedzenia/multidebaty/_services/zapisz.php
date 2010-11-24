<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $data = array(
    'typ' => $_PARAMS['typ'],
    'tytul' => $_PARAMS['tytul'],
    'projekty_typ' => $this->DB->selectValue("SELECT projekty.typ_id FROM projekty_etapy LEFT JOIN projekty ON projekty_etapy.projekt_id=projekty.id WHERE projekty_etapy.typ_id=2 AND projekty_etapy.etap_id='$id'"),
    'ilosc_projektow' => $this->DB->selectValue("SELECT COUNT( DISTINCT(projekt_id) ) FROM projekty_etapy WHERE typ_id=2 AND etap_id='$id'"),
    'akcept' => '1',
  );
  
  $this->DB->update_assoc('multidebaty', $data, $id);
  $this->S('liczniki/nastaw/multidebaty');
  return 4;
?>