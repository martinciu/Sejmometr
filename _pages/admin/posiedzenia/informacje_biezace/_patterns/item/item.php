<?
  $id = $_PARAMS['id'];
  
  $debata = $this->DB->selectAssoc("SELECT informacje_biezace.id, informacje_biezace.tytul, informacje_biezace.akcept, punkty_wypowiedzi.sejm_id, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia_dni.data, informacje_biezace.klub_id FROM informacje_biezace JOIN punkty_wypowiedzi ON informacje_biezace.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE informacje_biezace.id='$id'");
  
  list($dzien_id, $min_ord) = $this->DB->selectRow("SELECT dzien_id, MIN(ord) FROM `wypowiedzi` WHERE `punkt_id`='$id'");
  $text = $this->DB->selectValue("SELECT text FROM wypowiedzi WHERE dzien_id='$dzien_id' AND typ='2' AND ord<$min_ord ORDER BY ord DESC LIMIT 1");
  
  $funkcje = $this->DB->selectValues("SELECT DISTINCT(wypowiedzi_funkcje.nazwa) FROM wypowiedzi JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id WHERE wypowiedzi.punkt_id='$id' AND wypowiedzi_funkcje.klub='0'");
  
  
  $result = array(
    'item' => $debata,
    'text' => $text,
    'funkcje' => $funkcje,
  );
      
  return $result;
?>