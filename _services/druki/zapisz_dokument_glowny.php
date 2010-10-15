<?
  /*
    return codes
    
    2 - incorect params
    3 - ok
    
  */
  
  list($druk, $dokument) = $_PARAMS;
  if( empty($druk) || empty($dokument) ) return 2;
  $this->DB->q("UPDATE `druki` SET `dokument_id`='$dokument' WHERE `id`='$druk'");
  $this->DB->q("UPDATE `druki_multi` SET `akcept`='1' WHERE `id`='$druk'");
  $this->S('liczniki/nastaw/druki_multi');
  return 3;
?>