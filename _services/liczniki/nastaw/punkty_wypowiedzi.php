<?
  $this->S('liczniki/nastaw', array('punkty_wypowiedzi', $this->DB->selectCount("SELECT COUNT(*) FROM punkty_wypowiedzi WHERE sejm_id!='Oświadczenia' AND akcept='0'")));
  $this->S('liczniki/nastaw', array('debaty_specjalne', $this->DB->selectCount("SELECT COUNT(*) FROM debaty_specjalne WHERE akcept='0'")));
?>