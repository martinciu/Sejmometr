<?
  $id = $_PARAMS['id'];
  $data = $_PARAMS['data'];
  $projekty = $_PARAMS['projekty'];
  
  if( strlen($id)!=5 || !is_array($projekty) || empty($projekty) ) return false;
  
  $this->DB->update_assoc('bas', array(
   'data' => $data,
   'akcept' => '1',
   'data_akceptacji' => 'NOW()',
  ), $id);
  
  $this->DB->q("DELETE FROM projekty_bas_ WHERE bas_id='$id'");
  
  foreach( $projekty as $projekt_id ) {
    $this->DB->q("INSERT IGNORE INTO projekty_bas_ (`projekt_id`, `bas_id`) VALUES ('$projekt_id', '$id')");
    $this->S('projekty/policz_opinie', $projekt_id);
  }
  
  $this->S('liczniki/nastaw/bas');
  
  return 4;
?>