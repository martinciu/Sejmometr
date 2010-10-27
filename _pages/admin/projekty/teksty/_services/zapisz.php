<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $autorzy = $_PARAMS['autorzy'];
  $tresc = $_PARAMS['tresc'];
  $uzasadnienie = $_PARAMS['uzasadnienie'];
  $osr = $_PARAMS['osr'];
  
  $this->DB->update_assoc('projekty_teksty', array(
    'autorzy' => addslashes($autorzy),
    'tresc' => addslashes($tresc),
    'uzasadnienie' => addslashes($uzasadnienie),
    'osr' => addslashes($osr),
    'akcept' => '1',
  ), $id);
  
  $this->S('liczniki/nastaw/projekty_teksty');
  
  return 4;
?>