<?    
  while( $id=$this->DB->selectValue("SELECT id FROM `posiedzenia_dni` WHERE (analiza_glosowania='0' AND analiza_wystapienia='4' AND status='2')") ) {
    if( $id!=$_id ) {
      $this->S('graber/posiedzenia/analizator/analizuj_glosowanie', $id);
      $_id = $id;
    } else return false;
  }
?>