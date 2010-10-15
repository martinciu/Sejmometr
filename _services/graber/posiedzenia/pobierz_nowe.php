<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  $session_data = $this->S('graber/session_start', 'posiedzenia/pobierz_nowe');
  
  $result = array('posiedzenia'=>0, 'dni'=>0);
  
  // pobieramy listę posiedzeń
  $lista = $SP->posiedzenia_lista(); 
  $numery_w_bazie = $this->DB->selectValues("SELECT numer FROM posiedzenia");
  $nowe_posiedzenia = array();
  
  if( is_array($lista) ) foreach( $lista as $posiedzenie ) {
    if( !in_array($posiedzenie['numer'], $numery_w_bazie) ) $nowe_posiedzenia[] = $posiedzenie;
  }
  $result['posiedzenia'] = count($nowe_posiedzenia);
  
  
  // pobieramy dni nowych posiedzeń
  foreach( $nowe_posiedzenia as $posiedzenie) {
    $posiedzenie_id = $this->DB->insert_assoc_create_id('posiedzenia', array('numer'=>$posiedzenie['numer']));
    
    $lista = $SP->posiedzenia_dni_lista( $posiedzenie['temp_id'] );
    if( is_array($lista) ) foreach($lista as $dzien) {
      $result['dni']++;
      
      $this->DB->insert_assoc_create_id('posiedzenia_dni', array(
        'posiedzenie_id' => $posiedzenie_id,
        'data' => $dzien['data'],
        'sejm_id' => $dzien['dzien_id'],
      ));
      
    }
  }
  
  $session_data[] = $result;
  $this->S('graber/session_end', $session_data);
?>