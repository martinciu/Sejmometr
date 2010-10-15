<?
  while( $id = $this->DB->selectValue("SELECT id FROM isap WHERE status='0' ORDER BY data_dodania ASC LIMIT 1") ) {
    $this->S('graber/isap/pobierz', $id);
  }
?>