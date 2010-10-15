<?
  $this->S('liczniki/nastaw', array('projekty-wlasciwosci', $this->DB->selectCount("SELECT COUNT(*) FROM projekty WHERE akcept='0'")));
?>