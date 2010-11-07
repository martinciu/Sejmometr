<?
  $data = $this->DB->selectRows("SELECT glosowanie_id, klub_id FROM glosowania_kluby WHERE (status='1' OR status='3') AND TIMESTAMPDIFF(MINUTE, data_pobrania, NOW())>3");
  foreach( $data as $item ) {
    list( $glosowanie_id, $klub_id ) = $item;
    $this->DB->q("DELETE FROM glosowania_glosy WHERE glosowanie_id='$glosowanie_id'");
    $this->DB->q("UPDATE glosowania_kluby SET status='0' WHERE glosowanie_id='$glosowanie_id' AND klub_id='$klub_id'");
  }
  
  
  while( $data = $this->DB->selectRow("SELECT glosowanie_id, klub_id FROM glosowania_kluby WHERE status='0'") ) $this->S('graber/posiedzenia/glosowania/pobierz_klub', $data);
?>