<?
  $this->addLib('google');
  $file = ROOT.'/data/ofensywa.json';
  
  
  if( $_GET['refresh_data'] ) {
	  $data = array();
	  $db_data = $this->DB->selectPairs("SELECT SUBSTRING(data_wplynal, 1, 7) as 'miesiac', COUNT(*) as ilosc FROM projekty WHERE typ_id=1 AND (autor_id='Rzad') GROUP BY miesiac");
	  
	  $start = 2007*12+10;
	  $y = (int) date('Y');
	  $m = (int) date('n');
	  $m--; 
	  $end = $y*12+$m;
	  
	  for( $i=$start; $i<=$end; $i++ ) {
	    $m = $i % 12;
	    $y = ($i-$m) / 12;
	    $m++;
	    if( $m<10 ) $m = '0'.$m;
	    
	    $m = $y.'-'.$m;
	    
	    $data[] = array( $m, (int) $db_data[$m][0] );
	  }
	  
	  @unlink($file);
	  file_put_contents($file, json_encode($data));
  }
  
  
  $data = json_decode(file_get_contents($file));
  $this->SMARTY->assign('data', $data);
?>