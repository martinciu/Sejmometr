<?
  $id = (int) $_PARAMS['id'];
  $tytul = addslashes( trim( $_PARAMS['tytul'] ) );
  $opis = addslashes( trim( $_PARAMS['opis'] ) );
  $tresc = addslashes( trim( $_PARAMS['tresc'] ) );
  $url_title = url_title($tytul);
  
  if( $tytul=='' || $opis=='' || $tresc=='' ) return 2;
  
  
  if( $id==0 ) {
  
	  $this->DB->insert_assoc('blog', array(
	    'tytul' => $tytul,
	    'url_title' => $url_title,
	    'opis' => $opis,
	    'tresc' => $tresc,
	    'autor' => $this->USER['login'],
	    'robocza' => '0',
	  ));
  
  } else {
    
    $this->DB->q("UPDATE blog SET `tytul`='$tytul', `url_title`='$url_title', `opis`='$opis', `tresc`='$tresc', `robocza`='0' WHERE id=$id");
  
  }
  
  return $this->DB->affected_rows;
?>