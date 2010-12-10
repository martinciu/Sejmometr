<?
  $limit = 700;
  $i = 0;
  while( $i<$limit && $id = $this->DB->selectValue("SELECT id FROM glosowania_glosy WHERE status='0' LIMIT 1") ) {
    $i++;
    $this->S('graber/glosowania/rozpoznawanie/rozpoznaj', $id);
  }
?>