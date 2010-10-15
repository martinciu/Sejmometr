<?
  require_once(ROOT.'/_lib/scribd.php');
	$scribd = new Scribd($scribd_api_key, $scribd_secret); 
  $result = 0;
	
	$this->DB->q("UPDATE `dokumenty` SET scribd='1' WHERE scribd='2' AND TIMESTAMPDIFF(MINUTE, data_scribd, NOW())>7");

  while( $id = $this->DB->selectValue("SELECT id FROM dokumenty WHERE scribd='1' ORDER BY data_dodania ASC LIMIT 1") ) {
  	  
	  $this->DB->q("UPDATE dokumenty SET `scribd`='2', `data_scribd`=NOW() WHERE `id`='$id'");
	  $extension = $this->DB->selectValue("SELECT plik_rozszerzenie FROM dokumenty WHERE id='$id'");
	  
	  $r = $scribd->uploadFromUrl('http://sejmometr.com.pl/dokumenty/'.$id.'.'.$extension, null, 'private');
	  	  
	  $doc_id = (int) $r['doc_id'];
	  
	  if( $doc_id>0 ) {
	    $this->DB->q("UPDATE dokumenty SET `scribd`='3', `data_scribd`=NOW(), `scribd_doc_id`='".$doc_id."', `scribd_access_key`='".$r['access_key']."', `scribd_secret_password`='".$r['secret_password']."' WHERE `id`='$id'");
	    $result++;
	  }
  
  }
  
  return $this->S('graber/dokumenty/log', array('scribd przesyłanie', $result));
?>