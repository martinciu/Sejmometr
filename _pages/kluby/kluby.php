<?
  $this->addLib('google');
  
  $data = $this->DB->selectRows('SELECT druki_autorzy.autor, kluby.ilosc_poslow, kluby.srednia_wieku, kluby.udzial_kobiet, kluby.udzial_singli FROM kluby LEFT JOIN druki_autorzy ON kluby.id=druki_autorzy.id WHERE ilosc_poslow>0 ORDER BY ilosc_poslow DESC');
  
  foreach( $data as &$d ) {
    if( $d[0]=='Niezrzeszeni' ) $d[0] = '<span class="i">Niezrzeszeni</span>';
    $d[0] = "'".$d[0]."'";
  }

  $this->SMARTY->assign('data', $data);  
?>