<?
  $limit = 100;
  while( $i<$limit && $id=$this->DB->selectValue("SELECT id FROM wypowiedzi WHERE lucene='1' LIMIT 1") ) {
    $i++;
    $this->S('lucene/wypowiedzi/dodaj', $id);
    die();
  }
?>