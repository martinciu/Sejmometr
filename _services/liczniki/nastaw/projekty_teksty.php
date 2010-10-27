<?
  $this->S('liczniki/nastaw', array('projekty_teksty', $this->DB->selectCount("SELECT COUNT(*) FROM projekty_teksty WHERE akcept='0'")));
  return $_PARAMS;
?>