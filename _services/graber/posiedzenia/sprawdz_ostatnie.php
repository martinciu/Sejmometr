<?
  $id = $this->DB->selectValue("SELECT id FROM posiedzenia ORDER BY data_sprawdzenia ASC LIMIT 1");
  $this->S('graber/posiedzenia/sprawdz', $id);
  $this->DB->update_assoc('posiedzenia', array('data_sprawdzenia'=>'NOW()'), $id);
?>