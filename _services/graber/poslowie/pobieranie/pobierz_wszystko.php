<?
  while( $id = $this->DB->selectValue("SELECT id FROM poslowie WHERE status='0'") ) {
    $this->S('graber/poslowie/pobieranie/pobierz', $id);
  }
?>