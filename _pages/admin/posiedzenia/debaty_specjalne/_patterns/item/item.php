<?
  $id = $_PARAMS['id'];
  
  $debata = $this->DB->selectAssoc("SELECT debaty_specjalne.id, debaty_specjalne.tytul, debaty_specjalne.akcept, punkty_wypowiedzi.sejm_id, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia_dni.data FROM debaty_specjalne JOIN punkty_wypowiedzi ON debaty_specjalne.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE debaty_specjalne.id='$id'");
  
  $result = array(
    'item' => $debata,
  );
      
  return $result;
?>