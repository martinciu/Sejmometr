<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "druki_multi.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "druki_multi.akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS druki_multi.id, druki.numer FROM druki_multi LEFT JOIN druki ON druki_multi.id=druki.id WHERE $where ORDER BY druki.data DESC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>