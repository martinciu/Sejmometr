<?
  $result = 0;
  while( $id=$this->S('graber/dokumenty/obrazy/id_pierwszy_niepobrany') ) {
    if( $this->S('graber/dokumenty/obrazy/pobierz', $id) ) $result++;
  }
  $this->S('graber/dokumenty/log', array('obrazy', $result));
  return $result;
?>