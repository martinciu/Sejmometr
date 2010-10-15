<?
  while( $id = $this->DB->selectValue("SELECT id FROM glosowania_pp_dni WHERE status='0'") ) {
    $this->S('graber/glosowania_pp/pobierz_dzien', $id);
  }
?>