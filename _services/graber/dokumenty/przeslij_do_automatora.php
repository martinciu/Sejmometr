<?
  $id = $_PARAMS;
  
  $this->DB->q("UPDATE dokumenty SET `obraz`='1', `akcept`='0' WHERE `id`='$id'");
  $this->S('liczniki/nastaw/dokumenty_obrabiane');
?>