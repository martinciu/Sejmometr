<?
  $id = $_PARAMS['id'];
  $data = $_PARAMS['data'];
  
  if( strlen($id)!=5 ) return false;
  
  $this->DB->update_assoc('bas', array(
   'data' => $data,
   'akcept' => '1',
   'data_akceptacji' => 'NOW()',
  ), $id);
  
  $this->S('liczniki/nastaw/bas');
  
  
  $projekty = $this->DB->selectValues("SELECT projekt_id FROM projekty_bas WHERE bas_id='$id'");
  foreach( $projekty as $projekt_id ) $this->S('projekty/policz_opinie', $projekt_id);

  return 4;
?>