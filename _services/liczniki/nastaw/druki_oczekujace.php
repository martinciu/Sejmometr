<?
  return $this->S('liczniki/nastaw', array('druki_oczekujace', $this->DB->selectCount("SELECT COUNT(*) FROM druki WHERE `ilosc_dokumentow`=0")));
?>