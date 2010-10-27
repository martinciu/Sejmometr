<?
	require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  if(!function_exists('__strip_html')){
	  function __strip_html($s){
	    preg_match('/Stan na ([0-9]{2})-([0-9]{2})-([0-9]{4})/i', $s, $m);
	    $s = str_replace($m[0], '', $s);    
	    preg_match_all('/\<\!-(.*?)-\>/', $s, $m);
	    foreach( $m as $i=>$n ){ $s = str_replace($n,'',$s); }
	    $s = strip_tags($s);
	    $s = str_replace(' ', '', $s);
	    $s = str_replace('&nbsp;', '', $s);
	    return $s;
	  }
  }

  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  
  
  
  
  
  
  list($sejm_id, $druk_id) = $this->DB->selectRow("SELECT sejm_id, druk_id FROM projekty WHERE id='$id'");
  
  if( empty($sejm_id) ) return false;
  
  $etapy_count = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_etapy WHERE projekt_id='$id'");
  $this->DB->update_assoc('projekty', array('response_status'=>0), $id);
  
  $projekt = $SP->projekt_info($sejm_id);
  $response_status = $SP->response_status;
  
  $this->DB->update_assoc('projekty', array('response_status'=>$response_status), $id);
  
  if( $response_status==404 ) {
    
    if( $druk_id=='' && $etapy_count==0 ) $this->S('projekty/usun', $id);
    return false;

  } elseif( $response_status!=200 ) return false;
  
    
  // DRUKI
  $this->S('projekty/dodaj_druki', array($id, $projekt['DRUKI']));
    
  
  
  
  
  
  
  
  // SPRAWDZANIE DUPLIKATÓW
  
  $glowny_druk_id = $this->DB->selectValue("SELECT druk_id FROM projekty_druki WHERE projekt_id='$id' ORDER BY id ASC");
  
  if( empty($glowny_druk_id) ) {
  
    $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.sejm_id, projekty.tytul, projekty.autor_id, projekty.druk_id, projekty.response_status FROM projekty_specjalne LEFT JOIN projekty ON projekty_specjalne.id=projekty.id WHERE projekty_specjalne.tytul='".$projekt['tytul']."' AND projekty.id!='$id'");
  
  } else {        
    $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.sejm_id, projekty.tytul, projekty.autor_id, projekty.druk_id, projekty.response_status FROM projekty_druki LEFT JOIN projekty ON projekty_druki.projekt_id=projekty.id WHERE projekty_druki.druk_id='$glowny_druk_id' AND projekty.id!='$id'");
    
    $_projekty = array();
    foreach( $projekty as $i=>$_projekt ) {      
      if( $_projekt['druk_id']==$glowny_druk_id ) $_projekty[] = $_projekt;
    }
    $projekty = $_projekty;
  }
      

  if( count($projekty)==1 ) {
        
    $_projekt = $projekty[0];
    
    if( $etapy_count==0 && $response_status==200 && $_projekt['response_status']==404 ) {
                
      $this->S('projekty/usun', $id);
      $this->DB->update_assoc('projekty', array(
        'sejm_id' => $sejm_id,
        'response_status' => 200,
      ), $_projekt['id']);
      
      $this->S('liczniki/nastaw/projekty');
      return false;
    
    }
    
  }
    
  
  
  
  
  
  
  
  
  
  // BAS
  $BAS = $projekt['BAS'];
    
  if(is_array($BAS)) foreach( $BAS as $item ) {
    
    $bas = array(
      'sejm_id' => $item['id'],
      'tytul' => $item['tytul'],
    );
    
    $bas_id = $this->DB->selectValue("SELECT id FROM bas WHERE sejm_id='".$item['id']."'");
    
    if( empty($bas_id) ) {
	    $bas_id = $this->DB->insert_assoc_create_id('bas', $bas);
	    $pathparts = pathinfo($item['plik']);
      $plik_data = array(
        'typ' => 'bas',
        'gid' => $bas_id,
        'download_url' => $item['download_url'],
        'plik' => $item['plik'],
        'plik_rozszerzenie' => strtolower( $pathparts['extension'] ),
      );
      $count = $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty WHERE (typ='bas' AND plik='".$item['plik']."')");
      if( $count==0 ) {
	      $dokument_id = $this->DB->insert_assoc_create_id('dokumenty', $plik_data);
        $this->DB->q("UPDATE bas SET `dokument_id`='$dokument_id' WHERE `id`='$bas_id'");
      } else {
        $this->DB->insert_ignore_assoc('dokumenty_problemy', $plik_data);
      }
	  }
	  
	  $this->DB->insert_ignore_assoc('projekty_bas', array(
	    'projekt_id' => $id,
	    'bas_id' => $bas_id,
	  ));
	  if( $this->DB->affected_rows ) $this->S('projekty/policz_opinie', $id);
	  
  }
  
  if( count($BAS) ) $this->S('liczniki/nastaw/bas'); 
  
 
 
 
 
 
 
 
 
	// ISAP
	$ISAP = $projekt['ISAP'];
	if( is_array($ISAP) ) foreach( $ISAP as $item ) {
	  $isap_id = $this->DB->selectValue("SELECT id FROM isap WHERE sejm_id='$item'");
	  $wyrok_id = $this->DB->selectValue("SELECT id FROM wyroki_tk WHERE sejm_id='$item'");
	  
	  if( empty($isap_id) && empty($wyrok_id) ) {
	    $isap_id = $this->DB->insert_assoc_create_id('isap', array(
	      'sejm_id' => $item,
	    ));
	  }
	  
	  if( !empty($isap_id) ) {
		  $this->DB->insert_ignore_assoc('projekty_isap', array(
		    'projekt_id' => $id,
		    'isap_id' => $isap_id,
		  ));
	  }
	}
      
   
   
  
           
  // CACHE FILE
  $file = ROOT.'/graber_cache/projekty/'.$id.'.html';
  
  $html_zmiana = true;
  if( file_exists($file) ) {
    if( __strip_html(file_get_contents($file)) == __strip_html($projekt['txt'])  ) $html_zmiana = false;
    unlink($file);
  }
  file_put_contents($file, $projekt['txt']);
  
  $data = array(
    'data_sprawdzenia' => 'NOW()',
  );
        
  if( $html_zmiana ) {
    $data['html_zmiana'] = '1';
    $data['html_zmiana_data'] = 'NOW()';
  }
    
  $this->DB->update_assoc('projekty', $data, $id);
  $this->S('liczniki/nastaw/projekty');
?>