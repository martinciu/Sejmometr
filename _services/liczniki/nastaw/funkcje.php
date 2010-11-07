<?
  $this->S('liczniki/nastaw', array('funkcje', $this->DB->selectCount("SELECT COUNT(*) FROM wypowiedzi_nierozpoznani_autorzy")));
?>