<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;

  $_frazy = array(
    'Wysoka Izbo!',
    'Panie Marszałku!',
    'Wysoki Sejmie!',
    'W kwestii formalnej.',
    'Dziękuję bardzo.',
    'Panie Premierze!',
    'Panie Posłanki i Panowie Posłowie!',
    'Pani Marszałek!',
    'Panie i Panowie Posłowie!',
    'Bardzo dziękuję.',
    'Bardzo dziękuję, pani marszałek.',
    'Dziękuję.',
    'Szanowna Pani Marszałek!',
    'Panie Ministrze!',
    'Dziękuję, pani marszałek.',
    'Dziękuję pani marszałek.',
    'Dziękuję, panie marszałku.',
    'Dziękuję panie marszałku.',
    'Państwo Ministrowie!',
    'Szanowny Panie Marszałku!',
    'Pani Poseł!',
    'Panie Pośle!',
    'Szanowni Państwo Ministrowie!',
    'Wysoki Sejmie!',
    'Panie i Panowie Ministrowie!',
    'Panie Senatorze!',
    'Pani Senator!',
  );
  
  $text = stripslashes( $this->DB->selectValue("SELECT text FROM wypowiedzi WHERE id='$id'") );
  $p = strpos($text, '<p c');
  if( $p!==false ) $text = substr($text, 0, $p);
    
  
  $parts = explode('<p>', $text);
  
  foreach( $parts as $i=>&$part ) {
    $part = trim($part);
    if( $part=='' || in_array($part, $_frazy) ) unset( $parts[$i] );
  }
  $parts = array_values($parts);
  $part = $parts[0];
  
  do {
    reset($_frazy);
    foreach( $_frazy as $fraza ) {
      $found = false;
      if( stripos($part, $fraza)===0 ) {
        $part = trim( substr($part, strlen($fraza)) );
        $found = true;
        break;
      }
    }
  } while( $found );
  
  
  $parts = explode('. ', $part);
  $skrot_A = $parts[0].'.';
  $skrot_B = $skrot_A.' '.$parts[1].'.';
  
  
  $skrot = strlen($skrot_B)>400 ? $skrot_A : $skrot_B;
  $skrot = trim( str_replace('?.', '?', str_replace('!.', '!', str_replace('. .', '.', str_ireplace('(Oklaski)', '', $skrot)))) );
  
  return $skrot;
?>