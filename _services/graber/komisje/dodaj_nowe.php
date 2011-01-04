<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $result = 0;
  $data = $SP->komisje_lista();
  
  foreach( $data as $d ) {
    $this->DB->insert_ignore_assoc('komisje', array(
      'id' => $d[0],
      'typ' => $d[1],
    ));
    if( $this->DB->affected_rows ) $result++;
  }
  
  if( $result ) $this->S('liczniki/nastaw/komisje');
  return $result;
?>