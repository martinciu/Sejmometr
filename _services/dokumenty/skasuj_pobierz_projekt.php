<?
  $dok_id = (int) $_PARAMS;
  if( $dok_id==0 ) return array('error'=>1);
  
  list( $dok_typ, $dok_gid, $download_url, $plik ) = $this->DB->selectRow("SELECT typ, gid, download_url, plik FROM dokumenty_problemy WHERE id='$dok_id'");
  
  if( $dok_typ=='bas' ) {
    
    $ids = $this->DB->selectValues("SELECT gid FROM dokumenty WHERE typ='bas' AND plik='$plik'");
    if( count($ids)!=1 ) return array('error'=>2);
    $stary_bas_id = $ids[0];
    
    $projekty = $this->DB->selectValues("SELECT projekt_id FROM projekty_bas WHERE bas_id='".$stary_bas_id."'");


    $this->DB->q("DELETE FROM dokumenty_problemy WHERE id='$dok_id'");

        
    foreach( $projekty as $projekt_id ) {
      
      $bas_ids = $this->DB->selectValues("SELECT bas.id FROM projekty_bas LEFT JOIN bas ON projekty_bas.bas_id=bas.id WHERE projekty_bas.projekt_id='$projekt_id' AND dokument_id NOT IN (SELECT id FROM dokumenty WHERE typ='bas')");


      
      $q = "DELETE FROM dokumenty WHERE typ='bas' AND (gid='".implode("' OR gid='", $bas_ids)."')";
      echo "\n$q";
      $this->DB->q($q);
      
      $q = "DELETE FROM bas WHERE (id='".implode("' OR id='", $bas_ids)."')";
      echo "\n$q";
      $this->DB->q($q);
      
      $q = "DELETE FROM projekty_bas WHERE (bas_id='".implode("' OR bas_id='", $bas_ids)."')";
      echo "\n$q";
      $this->DB->q($q);
      
      $this->S('graber/projekty/pobierz', $projekt_id);
            
    }
    
    return 4;

  }
  
  $this->S('liczniki/nastaw/dokumenty_obrabiane');  
?>