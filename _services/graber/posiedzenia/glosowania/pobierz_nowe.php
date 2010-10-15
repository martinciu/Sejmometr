<?
  require_once(ROOT.'/_lib/SejmParser.php');
	$SP = new SejmParser();
	$session_data = $this->S('graber/session_start', 'glosowania/pobierz_nowe');
	
	$this->DB->q("UPDATE `glosowania` SET status='0' WHERE status='1' AND TIMESTAMPDIFF(MINUTE, data_pobrania, NOW())>7");
	
  while( $id = $this->DB->selectValue("SELECT id FROM glosowania WHERE status='0' ORDER BY dzien_id ASC, numer ASC LIMIT 1") ) {
	  
	  $this->DB->q("UPDATE glosowania SET status='1', data_pobrania=NOW() WHERE id='$id'");
	  list($sejm_id, $dzien_id) = $this->DB->selectRow("SELECT sejm_id, dzien_id FROM glosowania WHERE id='$id'");
	 	  
	  $data = $SP->glosowanie_info($sejm_id);
	  
	  $posiedzenie_numer = addslashes($data['posiedzenie_numer']);
	  $punkt = addslashes($data['punkt']);	  
	  
	  $this->DB->q("INSERT INTO glosowania_modele (`glosowanie_id`, `punkt`, `posiedzenie_numer`) VALUES ('$id', '$punkt', '$posiedzenie_numer') ON DUPLICATE KEY UPDATE `posiedzenie_numer`='$posiedzenie_numer', `punkt`='$punkt'");
	  
	  $this->DB->update_assoc('glosowania', array(
	    'data_pobrania' => 'NOW()',
	    'numer' => $data['numer'],
	    'czas' => $data['czas'],
	    'tytul' => addslashes($data['tytul']),
	    'status' => '2',
	  ), $id);
	  $result = $this->DB->affected_rows; 	  
  }
  
  $session_data[] = $result;
  $this->S('graber/session_end', $session_data);
?>