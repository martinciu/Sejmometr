<?
  $where = 1;
  if( $category=='bezproblemowe' ) { $where = "(status='0' OR status='2') AND (analiza_wystapienia='0' OR analiza_wystapienia='4')"; }
  elseif( $category=='problemowe' ) { $where = "status='1' OR analiza_wystapienia='1' OR analiza_wystapienia='2' OR analiza_wystapienia='3' OR analiza_wystapienia='5'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS posiedzenia_dni.id, posiedzenia_dni.data FROM posiedzenia_dni WHERE ($where) ORDER BY posiedzenia_dni.data DESC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>