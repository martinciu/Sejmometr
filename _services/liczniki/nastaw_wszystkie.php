<?
  $di = new DirectoryIterator(ROOT.'/_services/liczniki/nastaw');
  foreach( $di as $file ) {
    if( !$file->isDot() ) {
      $licznik = filename($file->getFilename());
      if( $licznik[0]!='-' ) $this->S('liczniki/nastaw/'.$licznik);
    }    
  }
?>