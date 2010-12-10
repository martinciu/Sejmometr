<?
  list( $czlowiek_id, $avatar_id, $x, $y, $z ) = $_PARAMS;
    
  $src_file = ROOT.'/resources/ludzie/avatary/'.$avatar_id.'.jpg';
  $target_file = ROOT.'/l/0/'.$czlowiek_id.'.jpg';
  
  $src_size = getimagesize( $src_file );
  $src_img = imagecreatefromjpeg( $src_file );
  $target_img = imagecreatetruecolor(110, 140);
  
  $src_x = $src_size[0]/2 - $x;
  $src_y = $src_size[1]/2 - $y;
  
  if( $src_size[0]>$src_size[1] ) {
    $src_w = $src_size[0];
    $src_h = $src_size[0] * 14 / 11;
  } else {
    $src_h = $src_size[1];
    $src_w = $src_size[1] * 11 / 14;
  }
  
  $src_w = 2 * $z * $src_w;
  $src_h = 2 * $z * $src_h;
  
  echo "$src_w $src_h\n";
  
  imagecopyresampled( $target_img, $src_img, 0, 0, $src_x, $src_y, 110, 140, $src_w, $src_h );
  
  @unlink( $target_file );
  imagejpeg( $target_img, $target_file, 90 );






  
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
  
  make_thumb( &$target_img, $czlowiek_id, '1', 55, 70, 10, 5, 90, 115 );
  make_thumb( &$target_img, $czlowiek_id, '2', 50, 50, 17, 17, 93, 93 );
  make_thumb( &$target_img, $czlowiek_id, '3', 33, 33, 19, 25, 80, 80 );
?>