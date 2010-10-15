<?
  $projekt_id = $_GET['_ID'];
  
  $projekt = $this->DB->selectAssoc("SELECT projekty.sejm_id, projekty.tytul, projekty.status_slowny, druki_autorzy.autor, projekty.opis, druki.numer, druki.dokument_id, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty.id='$projekt_id'");
  if($projekt['numer']) $projekt['title'] = 'Druk nr '.$projekt['numer'];
  
  $proces = $this->S('proces', $projekt_id);
    
  $this->SMARTY->assign('projekt', $projekt);
  $this->SMARTY->assign('proces', $proces);
  
  $this->set_meta('description', $projekt['opis']);
  $this->TITLE = $projekt['tytul'];
?>