<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  list( $analiza_glosowania ) = $this->DB->selectRow("SELECT analiza_glosowania FROM posiedzenia_dni WHERE id='$id'");
  
  if( $analiza_glosowania==4 || $analiza_glosowania==0 ) {
    $this->DB->update_assoc('posiedzenia_dni', array(
      'analiza_wystapienia' => '4',
    ), $id);
    $this->S('liczniki/nastaw/dni');
    return 4;
  } else return 'Nie jestem pewien, czy potrafię obsłużyć te głosowania...';
?>