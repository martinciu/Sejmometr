<?

  if( $category=='doakceptu' ) { $where = "akcept='0'"; }
  elseif( $category=='zaakceptowane' ) { $where = "akcept='1'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS id, nazwa FROM ludzie WHERE $where ORDER BY nazwa ASC LIMIT $limit_start, $per_page";
  
  
  
  $data = $this->DB->selectAssocs($q);
?>