<?
  $debata_id = $_GET['_ID'];
  
  $debata = $this->DB->selectAssoc("SELECT punkty_wypowiedzi.id, punkty_wypowiedzi.dzien_id, punkty_wypowiedzi.typ_id, posiedzenia_dni.data FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.id='$debata_id'");
  switch( $debata['typ_id'] ) {
    case '4': {
      $debata = array_merge($debata, $this->DB->selectAssoc("SELECT tytul FROM debaty_specjalne WHERE id='$debata_id'"));
      break;
    }
  }
  
 
  $this->SMARTY->assign('debata', $debata); 
  $this->assignService('wypowiedzi_lista', 'wypowiedzi_lista', $debata_id);
?>