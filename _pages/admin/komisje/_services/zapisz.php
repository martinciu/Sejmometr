<?
  $id = $_PARAMS;
  
  $this->DB->update_assoc('komisje', array('akcept'=>'1'), $id);
  if( $this->DB->affected_rows ) {
    
    $this->S('liczniki/nastaw/komisje');
    return 4;
  
  }
?>