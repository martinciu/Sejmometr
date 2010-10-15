<?
  $dok_id = (int) $_PARAMS;
  if( $dok_id==0 ) return array('error'=>1);
  
  list( $dok_typ, $dok_gid, $download_url, $plik ) = $this->DB->selectRow("SELECT typ, gid, download_url, plik FROM dokumenty_problemy WHERE id='$dok_id'");
  $pathparts = pathinfo($plik);
  $plik = $pathparts['filename'].'-SP'.uniqid().'.'.$pathparts['extension'];
  
  switch( $dok_typ ) {
    case 'bas': {
      
      if( strlen($dok_gid)!=5 ) return array('error'=>2);
      if( $this->DB->selectCount("SELECT COUNT(*) FROM bas WHERE id='".$dok_gid."' AND dokument_id=''")!==1 ) return array('error'=>3);
      
      
      $nowy_dok_id = $this->DB->insert_assoc_create_id('dokumenty', array(
        'typ' => 'bas',
        'gid' => $dok_gid,
        'download_url' => $download_url,
        'plik' => $plik,
        'plik_rozszerzenie' => $pathparts['extension'],
      ));
      
      if( strlen($nowy_dok_id)!=5 ) return array('error'=>3);
      
      $this->DB->update_assoc('bas', array(
        'dokument_id' => $nowy_dok_id,
      ), $dok_gid);
      
      if( $this->DB->affected_rows ) $this->DB->q("DELETE FROM dokumenty_problemy WHERE id='$dok_id'");
    
      $this->S('liczniki/nastaw/dokumenty_obrabiane');  
      return 4;
      
    }
  }
  
?>