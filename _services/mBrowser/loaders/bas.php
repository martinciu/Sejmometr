<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS id FROM bas WHERE $where ORDER BY data ASC, data_dodania ASC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>