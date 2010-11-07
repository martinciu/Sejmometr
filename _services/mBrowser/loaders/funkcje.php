<?
  $q = "SELECT SQL_CALC_FOUND_ROWS id, autor FROM wypowiedzi_nierozpoznani_autorzy ORDER BY autor ASC LIMIT $limit_start, $per_page";
    
  $data = $this->DB->selectAssocs($q);
?>