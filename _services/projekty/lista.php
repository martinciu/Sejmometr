<?
  $typ_ids = $_PARAMS['typ_ids'];
  $filtry = $_PARAMS['filtry'];
  $s = $_PARAMS['sort'];
  $miesiac = $_PARAMS['miesiac'];
  
  $_fields = array(
    'autor' => 'autor_id',
    'status' => 'status',
  );
  
  $w = "projekty.akcept='1' AND projekty.status!=0 AND (projekty.typ_id=".implode(" OR projekty.typ_id=", $typ_ids).")";
    

   
  if( is_array($filtry) ) foreach( $filtry as $k=>$v ) {
    $field = $_fields[$k];
    $w .= " AND (projekty.$field='".implode("' OR projekty.$field='", $v)."')";
  }
  
  if( strlen($miesiac)==7 ) $w .= " AND SUBSTRING(projekty.data_wplynal, 1, 7)='$miesiac'";
  
  $na_strone = 15;
  $strony_wychylenie = 7;
    
  $strona = (int) $_GET['str'];
  if( $strona<=0 ) $strona=1;
  
  $_str = $strona-1;
  $_limit_start = $_str*$na_strone;
  
  $q = "SELECT SQL_CALC_FOUND_ROWS projekty.id, druki.dokument_id, druki_autorzy.autor, projekty.tytul, projekty.opis, projekty.status_slowny, projekty.data_wplynal FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE $w ORDER BY $s LIMIT $_limit_start, $na_strone";
  $projekty = $this->DB->selectAssocs($q);
  
  $total = $this->DB->found_rows();
  $ilosc_stron = ceil($total/$na_strone);
  $strony_start = max(1, $strona - $strony_wychylenie+1);
  $elementy_start = $na_strone*($_str)+1;
  
  $paginacja = array(
    'total' => $total,
    'strona' => $strona,
    'na_strone' => $na_strone,
    'ilosc_stron' => $ilosc_stron,
    'strony_start' => $strony_start,
    'strony_koniec' => min($ilosc_stron+1, $strona + $strony_wychylenie),
    'elementy_start' => $elementy_start,
    'elementy_koniec' => min($elementy_start+$na_strone-1, $total),
  );
    
  return array($projekty, $paginacja);
?>