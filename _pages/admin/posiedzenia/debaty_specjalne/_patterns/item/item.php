<?
  $id = $_PARAMS['id'];
  
  $debata = $this->DB->selectAssoc("SELECT debaty_specjalne.id, debaty_specjalne.tytul, debaty_specjalne.akcept, punkty_wypowiedzi.sejm_id, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia_dni.data FROM debaty_specjalne JOIN punkty_wypowiedzi ON debaty_specjalne.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE debaty_specjalne.id='$id'");
  
  list($dzien_id, $min_ord) = $this->DB->selectRow("SELECT dzien_id, MIN(ord) FROM `wypowiedzi` WHERE `punkt_id`='$id'");
  $text = $this->DB->selectValue("SELECT text FROM wypowiedzi WHERE dzien_id='$dzien_id' AND typ='2' AND ord<$min_ord ORDER BY ord DESC LIMIT 1");
  
  
  $result = array(
    'item' => $debata,
    'text' => $text,
  );
      
  return $result;
?>