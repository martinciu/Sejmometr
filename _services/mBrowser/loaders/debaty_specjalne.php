<?
  if( $category=='zaakceptowane' ) { $where = "debaty_specjalne.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "debaty_specjalne.akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS debaty_specjalne.tytul, debaty_specjalne.id FROM debaty_specjalne JOIN punkty_wypowiedzi ON debaty_specjalne.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE $where ORDER BY posiedzenia_dni.data ASC LIMIT $limit_start, $per_page";
  
  $data = $this->DB->selectAssocs($q);
?>