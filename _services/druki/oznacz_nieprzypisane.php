<?
  // $this->DB->q("UPDATE druki SET przypisany='0'");
  // $this->DB->q("UPDATE projekty_etapy SET sprawdz_przypisanie='0'");

  $this->DB->q("UPDATE projekty_etapy SET sprawdz_przypisanie='1' WHERE typ_id=0 AND sprawdz_przypisanie='0'"); 
  
  $ids = $this->DB->selectValues("SELECT etap_id FROM projekty_etapy  WHERE typ_id=0 AND sprawdz_przypisanie='1'");
  foreach( $ids as $id ) { $this->DB->update_assoc('druki', array('przypisany'=>'1'), $id); }
  
  $this->DB->q("UPDATE projekty_etapy SET sprawdz_przypisanie='2' WHERE typ_id=0 AND sprawdz_przypisanie='1'");
  
  return $this->DB->selectCount("SELECT COUNT(*) FROM projekty_etapy WHERE typ_id=0 AND sprawdz_przypisanie='0'");
?>