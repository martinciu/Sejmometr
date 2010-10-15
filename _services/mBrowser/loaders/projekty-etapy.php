<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "projekty.html_zmiana='0'"; }
  elseif( $category=='doakceptu' ) { $where = "projekty.html_zmiana='1'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS projekty.id, druki.numer FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.akcept='1' AND $where ORDER BY druki.data ASC, projekty.id ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>