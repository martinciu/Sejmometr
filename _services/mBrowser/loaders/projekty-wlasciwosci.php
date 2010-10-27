<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "projekty.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "projekty.akcept='0'"; }
  elseif( $category=='skasowane' ) { $where = "projekty.response_status!=200"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS projekty.id, druki.numer FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE $where ORDER BY druki.data ASC, projekty.id ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>