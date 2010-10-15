<?
  require_once(ROOT.'/_lib/scribd.php');
	$scribd = new Scribd($scribd_api_key, $scribd_secret); 
  
  $items = $this->DB->selectRows("SELECT id, scribd_doc_id FROM dokumenty WHERE scribd='3'");
  $result = 0;
  
  for( $i=0; $i<count($items); $i++ ) {
  	
  	$id = $items[$i][0];
  	$doc_id = $items[$i][1];
  	
  	$result++;
  	try{
		  switch( $scribd->getConversionStatus($doc_id) ) {
		  
		    case 'DONE': {
		      $data = $scribd->getSettings($doc_id);
		      $ilosc_stron = (int) $data['page_count'];
		      $this->DB->q("UPDATE dokumenty SET `scribd`='5', `data_scribd`=NOW(), `ilosc_stron`='$ilosc_stron', `data_scribd`=NOW() WHERE `id`='$id'");
		      $this->S('liczniki/nastaw/dokumenty_obrabiane');
		      break;
		    }
		    
		    case 'PROCESSING': { break; }
		    
		    default: {
		      $this->DB->q("UPDATE dokumenty SET `scribd`='4', `data_scribd`=NOW() WHERE `id`='$id'");
		      break;
		    }
		  
		  }
	  }catch(Exception $e){
	    var_export($e);
	    echo '<hr/>';
	  }
	  
	}
  
  return $this->S('graber/dokumenty/log', array('scribd sprawdzanie', $result));
?>