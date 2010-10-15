<?
  return $this->S('liczniki/nastaw', array('druki_multi', $this->DB->selectCount("SELECT COUNT(*) FROM druki_multi WHERE akcept='0'")));
?>