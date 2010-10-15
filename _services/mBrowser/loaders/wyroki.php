<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS id, numer FROM wyroki_tk WHERE $where ORDER BY data_wydania ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>