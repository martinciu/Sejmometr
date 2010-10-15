<?
  list($scribd_id, $dokument_id) = $_PARAMS;
  if( strlen($dokument_id)!=5 ) return false;
  if( !is_numeric($scribd_id) ) return false;
  
  require_once(ROOT.'/_lib/scribd.php');
	$scribd = new Scribd($scribd_api_key, $scribd_secret);
  
  $scribd_data = $scribd->getSettings($scribd_id);
  
  $data = array(
    'scribd_doc_id' => $scribd_data['doc_id'],
    'scribd_access_key' => $scribd_data['access_key'],
    'scribd_secret_password' => $scribd_data['secret_password'],
    'ilosc_stron' => $scribd_data['page_count'],
    'scribd' => '5',
  );
  
  $result = $this->DB->update_assoc('dokumenty', $data, $dokument_id);
  $this->S('liczniki/nastaw/dokumenty_obrabiane');
  return $result;
?>