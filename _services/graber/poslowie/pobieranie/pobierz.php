<?
  $id = $_PARAMS;
  
  // $this->DB->update_assoc('poslowie', array('status' => '1', 'data_sprawdzenia' => 'NOW()'), $id);
 
  
  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM poslowie WHERE id='$id'");
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();  
  $sejm_posel = $SP->posel_info($sejm_id);  
  if( $SP->response_status!=200 ) return false;
  
  $sejm_pola = array_keys( $sejm_posel );
  
  $db_posel = array();
  $_db_posel = $this->DB->selectRows("SELECT nazwa, wartosc FROM poslowie_pola WHERE posel_id='$id' GROUP BY nazwa ORDER BY id DESC");
  foreach( $_db_posel as $row ) $db_posel[ $row[0] ] = $row[1];
  
  
  $sejm_keys = array_keys($sejm_posel);
  $db_keys = array_keys($db_posel);
  
  $new_keys = array_diff($sejm_keys, $db_keys);
  $existing_keys = array_intersect($sejm_keys, $db_keys);
  $removed_keys = array_diff($db_keys, $sejm_keys);
  
  $modified_keys = array();
  foreach($existing_keys as $key) {
    if( $sejm_posel[$key] != $db_posel[$key] ) $modified_keys[] = $key;
  }
  
  
    
  $params = array(
    'status' => '2',
    'data_sprawdzenia' => 'NOW()',
  );
  
  foreach( $new_keys as $key ) {
    $this->DB->insert_assoc('poslowie_pola', array(
      'posel_id' => $id,
      'nazwa' => $key,
      'wartosc' => addslashes( $sejm_posel[$key] ),
      'typ' => 'A',
    ));
    $pole_id = $this->DB->insert_id;    
    if( $key=='image_md5' ) {
      $file = ROOT.'/graber_cache/poslowie/avatary/'.$pole_id.'.jpg';
      @unlink( $file );
      @copy($val, $file);
    }
    
  }
  
  foreach( $modified_keys as $key ) {
    $this->DB->insert_assoc('poslowie_pola', array(
      'posel_id' => $id,
      'nazwa' => $key,
      'wartosc' => addslashes( $sejm_posel[$key] ),
      'typ' => 'M',
    ));
    $pole_id = $this->DB->insert_id;    
    if( $key=='image_md5' ) {
      $file = ROOT.'/graber_cache/poslowie/avatary/'.$pole_id.'.jpg';
      @unlink( $file );
      @copy($val, $file);
    }
    
  }
  
  foreach( $removed_keys as $key ) {
    $this->DB->insert_assoc('poslowie_pola', array(
      'posel_id' => $id,
      'nazwa' => $key,
      'typ' => 'D',
    ));
    $pole_id = $this->DB->insert_id;
  }
  
  if( count($new_keys) || count($modified_keys) || count($removed_keys) ) $params['update'] = '1';
  
  $this->DB->update_assoc('poslowie', $params, $id);
  if(  $params['update']=='1' ) $this->S('liczniki/nastaw/poslowie');
?>