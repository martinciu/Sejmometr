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
    $sejm_model_count = count($SP->posiedzenia_model_dnia($_id));
    $__id = $this->DB->selectValue("SELECT id FROM posiedzenia_dni WHERE sejm_id='$_id'");
    $db_model_count = $this->DB->selectCount("SELECT COUNT(*) FROM `dni_modele` WHERE dzien_id='$__id'");
    if( $sejm_model_count!=$db_model_count ) {
      echo 'analiza - 7';
      $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'7'), $__id);
    }
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
    $this->DB->update_assoc('posiedzenia_dni', array('analiza_wystapienia'=>'6'), $_id);
  }
  
  
  $this->S('liczniki/nastaw/dni');
?>