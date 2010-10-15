<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $images = true;
  $folders = array('0', '1', '2', '3', '4', '5');
  foreach( $folders as $folder ) {
    $file = ROOT.'/d/'.$folder.'/'.$id.'.gif';
    $images = $images && file_exists($file);
    $images = $images && ( filesize($file)>0 );
  }
  
  
  
  
  if( !$images ) {
  
    $this->DB->update_assoc('dokumenty', array(
	   'obraz' => '1',
	   'akcept' => '0',
	   '_kontrola' => 'NOW()',
	  ), $id);
	  $S('liczniki/nastaw/dokumenty_obrabiane');
  
  } else {
    
    $this->DB->update_assoc('dokumenty', array(
	   '_kontrola' => 'NOW()',
	  ), $id);
	  
  }
?>