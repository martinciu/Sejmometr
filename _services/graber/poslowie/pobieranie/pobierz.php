<?
  $id = $_PARAMS;
  
  $this->DB->update_assoc('poslowie', array('status' => '1', 'data_sprawdzenia' => 'NOW()'), $id);
 
  
  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM poslowie WHERE id='$id'");
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();  
  $sejm_posel = $SP->posel_info($sejm_id);  
  if( $SP->response_status!=200 ) return false;
  
  die();
  
  foreach( $sejm_posel as $key=>$val ) {
    $this->DB->insert_assoc('poslowie_pola', array(
      'posel_id' => $id,
      'nazwa' => $key,
      'wartosc' => addslashes($val),
    ));
    $pole_id = $this->DB->insert_id;
    
    
    if( $key=='image_md5' ) {
      $file = ROOT.'/graber_cache/poslowie/avatary/'.$pole_id.'.jpg';
      @unlink( $file );
      @copy($val, $file);
    }
    
  }
  
  $this->DB->update_assoc('poslowie', array('status' => '2', 'data_sprawdzenia' => 'NOW()'), $id);
?>