<?
  $this->S('liczniki/nastaw', array('druki_nieprzypisane', $this->DB->selectCount("SELECT COUNT(*) FROM druki WHERE akcept='1' AND typ_id!=13 AND przypisany='0'")));
?>