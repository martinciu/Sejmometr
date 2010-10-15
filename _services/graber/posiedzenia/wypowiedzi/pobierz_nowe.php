<?
  require_once(ROOT.'/_lib/SejmParser.php');
	$SP = new SejmParser();
  $session_data = $this->S('graber/session_start', 'wypowiedzi/pobierz_nowe');
  
	$this->DB->q("UPDATE `dni_modele` SET status='0' WHERE status='1' AND TIMESTAMPDIFF(MINUTE, data_pobrania, NOW())>7");
	
  while( $id = $this->DB->selectValue("SELECT dni_modele.id FROM dni_modele LEFT JOIN posiedzenia_dni ON dni_modele.dzien_id=posiedzenia_dni.id WHERE dni_modele.typ='1' AND posiedzenia_dni.status='2' AND dni_modele.status='0' LIMIT 1") ) {
        
	  $sejm_id = $this->DB->selectValue("SELECT sejm_id FROM dni_modele WHERE id='$id'");
	  $this->DB->q("UPDATE dni_modele SET status='1', data_pobrania=NOW() WHERE id='$id'");
	  
	  $data = $SP->wypowiedzi_info($sejm_id);
	  $punkty = addslashes($data['punkty']);
	  $text = addslashes($data['text']);
	  
	  $this->DB->q("UPDATE dni_modele SET status='2', data_pobrania=NOW(), punkty='$punkty', text='$text' WHERE id='$id'");
	  $result = $this->DB->affected_rows;
  }
  
  $session_data[] = $result;
  $this->S('graber/session_end', $session_data);
?>