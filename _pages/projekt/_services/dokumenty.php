<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  $result = array();
  
  $akty_wykonawcze = $this->DB->selectAssocs("SELECT druki.data, dokumenty.id as 'dokument_id', dokumenty.ilosc_stron, dokumenty.scribd_doc_id, dokumenty.scribd_access_key FROM akty_wykonawcze JOIN druki ON akty_wykonawcze.druk_id=druki.id JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE akty_wykonawcze.projekt_id='$id' AND druki.akcept='1' ORDER BY druki.data ASC");
  
  $dokumenty = $this->DB->selectAssocs("SELECT projekty_dokumenty.tytul, druki.data, dokumenty.id as 'dokument_id', dokumenty.ilosc_stron, dokumenty.scribd_doc_id, dokumenty.scribd_access_key FROM projekty_dokumenty JOIN druki ON projekty_dokumenty.druk_id=druki.id JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty_dokumenty.projekt_id='$id' AND projekty_dokumenty.akcept='1' AND druki.akcept='1' ORDER BY druki.data ASC, projekty_dokumenty.tytul ASC");
  
  $BAS = $this->DB->selectAssocs("SELECT bas.data, dokumenty.id as 'dokument_id', dokumenty.ilosc_stron, dokumenty.download_url FROM projekty_bas LEFT JOIN bas ON projekty_bas.bas_id=bas.id LEFT JOIN dokumenty ON bas.dokument_id=dokumenty.id WHERE bas.akcept='1' AND projekty_bas.projekt_id='$id' ORDER BY bas.data ASC");
  
  $SR = $this->DB->selectAssocs("SELECT druki.data, dokumenty.id as 'dokument_id', dokumenty.ilosc_stron, dokumenty.scribd_doc_id, dokumenty.scribd_access_key FROM projekty_stanowiska_rzadu LEFT JOIN druki ON projekty_stanowiska_rzadu.druk_id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty_stanowiska_rzadu.projekt_id='$id' AND druki.akcept='1' ORDER BY druki.data ASC");
  
  $opinie = $this->DB->selectAssocs("SELECT druki.data, dokumenty.id as 'dokument_id', dokumenty.ilosc_stron, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, druki_autorzy.autor FROM projekty_opinie LEFT JOIN druki ON projekty_opinie.druk_id=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty_opinie.projekt_id='$id' AND druki.akcept='1' ORDER BY druki.data ASC");
  
  
  
  $result['BAS'] = $BAS;
  $result['SR'] = $SR;
  $result['opinie'] = $opinie;
  $result['akty_wykonawcze'] = $akty_wykonawcze;
  $result['dokumenty'] = $dokumenty;
    
  return $result;
?>