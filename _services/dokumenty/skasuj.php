<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  $typ = $this->DB->selectValue("SELECT typ FROM dokumenty WHERE id='$id'");
  
  $folders = array('0','1','2','3','4','5');
  foreach( $folders as $folder ) {
    @unlink( ROOT.'/d/'.$folder.'/'.$id.'.gif' );
  }
  $this->DB->q("DELETE FROM dokumenty WHERE `id`='$id'");
  
  
  
  if( $typ=='druk' ) {

    $druki = $this->DB->selectValues("SELECT id FROM druki WHERE dokument_id='$id'");
    foreach( $druki as $druk_id ) {

      $ilosc_dokumentow = $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty WHERE `typ`='druk' AND `gid`='$druk_id'");
      $this->DB->q("UPDATE druki SET `akcept`='0', `dokument_id`='', `ilosc_dokumentow`='$ilosc_dokumentow' WHERE `id`='$druk_id'");
      
    }
    return 3;

  }
    
  return 2;
?>