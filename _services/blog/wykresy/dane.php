<?
  switch( $_PARAMS ) {
    case 'ofensywa': return json_decode( file_get_contents( ROOT.'/data/ofensywa.json' ) );
    case 'kluby': {
      $data = $this->DB->selectRows('SELECT kluby.id, kluby.ilosc_poslow, kluby.srednia_wieku, kluby.udzial_kobiet, kluby.udzial_singli FROM kluby LEFT JOIN druki_autorzy ON kluby.id=druki_autorzy.id WHERE ilosc_poslow>0 ORDER BY ilosc_poslow DESC');
		  foreach( $data as &$d ) if( $d[0]=='NIEZ' ) $d[0] = '<span class="i">Niezrzeszeni</span>';
		  return $data;
    }
  }  
?>