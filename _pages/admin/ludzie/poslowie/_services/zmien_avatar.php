<?
  $id = $_PARAMS;
  
  $img_id = $this->DB->selectValue("SELECT id FROM poslowie_pola WHERE posel_id='$id' AND nazwa='image_md5' ORDER BY id DESC LIMIT 1");
  $img_file = ROOT.'/graber_cache/poslowie/avatary/'.$img_id.'.jpg';
  
  if( file_exists($img_file) ) {  
    
    
    if( !function_exists('make_thumb') ) {
      function make_thumb($master, $id, $typ, $width, $height, $left, $top, $master_width, $master_height){
	      list( $_left, $_top ) = $_SERVER['DB']->selectRow("SELECT `left`, `top` FROM ludzie_avatary WHERE `czlowiek_id`='$id' AND `typ`='$typ' LIMIT 1");
	      if( !empty($_left) && !empty($_top) ) {
	        $left = $_left;
	        $top = $_top;
	      }
	      $thumb = imagecreatetruecolor($width, $height);
		    imagecopyresampled( $thumb, $master, 0, 0, $left, $top, $width, $height, $master_width, $master_height );
		    $file = ROOT.'/l/'.$typ.'/'.$id.'.jpg';
		    @unlink( $file );
		    imagejpeg( $thumb, $file, 90 );
		    imagedestroy( $thumb );
		    $_SERVER['DB']->q("INSERT INTO ludzie_avatary (`czlowiek_id`, `typ`, `left`, `top`) VALUES ('$id', '$typ', $left, $top) ON DUPLICATE KEY UPDATE `left`=$left, `top`=$top");
      }
    }
    
    try{
      
	    $source = imagecreatefromjpeg($img_file);
	    $master = imagecreatetruecolor(110, 140);
	    imagecopyresized($master, $source, 0, 0, 1, 1, 110, 140, 110, 140);
	    imagedestroy($source);
	    
	    $master_file = ROOT.'/l/0/'.$id.'.jpg';
	    @unlink($master_file);
	    imagejpeg($master, $master_file, 90);
	 
	    
	    
	    make_thumb( &$master, $id, '1', 55, 70, 10, 5, 90, 115 );
	    make_thumb( &$master, $id, '2', 50, 50, 17, 17, 93, 93 );
	    make_thumb( &$master, $id, '3', 33, 33, 19, 25, 80, 80 );
	        
	    imagedestroy($master);	    
	    $this->DB->q("UPDATE ludzie SET avatar='1' WHERE id='$id'");
	    
    } catch(Exception $e) { return false; }
    
    return true;
  } else return false;
?>