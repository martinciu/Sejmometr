<?
  $this->S('liczniki/nastaw', array('projekty-skasowane', $this->DB->selectCount("SELECT COUNT(*) FROM projekty WHERE response_status!=200")));
?>