<?    
  while( $id=$this->DB->selectValue("SELECT id FROM `posiedzenia_dni` WHERE (analiza_wystapienia='0' AND status='2' AND TIMESTAMPDIFF(MINUTE, data_pobrania_modelu, NOW())>12)") ) {
    if( $id!=$_id ) {
      $this->S('graber/posiedzenia/analizator/analizuj_wypowiedz', $id);
      $_id = $id;
    } else return false;
  }
?>