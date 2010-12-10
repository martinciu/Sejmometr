<?
  $limit = 20;
  $i = 0;
  
  while( $i<$limit && $id=$this->DB->selectValue("SELECT id FROM poslowie WHERE status_kluby_historia='0' LIMIT 1") ) {
    $i++;
    $this->S('poslowie/kluby_historia/przetworz', $id);
  }
?>