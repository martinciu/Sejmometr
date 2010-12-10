<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $numer = $this->DB->selectValue("SELECT numer FROM posiedzenia WHERE id='$id'");
  
  $lista = $SP->posiedzenia_lista();
  if( is_array($lista) ) foreach($lista as $i) {
    if( $i['numer']==$numer ) {
      $temp_id = $i['temp_id'];
    }
  }
  
  if( !$temp_id ) return false;
  
  $lista = $SP->posiedzenia_dni_lista( $temp_id );  
  
  
  if( !is_array($lista) ) return false;
    
  $sejm_ids = array();
  $_daty = array();
  foreach( $lista as $i ) {
    $sejm_ids[] = $i['dzien_id'];
    $_daty[ $i['dzien_id'] ] = $i['data'];
  }
  $db_ids = $this->DB->selectValues("SELECT sejm_id FROM posiedzenia_dni WHERE posiedzenie_id='$id'");
  
  $dni_utrzymane = array_intersect($sejm_ids, $db_ids);
  $dni_dodane = array_diff($sejm_ids, $db_ids);
  $dni_skasowane = array_diff($db_ids, $sejm_ids);
  

  
  
  
  // DNI UTRZYMANE
  if( is_array( $dni_utrzymane ) ) foreach( $dni_utrzymane as $_id ) {
    
    list($dzien_id, $dbmd5) = $this->DB->selectRow("SELECT id, md5 FROM posiedzenia_dni WHERE sejm_id='$_id'");
    $model = $SP->posiedzenia_model_dnia( $_id );
    
    if( $SP->response_status==200 ) {
		  $model_json = json_encode( $model );
		  $md5 = md5($model_json);
		  
		  $file = ROOT.'/graber_cache/dni/'.$dzien_id.'.json';
		  
		  if( $md5!=$dbmd5 ) {
		    @unlink($file);
		    @file_put_contents($file, $model_json);
		    $this->DB->q("UPDATE posiedzenia_dni SET `data_pobrania_modelu`=NOW(), `md5`='".$md5."', analiza_wystapienia='7' WHERE id='$dzien_id'");
		  }
    } else $this->DB->q("INSERT INTO graber_posiedzenia_nieudane_sesje (`dzien_id`, `status`) VALUES ('$dzien_id', ".$SP->response_status.")");
    
  }
  
 

  // DNI DODANE
  if( is_array($dni_dodane) ) foreach($dni_dodane as $_id) {            
    $this->DB->insert_assoc_create_id('posiedzenia_dni', array(
      'posiedzenie_id' => $id,
      'data' => $_daty[$_id],
      'sejm_id' => $_id,
    ));
  }
  
  
  
  // DNI SKASOWANE
  if( is_array($dni_skasowane) ) foreach($dni_skasowane as $_id) {
    $dzien_id = $this->DB->selectValue("SELECT id FROM posiedzenia_dni WHERE sejm_id='$_id'");
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'6'), $dzien_id);
  }
  
  $this->DB->update_assoc('posiedzenia', array(
    'data_sprawdzenia' => 'NOW()',
  ), $id);
  $this->S('liczniki/nastaw/dni');
?>