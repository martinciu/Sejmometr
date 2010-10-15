<?
  $this->S('liczniki/nastaw', array('projekty-etapy', $this->DB->selectCount("SELECT COUNT(*) FROM projekty WHERE akcept='1' AND html_zmiana='1'")));
?>