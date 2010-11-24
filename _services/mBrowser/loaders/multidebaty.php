<?
  if( $category=='zaakceptowane' ) { $where = "multidebaty.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "multidebaty.akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS multidebaty.tytul, multidebaty.id FROM multidebaty JOIN punkty_wypowiedzi ON multidebaty.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE $where ORDER BY posiedzenia_dni.data ASC LIMIT $limit_start, $per_page";
  
  $data = $this->DB->selectAssocs($q);
?>