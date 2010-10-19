<?
  $id = (int) $_PARAMS['id'];
  $tytul = addslashes( trim( $_PARAMS['tytul'] ) );
  $opis = addslashes( trim( $_PARAMS['opis'] ) );
  $tresc = addslashes( trim( $_PARAMS['tresc'] ) );
  
  if( $tytul=='' || $opis=='' || $tresc=='' ) return 2;
  
  
  if( $id==0 ) {
  
	  $this->DB->insert_assoc('blog', array(
	    'tytul' => $tytul,
	    'opis' => $opis,
	    'tresc' => $tresc,
	    'autor' => $this->USER['login'],
	  ));
  
  } else {
    
    $this->DB->q("UPDATE blog SET `tytul`='$tytul', `opis`='$opis', `tresc`='$tresc' WHERE id=$id");
  
  }
  
  return $this->DB->affected_rows;
?>