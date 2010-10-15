<?
  $result = 0;
  
  $this->DB->q("UPDATE `dokumenty` SET pobrano='0' WHERE pobrano='1' AND TIMESTAMPDIFF(MINUTE, data_pobrania, NOW())>4");
  
  while( $id=$this->S('graber/dokumenty/pobieranie/id_pierwszy_niepobrany') ) {
    if( $this->S('graber/dokumenty/pobieranie/pobierz', $id) ) $result++;
  }
  $this->S('graber/dokumenty/log', array('pobieranie', $result));
  return $result;
?>