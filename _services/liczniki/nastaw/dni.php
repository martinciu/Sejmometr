<?
  $this->S('liczniki/nastaw', array('dni', $this->DB->selectCount("SELECT COUNT(*) FROM posiedzenia_dni WHERE analiza_wystapienia!='4' OR analiza_glosowania!='4'")));
?>