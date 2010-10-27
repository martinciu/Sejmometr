<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "projekty.html_zmiana='0'"; }
  elseif( $category=='doakceptu' ) {
    $ids = $this->DB->selectValues("(SELECT id FROM projekty WHERE html_zmiana='1') UNION (SELECT projekt_id FROM projekty_druki WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_punkty_wypowiedzi WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_punkty_glosowania WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_wyroki WHERE przypisany='0')");
    $where = "(projekty.id='".implode("' OR projekty.id='", $ids)."')";
  }

  $q = "SELECT SQL_CALC_FOUND_ROWS projekty.id, druki.numer FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.akcept='1' AND $where ORDER BY druki.data ASC, projekty.id ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>