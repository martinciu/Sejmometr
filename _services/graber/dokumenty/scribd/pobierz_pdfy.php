<?
  require_once(ROOT.'/_lib/scribd.php');
	$scribd = new Scribd($scribd_api_key, $scribd_secret); 
  $result = 0;
	
	// $this->DB->q("UPDATE `dokumenty` SET scribd='1' WHERE scribd='2' AND TIMESTAMPDIFF(MINUTE, data_scribd, NOW())>7");
  
  while( $id = $this->DB->selectValue("SELECT * FROM `dokumenty` WHERE plik_rozszerzenie!='pdf' AND scribd='5' AND obraz='0' ORDER BY data_scribd ASC LIMIT 1") ) {
  	if( $id==$_id ) return false;
  	
  	$scribd_doc_id = $this->DB->selectValue("SELECT scribd_doc_id FROM dokumenty WHERE id='$id'");
  	
  	$source = $scribd->getDownloadUrl($scribd_doc_id, 'pdf');  	
  	$target = ROOT.'/dokumenty/'.$id.'.pdf';
  	
  	@unlink($target);
  	if( copy($source, $target) ) {
  	  $this->DB->q("UPDATE `dokumenty` SET obraz='1' WHERE id='$id'");
  	} else {
  	  $this->DB->q("UPDATE `dokumenty` SET scribd='6' WHERE id='$id'");
  	}
  	
  	$_id = $id;
  }
  
  return $this->S('graber/dokumenty/log', array('scribd pobieranie pdfów', $result));
?>