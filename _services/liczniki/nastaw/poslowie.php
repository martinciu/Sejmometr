<?
  $this->S('liczniki/nastaw', array('poslowie', $this->DB->selectCount("SELECT COUNT(*) FROM poslowie WHERE akcept='0' OR `update`='1'")));
?>