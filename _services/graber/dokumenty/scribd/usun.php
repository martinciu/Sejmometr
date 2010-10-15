<?
  $id = $_PARAMS;
  $doc_id = $this->DB->selectValue("SELECT scribd_doc_id FROM dokumenty WHERE id='$id'");
  
  if( $doc_id ) {
  
	  require_once(ROOT.'/_lib/scribd.php');
		$scribd = new Scribd($scribd_api_key, $scribd_secret);
	  
	  if( $scribd->delete( $doc_id ) ) {
	    $this->DB->q("UPDATE dokumenty SET scribd_doc_id=0, scribd_access_key='', scribd_secret_password='', obraz='1', scribd='0', akcept='0' WHERE id='$id'");
	  }
  
  }
?>