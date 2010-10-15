<?
  $this->S('liczniki/nastaw', array('wyroki', $this->DB->selectCount("SELECT COUNT(*) FROM wyroki_tk WHERE akcept='0'")));
?>