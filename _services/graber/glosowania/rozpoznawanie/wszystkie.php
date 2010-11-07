<?
  while( $id = $this->DB->selectValue("SELECT id FROM glosowania_glosy WHERE status='0' LIMIT 1") ) $this->S('graber/glosowania/rozpoznawanie/rozpoznaj', $id);
?>