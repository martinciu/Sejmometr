<?
  return $this->S('liczniki/nastaw', array('projekty_dokumenty', $this->DB->selectCount("SELECT COUNT(*) FROM projekty_dokumenty WHERE akcept='0'")));
?>