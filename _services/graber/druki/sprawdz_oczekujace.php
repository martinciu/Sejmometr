<?
  $ids = $this->DB->selectValues("SELECT id FROM druki WHERE dokument_id=''");
  foreach( $ids as $id ) $this->S('graber/druki/pobierz', $id);
?>