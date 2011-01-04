<?  
  $id = $_PARAMS['id'];
  
  $debata = $this->DB->selectAssoc("SELECT multidebaty.id, multidebaty.typ, multidebaty.tytul, multidebaty.akcept, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia_dni.data FROM multidebaty JOIN punkty_wypowiedzi ON multidebaty.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE multidebaty.id='$id'");
  
  $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty_etapy.subtyp, projekty_typy.menu_id, projekty.typ_id, druki.numer, druki.dokument_id, druki_autorzy.autor FROM projekty_etapy LEFT JOIN projekty ON projekty_etapy.projekt_id=projekty.id LEFT JOIN projekty_typy ON projekty.typ_id=projekty_typy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON druki_autorzy.id=projekty.autor_id WHERE projekty_etapy.typ_id=2 AND projekty_etapy.etap_id='$id' ORDER BY LENGTH(projekty.tytul) DESC");
  
  if( !$debata['tytul'] ) $debata['tytul_suggestion'] = $this->DB->selectValue("SELECT multidebaty.tytul FROM projekty_etapy JOIN multidebaty ON projekty_etapy.etap_id=multidebaty.id WHERE projekty_etapy.projekt_id IN (SELECT projekt_id FROM `projekty_etapy` WHERE typ_id=2 AND etap_id='".$id."') AND projekty_etapy.typ_id=2 AND multidebaty.akcept='1' GROUP BY multidebaty.id");
  
  $result = array(
    'item' => $debata,
    'projekty' => $projekty,
  );
      
  return $result;
?>