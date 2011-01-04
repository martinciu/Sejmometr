<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $akty_wykonawcze = $this->DB->selectCount("SELECT COUNT(*) FROM akty_wykonawcze WHERE projekt_id='$id'");
  $inne_dokumenty = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_dokumenty WHERE projekt_id='$id' AND akcept='1'");
  $opinie_bas = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_bas LEFT JOIN bas ON projekty_bas.bas_id=bas.id WHERE projekty_bas.projekt_id='$id' AND bas.akcept='1'");
  $stanowiska_rzadu = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_stanowiska_rzadu LEFT JOIN druki ON projekty_stanowiska_rzadu.druk_id=druki.id WHERE projekty_stanowiska_rzadu.projekt_id='$id' AND druki.akcept='1'");
  $opinie = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_opinie LEFT JOIN druki ON projekty_opinie.druk_id=druki.id WHERE projekty_opinie.projekt_id='$id' AND druki.akcept='1'");
  
  $count = $akty_wykonawcze + $inne_dokumenty + $opinie_bas + $stanowiska_rzadu + $opinie;
  $this->DB->update_assoc('projekty', array('ilosc_dodatkowych_dokumentow'=>$count), $id);
  return $count;
?>