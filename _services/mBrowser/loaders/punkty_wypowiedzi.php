<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "alert='1' OR akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS punkty_wypowiedzi.id, punkty_wypowiedzi.sejm_id FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE $where ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>