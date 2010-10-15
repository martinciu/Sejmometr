<?
  $where = 1;
  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
  elseif( $category=='doakceptu' ) { $where = "akcept='0'"; }
  elseif( $category=='nieprzypisane' ) { $where = "przypisany='0' AND zalacznik='0' AND typ_id!=13"; }

  $q = "SELECT SQL_CALC_FOUND_ROWS id, numer FROM druki WHERE $where ORDER BY data DESC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>