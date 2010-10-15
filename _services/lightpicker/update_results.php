<?
  list($loader, $q, $params) = $_PARAMS;
  $html = '';
  
  if( !empty($loader) ) {
    $file = ROOT.'/_services/lightpicker/loaders/'.$loader.'.php';
    if( file_exists($file) ) $data = include($file);
  }
  
  return array(
    'html'=> $data['html'],
  );
?>