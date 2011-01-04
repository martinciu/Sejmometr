<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  list($sejm_id, $typ) = $this->DB->selectRow("SELECT sejm_id, typ FROM interpelacje WHERE id='$id'");
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $data = $SP->interpelacje_info( $sejm_id, $typ );
  if( $SP->response_status==200 ) {
    
    $pola = $data['pola'];
    
    var_export($pola);
    die();
    
    $klucze = array();
    foreach( $pola as $p ) $klucze[] = $p[0];
    asort($klucze);
    $klucze_string = implode(',', $klucze);
    
    $interpelacja = array(
      'nr' => $data['nr'],
      'tytul' => $data['tytul'],
    );
    
    if( $klucze_string=='adresat,adresat text id,data ogłoszenia,data wpływu,data wpływu,nr posiedzenia ogłoszenia,odpowiedź data ogłoszenia,odpowiedź nr posiedzenia ogłoszenia,odpowiedź odpowiadający,odpowiedź odpowiadający text id,zgłaszający' ) {
      
      // $interpelacja['']
      
    }
    
    
      
  }
?>