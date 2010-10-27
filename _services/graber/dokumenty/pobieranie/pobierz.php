<?
  $id = $_PARAMS;
  if( empty($id) ) return false;

  list($source, $extension) = $this->DB->selectRow("SELECT download_url, plik_rozszerzenie FROM dokumenty WHERE id='$id'");
  $source = _urlencode( $source );  
  $dest = ROOT.'/dokumenty/'.$id.'.'.$extension;
    
  $this->DB->insert_assoc('graber_pobieranie_dokumentow', array(
    'dokument_id' => $id,
    'source' => $source,
    'dest' => $dest,
  ));
  $session_id = $this->DB->insert_id;
  
  $this->DB->q("UPDATE dokumenty SET pobrano='1', akcept='0', data_pobrania=NOW() WHERE id='$id'");
  
  @unlink($dest);
  if( copy($source, $dest) ) {
    
    $this->DB->q("UPDATE graber_pobieranie_dokumentow SET size='".filesize($dest)."' WHERE id='$session_id'");
    
    if( $extension=='pdf' ) {
      $this->DB->q("UPDATE dokumenty SET pobrano='2', obraz='1', akcept='0' WHERE id='$id'");
    } else {
      $this->DB->q("UPDATE dokumenty SET pobrano='2', scribd='1', akcept='0' WHERE id='$id'");
    }
    
    return true;
  } else {
    $this->DB->q("UPDATE dokumenty SET data_pobrania=NOW(), pobrano='3', akcept='0' WHERE id='$id'");
    return false;
  }
?>