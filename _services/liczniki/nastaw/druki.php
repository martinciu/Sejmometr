<?
  $this->S('liczniki/nastaw', array('druki', $this->DB->selectCount("SELECT COUNT(*) FROM druki WHERE akcept='0'")));
  $this->S('liczniki/nastaw/druki_oczekujace');
  $this->S('liczniki/nastaw/druki_multi');
?>