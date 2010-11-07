<?
  $nazwa = trim( $_PARAMS );
  if( empty($nazwa) ) return false;
  
  return str_replace(' ', '-', translate_polish_letters($nazwa));
?>