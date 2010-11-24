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
      array('1', 'Pierwsze czytanie'),
       array('2', 'Drugie czytanie'),
       array('3', 'Trzecie czytanie'),
       array('4', 'Rozpatrywanie stanowiska Senatu'),
       array('5', 'Rozpatrywanie niezgodności z Konstytucją'),
       array('6', 'Rozpatrywanie wniosku Prezydenta'),
    ),
    '2' => array(
       array('1', 'Pierwsze czytanie'),
       array('2', 'Drugie czytanie'),
       array('3', 'Trzecie czytanie'),
    ),
    '3' => array(
       array('0', 'Rozpatrywanie'),
       array('1', 'Przyjęcie bez zastrzeżeń'),
       array('3', 'Wysłuchanie'),
    ),
  );
  
  
  $id = $_PARAMS['id'];
  
  $debata = $this->DB->selectAssoc("SELECT multidebaty.id, multidebaty.typ, multidebaty.tytul, multidebaty.akcept, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia_dni.data FROM multidebaty JOIN punkty_wypowiedzi ON multidebaty.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE multidebaty.id='$id'");
  
  $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty_etapy.subtyp, projekty_typy.menu_id, projekty.typ_id, druki.numer, druki.dokument_id, druki_autorzy.autor FROM projekty_etapy LEFT JOIN projekty ON projekty_etapy.projekt_id=projekty.id LEFT JOIN projekty_typy ON projekty.typ_id=projekty_typy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON druki_autorzy.id=projekty.autor_id WHERE projekty_etapy.typ_id=2 AND projekty_etapy.etap_id='$id' ORDER BY LENGTH(projekty.tytul) DESC");
  
  $typy = array();
  $subtypy = array();
  foreach( $projekty as $projekt ) {
    if( !in_array($projekt['typ_id'], $typy) ) $typy[] = $projekt['typ_id'];
    if( !in_array($projekt['subtyp'], $subtypy) ) $subtypy[] = $projekt['subtyp'];
  }
  
  if( !$debata['tytul'] ) $debata['tytul_suggestion'] = $this->DB->selectValue("SELECT multidebaty.tytul FROM projekty_etapy JOIN multidebaty ON projekty_etapy.etap_id=multidebaty.id WHERE projekty_etapy.projekt_id IN (SELECT projekt_id FROM `projekty_etapy` WHERE typ_id=2 AND etap_id='".$id."') AND projekty_etapy.typ_id=2 AND multidebaty.akcept='1' GROUP BY multidebaty.id");
  $debata['typ'] = (int) $debata['typ'];
  $debata['subtypy'] = $subtypy;
  $debata['typy_error'] = count($typy)!=1;
  $projekt_typ = $typy[0];
  $projekt_schemat = $_typy_schematy[ $projekt_typ ];
  $_typy_options = $_czytania_typy[ $projekt_schemat ]; 
  
  $result = array(
    'item' => $debata,
    'projekty' => $projekty,
    '_typy_options' => $_typy_options,
  );
      
  return $result;
?>