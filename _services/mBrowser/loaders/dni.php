<?
  $where = 1;
  if( $category=='bezproblemowe' ) { $where = "analiza_wystapienia='4' AND analiza_glosowania='4'"; }
  elseif( $category=='problemowe' ) { $where = "analiza_wystapienia!='4' OR analiza_glosowania!='4'"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS posiedzenia_dni.id, posiedzenia_dni.data FROM posiedzenia_dni WHERE ($where) ORDER BY posiedzenia_dni.data DESC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>