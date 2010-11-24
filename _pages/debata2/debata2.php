<?
  $_typy_schematy = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '1',
    '5' => '3',
    '7' => '2',
    '8' => '3',
    '10' => '3',
    '11' => '3',
    '12' => '2',
  );
  $_czytania_typy = array(
    '1' => array(
      '1' => 'Pierwsze czytanie',
      '2' => 'Drugie czytanie',
      '3' => 'Trzecie czytanie',
      '4' => 'Rozpatrywanie stanowiska Senatu',
      '5' => 'Rozpatrywanie niezgodności z Konstytucją',
      '6' => 'Rozpatrywanie wniosku Prezydenta'
    ),
    '2' => array(
      '1' => 'Pierwsze czytanie',
      '2' => 'Drugie czytanie',
      '3' => 'Trzecie czytanie',
    ),
    '3' => array(
      '0' => 'Rozpatrywanie',
      '1' => 'Przyjęcie bez zastrzeżeń',
      '3' => 'Wysłuchanie',
    ),
  );
  
  

  $debata_id = $_GET['_ID'];
  
  $debata = $this->DB->selectAssoc("SELECT punkty_wypowiedzi.id, punkty_wypowiedzi.dzien_id, punkty_wypowiedzi.typ_id, posiedzenia_dni.data FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.id='$debata_id'");
  switch( $debata['typ_id'] ) {
    case '1': {

      $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty_etapy.subtyp, projekty_typy.menu_id, projekty.typ_id, druki.numer, druki.dokument_id, druki_autorzy.autor FROM projekty_etapy LEFT JOIN projekty ON projekty_etapy.projekt_id=projekty.id LEFT JOIN projekty_typy ON projekty.typ_id=projekty_typy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON druki_autorzy.id=projekty.autor_id WHERE projekty_etapy.typ_id=2 AND projekty_etapy.etap_id='$debata_id'");
      if( count($projekty)==1 ) {
      
        $projekt = $projekty[0];
        $schemat = $_typy_schematy[ $projekt['typ_id'] ];
        $czytanie_label = $_czytania_typy[ $schemat ][ $projekt['subtyp'] ];
        $projekt['czytanie_label'] = $czytanie_label;
        
        $_GET['_TYPE'] = $projekt['menu_id'];
        $this->TITLE = $czytanie_label.', '.$projekt['tytul'];
        
        $this->SMARTY->assign('debata_typ', 1);
        $this->SMARTY->assign('projekt', $projekt);
        
      }
        
      break;
    }
    case '4': {
      $_GET['_FRONT_MENU_SELECTED'] = 'debaty_specjalne';
      $debata = array_merge($debata, $this->DB->selectAssoc("SELECT tytul FROM debaty_specjalne WHERE id='$debata_id'"));
      $this->TITLE = $debata['tytul'];
      break;
    }
  }
  
  
 
  $this->SMARTY->assign('debata', $debata); 
  $this->assignService('wypowiedzi_lista', 'wypowiedzi_lista', $debata_id);
?>