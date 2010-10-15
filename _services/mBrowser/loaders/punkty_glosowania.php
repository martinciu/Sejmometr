<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS punkty_glosowania.id, punkty_glosowania.sejm_id FROM punkty_glosowania LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id WHERE $where ORDER BY posiedzenia_dni.data ASC, punkty_glosowania.ord ASC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>