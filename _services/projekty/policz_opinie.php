<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $opinie_bas = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_bas LEFT JOIN bas ON projekty_bas.bas_id=bas.id WHERE projekty_bas.projekt_id='$id' AND bas.akcept='1'");
  $stanowiska_rzadu = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_stanowiska_rzadu LEFT JOIN druki ON projekty_stanowiska_rzadu.druk_id=druki.id WHERE projekty_stanowiska_rzadu.projekt_id='$id' AND druki.akcept='1'");
  $opinie = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_opinie LEFT JOIN druki ON projekty_opinie.druk_id=druki.id WHERE projekty_opinie.projekt_id='$id' AND druki.akcept='1'");
  
  $ilosc_opinii = $opinie_bas + $stanowiska_rzadu + $opinie;
  
  $this->DB->update_assoc('projekty', array(
    'ilosc_opinii' => $ilosc_opinii,
  ), $id);
?>