<?  
	$na_strone = 15;
  $strony_wychylenie = 7;
    
  $strona = (int) $_GET['str'];
  if( $strona<1 ) $strona=1;
  
  $_str = $strona-1;
  $_limit_start = $_str*$na_strone;
  
  
  $q = "SELECT SQL_CALC_FOUND_ROWS informacje_biezace.id, informacje_biezace.tytul, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi, punkty_wypowiedzi.opis, posiedzenia_dni.posiedzenie_id, druki_autorzy.autor FROM informacje_biezace JOIN punkty_wypowiedzi ON informacje_biezace.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id LEFT JOIN druki_autorzy ON informacje_biezace.klub_id=druki_autorzy.id WHERE informacje_biezace.akcept='1' ORDER BY posiedzenia_dni.data DESC LIMIT $_limit_start, $na_strone";
  
  $items = $this->DB->selectAssocs($q);
  
  $total = $this->DB->found_rows();
  $ilosc_stron = ceil($total/$na_strone);
  $strony_start = max(1, $strona - $strony_wychylenie+1);
  $elementy_start = $na_strone*($_str)+1;
  
  $pag = array(
    'total' => $total,
    'strona' => $strona,
    'na_strone' => $na_strone,
    'ilosc_stron' => $ilosc_stron,
    'strony_start' => $strony_start,
    'strony_koniec' => min($ilosc_stron+1, $strona + $strony_wychylenie),
    'elementy_start' => $elementy_start,
    'elementy_koniec' => min($elementy_start+$na_strone-1, $total),
  );  
  
  $this->SMARTY->assign('pag', $pag);
  $this->SMARTY->assign('debaty', $items);
?>