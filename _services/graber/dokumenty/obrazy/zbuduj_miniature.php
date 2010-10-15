<?
  list($src, $d_width, $d_height, $label) = $_PARAMS;
  if( !file_exists($src) || !$d_width || !$d_height || (empty($label) && $label!=='0') ) return false;
  
  
  
  list($s_width, $s_height) = getimagesize( $src );
  
  $pathparts = pathinfo( $src );
  $outputname = ROOT.'/d/'.$label.'/'.$pathparts['filename'].'.gif';
  
  $image = imagecreatefromgif($src);
  if( $image===false ) { return false; }
  
  $sRatio = $s_width / $s_height;
  $dRatio = $d_width / $d_height;
  
  if( $sRatio<$dRatio ) {
    // fixed X
    $r_width = $d_width;
    $r_height = round( $r_width * $s_height / $s_width );
  } else {
    // fixed Y
    $r_height = $d_height;
    $r_width = round( $r_height * $s_width / $s_height );
  }
  
  $start_x = round( ($r_width - $d_width) / 2 );
  $start_y = round( ($r_height - $d_height) / 2 );
  /*
  echo "s_width= $s_width; s_height= $s_height;<br/>";
  echo "d_width= $d_width; d_height= $d_height;<br/>";
  echo "r_width= $r_width; r_height= $r_height;<br/>";
  echo "start_x= $start_x; start_y= $start_y;<br/>";
  */
  
  $s_height = $s_height - 2*$start_y;
  $s_width = $s_width - 2*$start_x;
  $tmp_image = imagecreatetruecolor($d_width, $d_height);
  imagecopyresampled( $tmp_image, $image, 0, 0, $start_x, $start_y, $d_width, $d_height, $s_width, $s_height);
  @unlink( $outputname );
  $result = imagegif($tmp_image, $outputname);
	imagedestroy($image);
	imagedestroy($tmp_image);
	return $result;
?>