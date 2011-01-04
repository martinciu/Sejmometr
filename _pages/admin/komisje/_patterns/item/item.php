<?
  $id = $_PARAMS['id'];
  
  $item = $this->DB->selectAssoc("SELECT komisje.id, komisje_typy.label FROM komisje LEFT JOIN komisje_typy ON komisje.typ=komisje_typy.id WHERE komisje.id='$id'");
  $nazwa = $this->DB->selectRow("SELECT autor, dopelniacz FROM druki_autorzy WHERE id='$id' AND typ='komisja'");
  if( !$nazwa ) $nazwa = array();
  
  $result = array(
    'item' => $item,
    'nazwa' => $nazwa,
  );
      
  return $result;
?>