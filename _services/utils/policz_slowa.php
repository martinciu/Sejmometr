<?
  $string = $_PARAMS;
  $string = preg_replace('/\<p class=\"wMarszalek\"\>(.*?)\<\/p\>/i', '', $string);
  $string = preg_replace('/\((.*?)\)/i', '', $string);
  $string = strip_tags($string);
  
  preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", $string, $matches);
  
  $_slowa = $matches[0];
  $slowa = array();
    
  if( is_array($_slowa) ) foreach($_slowa as $s) if( strlen($s)>1 ) $slowa[] = $s;
  return count($slowa);
  
  
  
  
  /*
  $data = array();
  if( is_array($slowa) ) foreach( $slowa as $s ) {
    if( array_key_exists($s, $data) ) { $data[$s]++; } else { $data[$s]=1; }
  }
  */
?>