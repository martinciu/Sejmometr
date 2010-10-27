<?
  $where = 1;
  


  if( $category=='zaakceptowane' ) { $where = "projekty_teksty.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "projekty_teksty.akcept='0'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS projekty_teksty.id, druki.numer FROM projekty_teksty LEFT JOIN druki ON projekty_teksty.id=druki.id WHERE $where ORDER BY druki.data_dodania DESC LIMIT $limit_start, $per_page";
  
  
  
  $data = $this->DB->selectAssocs($q);
?>