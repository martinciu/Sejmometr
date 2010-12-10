<?
  list( $id, $typ, $direction ) = $_PARAMS;
  $typ = (int) $typ;
  $_allowed_directions = array('up', 'down', 'left', 'right');
  if( !in_array($direction, $_allowed_directions) || $typ<=0 || $id=='' ) return false;
  
  $master_file = ROOT.'/l/0/'.$id.'.jpg';
  if( !file_exists($master_file) ) return false;
  
  $master = imagecreatefromjpeg( $master_file );
  
  
  $params = array(
    1 => array(10,5,55,70,90,115,2),
    2 => array(17,17,50,50,93,93,2),
    3 => array(19,25,33,33,80,80,1),
  );
  list( $left, $top, $width, $height, $master_width, $master_height, $delta ) = $params[$typ];
  list( $_left, $_top ) = $_SERVER['DB']->selectRow("SELECT `left`, `top` FROM ludzie_avatary WHERE `czlowiek_id`='$id' AND `typ`='$typ' LIMIT 1");
  if( !empty($_left) && !empty($_top) ) {
    $left = $_left;
    $top = $_top;
  }
  
  switch( $direction ) {
    case 'up': {
      $top += $delta;
      break;
    }
    case 'down': {
      $top -= $delta;
      break;
    }
    case 'left': {
      $left += $delta;
      break;
    }
    case 'right': {
      $left -= $delta;
      break;
    }
  }
  
  $thumb = imagecreatetruecolor($width, $height);
  imagecopyresampled( $thumb, $master, 0, 0, $left, $top, $width, $height, $master_width, $master_height );
  $file = ROOT.'/l/'.$typ.'/'.$id.'.jpg';
  @unlink( $file );
  imagejpeg( $thumb, $file, 90 );
  imagedestroy( $thumb );
  $_SERVER['DB']->q("INSERT INTO ludzie_avatary (`czlowiek_id`, `typ`, `left`, `top`) VALUES ('$id', '$typ', $left, $top) ON DUPLICATE KEY UPDATE `left`=$left, `top`=$top");
?>