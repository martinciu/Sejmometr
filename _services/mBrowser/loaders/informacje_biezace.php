<?
  if( $category=='zaakceptowane' ) { $where = "informacje_biezace.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "informacje_biezace.akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS informacje_biezace.tytul, informacje_biezace.id FROM informacje_biezace JOIN punkty_wypowiedzi ON informacje_biezace.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE $where ORDER BY posiedzenia_dni.data ASC LIMIT $limit_start, $per_page";
  
  $data = $this->DB->selectAssocs($q);
?>