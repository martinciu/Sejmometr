<?
  $limit = 3;
  $i = 0;
  
  while( $i<$limit && $id = $this->DB->selectValue("SELECT id FROM druki WHERE sztuczny='0' ORDER BY data_sprawdzenia ASC") ) {
    $i++;
    $this->S('graber/druki/pobierz', $id);
  }
?>