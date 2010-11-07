<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "wypowiedzi.akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "wypowiedzi.akcept='0'"; }
  elseif( $category=='problemy' ) { $where = "(wypowiedzi.`_r`!='0') AND (wypowiedzi.`_r`!='5') AND (wypowiedzi.`_r`!='4')"; }
  elseif( $category=='bezautora' ) { $where = "(wypowiedzi.`_r`='0') AND (wypowiedzi.`typ`='1')"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS wypowiedzi.id FROM wypowiedzi LEFT JOIN posiedzenia_dni ON wypowiedzi.dzien_id=posiedzenia_dni.id WHERE wypowiedzi.typ='1' AND $where ORDER BY posiedzenia_dni.data ASC, wypowiedzi.ord  ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>