<?  
  require_once(ROOT.'/_lib/SejmParser.php');
	$SP = new SejmParser();
	
	$ids = $this->DB->selectValues("SELECT id FROM `posiedzenia_dni` WHERE (status='1' AND TIMESTAMPDIFF(MINUTE, data_pobrania_modelu, NOW())>7)");
	if(is_array($ids)) foreach($ids as $id) {
	  $this->DB->q("DELETE FROM dni_modele WHERE dzien_id='$id'");
	  $this->DB->q("UPDATE posiedzenia_dni SET `status`='0', `data_pobrania_modelu`=NOW() WHERE id='$id'");
	}
	
	
  while( $id = $this->DB->selectValue("SELECT id FROM posiedzenia_dni WHERE status='0' ORDER BY data_dodania ASC LIMIT 1") ) {
 
	  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM posiedzenia_dni WHERE id='$id'");
	  $this->DB->q("UPDATE posiedzenia_dni SET `status`='1', `data_pobrania_modelu`=NOW() WHERE id='$id'");
	  
	  $model = $SP->posiedzenia_model_dnia($sejm_id);
	  $model_json = json_encode( $model );
	  $md5 = md5( $model_json );
	  $file = ROOT.'/graber_cache/dni/'.$id.'.json';
	  @unlink($file);
	  @file_put_contents($file, $model_json);
	  
	  
	  $iterator = 0;
	  if( is_array($model) ) foreach( $model as $item ) {



	    $iterator++;
	    $item['dzien_id'] = $id;
	    $item['sejm_id'] = $item['id'];
	    $item['ord'] = $iterator;
	    $item['status'] = '0';
	    $item['text'] = addslashes($item['text']);
	    $item['autor'] = addslashes($item['autor']);
	    unset($item['id']);
	    $this->DB->insert_assoc('dni_modele', $item);
	    
	    
	  }
	  
	  $this->DB->q("UPDATE posiedzenia_dni SET `status`='2', `data_pobrania_modelu`=NOW(), `md5`='".$md5."' WHERE id='$id'");
  
  }
?>