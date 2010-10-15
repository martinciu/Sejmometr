<?
  $params = $_PARAMS;
  
  if( !is_array($params) || empty($params) || !$params['typ'] || !$params['plik'] || !$params['download_url'] ) return false;
  
  $pathparts = pathinfo($params['plik']);
  $plik_data = array(
    'typ' => $params['typ'],
    'gid' => $params['gid'],
    'download_url' => $params['download_url'],
    'plik' => $params['plik'],
    'plik_rozszerzenie' => strtolower( $pathparts['extension'] ),
  );
  $count = $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty WHERE (typ='".$params['typ']."' AND plik='".$params['plik']."')");
  if( $count===0 ) {  
    $result = $this->DB->insert_assoc_create_id('dokumenty', $plik_data);
  } else {
    $this->DB->insert_ignore_assoc('dokumenty_problemy', $plik_data);
    $result = false;
  }
  
  $this->S('liczniki/nastaw/dokumenty_obrabiane');
  return $result;
?>