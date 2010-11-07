<?
	$session_data = $this->S('graber/session_start', 'glosowania/pobierz_nowe');
	
	$this->DB->q("UPDATE `glosowania` SET status='0' WHERE status='1' AND TIMESTAMPDIFF(MINUTE, data_pobrania, NOW())>3");
	
  while( $id = $this->DB->selectValue("SELECT id FROM glosowania WHERE status='0' ORDER BY dzien_id ASC, numer ASC LIMIT 1") ) {
	  $result = $this->S('graber/posiedzenia/glosowania/pobierz', $id); 	  
  }
  
  $session_data[] = $result;
  $this->S('graber/session_end', $session_data);
?>