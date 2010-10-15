<?
  $this->DB->q("UPDATE dokumenty SET `akcept`='2' WHERE (`scribd`='5' AND `pobrano`='2' AND `obraz`='3')");
  $this->DB->q("UPDATE dokumenty SET `akcept`='0' WHERE (`scribd`!='5' AND `pobrano`!='2' AND `obraz`!='3')");
  
  $this->S('liczniki/nastaw', array('dokumenty_obrabiane', $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty WHERE akcept='0'")));
  $this->S('liczniki/nastaw', array('dokumenty_problemowe', $this->DB->selectCount("SELECT COUNT(*) FROM dokumenty_problemy")));
?>