<?
  $projekt_id = $_GET['_ID'];
  
  $projekt = $this->DB->selectAssoc("SELECT projekty.id, projekty.ilosc_opinii, projekty.sejm_id, projekty.tytul, projekty.status_slowny, druki_autorzy.autor, projekty.opis, druki.numer, druki.dokument_id, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron, projekty_typy.menu_id, projekty.data_wplynal FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id LEFT JOIN projekty_typy ON projekty.typ_id=projekty_typy.id WHERE projekty.id='$projekt_id'");
  $_GET['_TYPE'] = $projekt['menu_id'];
  
  
  $_TABS = array( array('proces', 'Proces') );
  if( $projekt['ilosc_opinii']>0 ) $_TABS[] = array('opinie', 'Opinie ('.$projekt['ilosc_opinii'].')');
  
  $_TAB = $_GET['_TAB'];
  $allowed_tabs = array('proces', 'opinie');
  if( !in_array($_TAB, $allowed_tabs) ) $_TAB = $allowed_tabs[0];
  $this->assignService($_TAB, $_TAB, $projekt_id);
    
  
  $this->SMARTY->assign('projekt', $projekt);
  $this->SMARTY->assign('_TABS', $_TABS);
  $this->SMARTY->assign('_TAB', $_TAB);
  
  $this->set_meta('description', $projekt['opis']);
  $this->TITLE = $projekt['tytul'];
?>