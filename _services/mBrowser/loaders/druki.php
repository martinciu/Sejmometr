<?
  $where = 1;
  
  if( $category=='powtorzone_projekty' ) {
  
    $q = "SELECT SQL_CALC_FOUND_ROWS druki.id, druki.numer FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.druk_id!='' GROUP BY projekty.druk_id HAVING COUNT(*)>1";
  
  } else {
  
	  if( $category=='zaakceptowane' ) { $where = "akcept='1'"; }
	  elseif( $category=='doakceptu' ) { $where = "akcept='0'"; }
	  elseif( $category=='nieprzypisane' ) { $where = "akcept='1' AND przypisany='0' AND typ_id!=13"; }
	
	  $q = "SELECT SQL_CALC_FOUND_ROWS id, numer FROM druki WHERE $where AND ilosc_dokumentow!=0 ORDER BY data ASC LIMIT $limit_start, $per_page";
  
  }
  
  
  $data = $this->DB->selectAssocs($q);
?>