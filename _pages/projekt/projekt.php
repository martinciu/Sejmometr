<?
  $projekt_id = $_GET['_ID'];
  
  $projekt = $this->DB->selectAssoc("SELECT projekty.id, projekty.sejm_id, projekty.tytul, projekty.status_slowny, druki_autorzy.autor, projekty.opis, projekty.ilosc_dodatkowych_dokumentow, druki.numer, druki.dokument_id, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron, projekty_typy.menu_id, projekty.data_wplynal FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id LEFT JOIN projekty_typy ON projekty.typ_id=projekty_typy.id WHERE projekty.id='$projekt_id'");
  
  $this->DB->q("UPDATE projekty SET ilosc_odwiedzin=ilosc_odwiedzin+1 WHERE id='$projekt_id'");
  
  $_GET['_TYPE'] = $projekt['menu_id'];
  
  
  $_TABS = array( array('proces', 'Proces') );
  
  if( $projekt['ilosc_dodatkowych_dokumentow']>0 ) $_TABS[] = array('dokumenty', 'Dodatkowe dokumenty ('.$projekt['ilosc_dodatkowych_dokumentow'].')');

  $_TAB = $_GET['_TAB'];
  $allowed_tabs = array('proces', 'dokumenty');
  if( !in_array($_TAB, $allowed_tabs) ) $_TAB = $allowed_tabs[0];
  $this->assignService($_TAB, $_TAB, $projekt_id);
    
  
  $this->SMARTY->assign('projekt', $projekt);
  $this->SMARTY->assign('_TABS', $_TABS);
  $this->SMARTY->assign('_TAB', $_TAB);
  
  $this->set_meta('description', $projekt['opis']);
  $this->TITLE = $projekt['tytul'];
?>