<?
  $projekt_id = $_PARAMS;
  if( strlen($projekt_id)!=5 ) return false;
  
  $data = array();  
  $data['data_zapisania_etapow'] = 'NOW()';
  $data['html_zmiana'] = '0';
  
  $this->DB->update_assoc('projekty', $data, $projekt_id);
  
  $this->S('liczniki/nastaw/projekty-wlasciwosci');
  $this->S('liczniki/nastaw/projekty-etapy');
  
  return array(
    'status' => 4,
  );
?>