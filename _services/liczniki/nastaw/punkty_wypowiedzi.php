<?
  $this->S('liczniki/nastaw', array('punkty_wypowiedzi', $this->DB->selectCount("SELECT COUNT(*) FROM punkty_wypowiedzi WHERE status='1' OR  akcept='0' OR alert='1'")));
  $this->S('liczniki/nastaw', array('debaty_specjalne', $this->DB->selectCount("SELECT COUNT(*) FROM debaty_specjalne WHERE akcept='0'")));
  $this->S('liczniki/nastaw/informacje_biezace');
?>