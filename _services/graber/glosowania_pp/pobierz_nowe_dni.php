<?
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $data = $SP->glosowania_dni_lista();
  foreach( $data as $dzien ) $this->DB->insert_ignore_assoc('glosowania_pp_dni', $dzien);
?>