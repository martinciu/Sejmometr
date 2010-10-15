<?
  $adres = $_PARAMS;
  if( preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $adres) ) {
    
    $ip = $_SERVER['REMOTE_ADDR'];
	  $this->DB->query("INSERT INTO newsletter_adresy (`adres`, `ip`) VALUES ('$adres', '$ip')");
    if( $this->DB->affected_rows ) return true;
      
  }
  return false;
?>