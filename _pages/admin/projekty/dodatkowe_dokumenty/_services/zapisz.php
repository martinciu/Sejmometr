<?
  $id = $_PARAMS['id'];
  $tytul = $_PARAMS['tytul'];
  
  if( empty($id) ) return false;
  
  $projekt_id = $this->DB->selectValue("SELECT projekt_id FROM projekty_dokumenty WHERE `id`='$id'");
  $this->DB->q("UPDATE projekty_dokumenty SET `tytul`='".addslashes($tytul)."', `akcept`='1' WHERE `id`='$id'");
  
  $this->S('liczniki/nastaw/projekty_dokumenty');
  $this->S('projekty/dodatkowe_dokumenty/policz', $projekt_id);
  
  return 4;
?>