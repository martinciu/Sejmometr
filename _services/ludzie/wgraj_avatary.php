<?
  if( !function_exists('clean_name') ) {
    function clean_name($s) {
      $chars = 'qwertyuiopasdfghjklzxcvbnm ';
      $result = '';
      for( $i=0; $i<strlen($s); $i++ ) {
        $c = strtolower( $s[$i] );
        $result .= strpos($chars, $c)===false ? '' : $c;
      }
      return $result;
    }
  }
  
  $_min_width = 110;
  $_min_height = 140;
  $_output_width = 220;
  $_output_height = 280;
  
  $folder = ROOT.'/avatary_podajnik';
  
  $DI = new DirectoryIterator( $folder );
  foreach( $DI as $item ){
    if( !$item->isDot() ) {
      $parts = pathinfo($item);
      
      $ext = strtolower( $parts['extension'] );
      if( $ext=='jpg' || $ext=='jpeg' ) {
        
        $nazwa = clean_name($parts['filename']);
        $this->DB->q("INSERT INTO avatary_wgrane (nazwa) VALUES ('".addslashes($nazwa)."')");
        $nid = $this->DB->insert_id;
        
        $src_file = $folder.'/'.$item;
        $target_file = ROOT.'/resources/ludzie/avatary/'.$nid.'.jpg';
        
        
        
        $src_size = getimagesize( $src_file );
        
        if( $src_size[0]>=$_min_width && $src_size[1]>=$_min_height ) {
        
          $src_img = imagecreatefromjpeg($src_file);
          $target_size = array();
          if( $src_size[0]>$src_size[1] ) {
            $target_size[0] = $_output_width;
            $target_size[1] = round( $_output_width * $src_size[1] / $src_size[0] );
          } else {
            $target_size[1] = $_output_height;
            $target_size[0] = round( $_output_height * $src_size[0] / $src_size[1] );
          }
          
          $target_img = imagecreatetruecolor($target_size[0], $target_size[1]);
          imagecopyresampled($target_img, $src_img, 0, 0, 0, 0, $target_size[0], $target_size[1], $src_size[0], $src_size[1]);
          @unlink( $target_file );
          if( imagejpeg( $target_img, $target_file, 100 ) ) @unlink($src_file);
        }
        
        
      }
    }
  }
?>