<?
  /*
    return codes:
  
    2 - direcotry doesn't exitst
    3 - no files
    4 - registered
  
  */
  
  $component = $_PARAMS;
  $component_name = filename( $component );
  
  if( !is_dir(ROOT.'/_components/'.$component) ) return 2;
    
  $js = (int) file_exists(ROOT.'/_components/'.$component.'/'.$component_name.'.js');
  $css = (int) file_exists(ROOT.'/_components/'.$component.'/'.$component_name.'.css');
  $smarty = (int) file_exists(ROOT.'/_components/'.$component.'/'.$component_name.'.php');
  
  if( $js+$css+$smarty==0 ) return 3;
  
  $q = "INSERT IGNORE INTO ".DB_TABLE_components." (`id`, `js`, `css`, `smarty`) VALUES ('$component', '$js', '$css', '$smarty') ON DUPLICATE KEY UPDATE `js`='$js', `css`='$css', `smarty`='$smarty'";
  $this->DB->q($q);
  return 4;
?>