<?
  $s = $_PARAMS;
  if( $s=='' ) { return array('',''); }
  elseif( $s=='Marszałek' ) { return array('','Marszałek',0); }
  elseif( $s=='Poseł Włodzimierz Witold Karpiński' ) { return array('Wlodzimierz-Karpinski','Poseł',1); }
  elseif( $s=='Poseł Stanisław Marcin Chmielewski' ) { return array('Stanislaw-Chmielewski','Poseł',1); }
  elseif( $s=='Poseł Sprawozdawca Stanisław Marcin Chmielewski' ) { return array('Stanislaw-Chmielewski','Poseł Sprawozdawca',1); }
  else {
      
    $reserved_words = array('Poseł', 'Sprawozdawca');
    $_words = explode(' ', $s);
    for( $i=0; $i<count($_words); $i++ ) {
      $word = $_words[$i];
      if( !in_array($word, $reserved_words) ) $words[] = $word;
    }
    
    $typy = $this->DB->selectAssocs("SELECT `id`, `imie`, `drugieImie`, `nazwisko` FROM `ludzie` WHERE `imie`='".implode("' OR `imie`='", $words)."' OR `nazwisko`='".implode("' OR `nazwisko`='", $words)."' OR `drugieImie`='".implode("' OR `drugieImie`='", $words)."'");
    
    if( is_array($typy) ) foreach( $typy as $i=>$posel ) {
      $nazwa = array($posel['imie']);
      if($posel['drugieImie']) $nazwa[] = $posel['drugieImie'];
      $nazwa[] = $posel['nazwisko'];
      $nazwa = implode(' ', $nazwa);
            
      if( stripos($s, $nazwa)!==false ) {
        $autor = $posel['id'];
        $funkcja = trim( str_ireplace($nazwa,'',$s) );
        return array($autor, $funkcja, 1);
      }
    }
    
  }
?>