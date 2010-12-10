<?
  $this->S('liczniki/nastaw', array('wypowiedzi', $this->DB->selectCount("SELECT COUNT(*) FROM `wypowiedzi` WHERE typ='0' OR (punkt_id!='' AND (funkcja_id=0 OR autor_id=''))")));
?>