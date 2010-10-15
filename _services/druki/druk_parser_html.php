<?
  $id = $_PARAMS;
  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM druki WHERE id='$id'");
  if( !empty($sejm_id) ) {
    require_once( ROOT.'/_lib/SejmParser.php' );
    $SP = new SejmParser();
    return $SP->druki_info_html($sejm_id);
  }
  
  return $sejm_id;
?>