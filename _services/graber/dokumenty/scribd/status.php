<?
  require_once(ROOT.'/_lib/scribd.php');
	$scribd = new Scribd($scribd_api_key, $scribd_secret);
	
	$id = $_PARAMS;
	
	$doc_id = $this->DB->selectValue("SELECT scribd_doc_id FROM dokumenty WHERE `id`='$id'");
	
	return $scribd->getConversionStatus($doc_id);
?>