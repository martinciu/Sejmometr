<?
  $id = $_PARAMS;
  
  if( strlen($id)!=5 ) { return false; }
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $result = '';
  $sejm_ids = $this->DB->selectValues("SELECT sejm_id FROM wypowiedzi_sejm_id WHERE wypowiedz_id='$id'");
  foreach( $sejm_ids as $id ) {
    $result .= $SP->wypowiedz_info_html($id);
  }
  echo $result;
  return '';
?>