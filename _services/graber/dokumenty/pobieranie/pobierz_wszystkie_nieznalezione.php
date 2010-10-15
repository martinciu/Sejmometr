<?
  $result = 0;
  while( $id=$this->S('graber/dokumenty/pobieranie/id_pierwszy_nieznaleziony') ) {
    if( $this->S('graber/dokumenty/pobieranie/pobierz', $id) ) $result++;
  }
  $this->S('graber/dokumenty/log', array('pobieranie', $result));
  return $result;
?>