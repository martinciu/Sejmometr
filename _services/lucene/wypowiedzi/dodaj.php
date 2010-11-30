<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $typ = $this->DB->selectValue("SELECT typ FROM wypowiedzi WHERE id='$id'");
  if( $typ=='1' ) {
  
	  $wyp = $this->DB->selectAssoc("SELECT multidebaty.typ as 'multidebata_czytanie_typ', multidebaty.tytul as 'multidebata_tytul', multidebaty.projekty_typ as 'multidebata_projekt_typ', projekty.typ_id as 'projekt_typ', projekty.tytul, projekty_etapy.subtyp as 'czytanie_typ', wypowiedzi.id, punkty_wypowiedzi.id as 'debata_id', projekty.id as 'projekt_id', punkty_wypowiedzi.opis, wypowiedzi_funkcje.nazwa as 'funkcja', wypowiedzi.autor_id, ludzie.nazwa as 'autor', posiedzenia_dni.data, wypowiedzi.text, wypowiedzi.skrot FROM wypowiedzi LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id LEFT JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id LEFT JOIN posiedzenia_dni ON wypowiedzi.dzien_id=posiedzenia_dni.id LEFT JOIN punkty_wypowiedzi ON wypowiedzi.punkt_id=punkty_wypowiedzi.id LEFT JOIN projekty_etapy ON wypowiedzi.punkt_id=projekty_etapy.etap_id LEFT JOIN multidebaty ON wypowiedzi.punkt_id=multidebaty.id LEFT JOIN projekty ON projekty_etapy.projekt_id=projekty.id WHERE projekty_etapy.typ_id='2' AND wypowiedzi.id='$id'");
	  
	  $czytanie_typ = $wyp['multidebata_czytanie_typ'] ? $wyp['multidebata_czytanie_typ'] : $wyp['czytanie_typ'];
	  $debata_tytul = $wyp['multidebata_tytul'] ? $wyp['multidebata_tytul'] : $wyp['tytul'];
	  $multidebata_projekt_typ = $wyp['multidebata_projekt_typ'] ? $wyp['multidebata_projekt_typ'] : $wyp['projekt_typ'];
	  var_export($czytanie_typ);
	  var_export($debata_tytul);
	  var_export($multidebata_projekt_typ);
  
  }
  die();
  
  require_once(ROOT.'/_lib/Zend/Search/Lucene.php');
  $index = Zend_Search_Lucene::open(ROOT.'/_lucene/main_index');
  
  $doc = new Zend_Search_Lucene_Document();
  $doc->addField(Zend_Search_Lucene_Field::Text('url', $docUrl));
  $doc->addField(Zend_Search_Lucene_Field::UnStored('contents', $docContent));

  $index->addDocument($doc);
?>