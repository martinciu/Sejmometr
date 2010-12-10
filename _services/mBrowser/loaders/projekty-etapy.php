<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "projekty.html_zmiana='0' AND projekty.alert='0'"; }
  elseif( $category=='doakceptu' ) {
    $ids = $this->DB->selectValues("(SELECT id FROM projekty WHERE html_zmiana='1' OR alert='1') UNION (SELECT projekt_id FROM projekty_druki WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_punkty_wypowiedzi WHERE przypisany='0') UNION (SELECT projekty_punkty_glosowania.projekt_id FROM projekty_punkty_glosowania LEFT JOIN punkty_glosowania ON projekty_punkty_glosowania.punkt_id=punkty_glosowania.id WHERE punkty_glosowania.aktywny='1' AND projekty_punkty_glosowania.przypisany='0') UNION (SELECT projekt_id FROM projekty_wyroki WHERE przypisany='0')");
    $where = "(projekty.id='".implode("' OR projekty.id='", $ids)."')";
  }

  $q = "SELECT SQL_CALC_FOUND_ROWS projekty.id, druki.numer FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.akcept='1' AND $where ORDER BY druki.data ASC, projekty.id ASC LIMIT $limit_start, $per_page";
  
  // echo $q;
  
  $data = $this->DB->selectAssocs($q);
?>