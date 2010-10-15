<?
  $this->S('liczniki/nastaw', array('druki_nieprzypisane', $this->DB->selectCount("SELECT COUNT(*) FROM druki WHERE przypisany='0' AND zalacznik='0' AND typ_id!=13")));
?>