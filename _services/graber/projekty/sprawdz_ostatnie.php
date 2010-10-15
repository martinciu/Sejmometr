<?
  $limit = is_numeric($_PARAMS) ? $_PARAMS : 5;
  
  $ids = $this->DB->selectValues("SELECT id FROM `projekty` ORDER BY data_sprawdzenia ASC LIMIT ".$limit);
  foreach( $ids as $id ) $this->S('graber/projekty/pobierz', $id);
?>