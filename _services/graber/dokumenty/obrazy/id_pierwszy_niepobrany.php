<?
  return $this->DB->selectValue("SELECT id FROM dokumenty WHERE obraz='2' AND TIMESTAMPDIFF(MINUTE, data_obraz, NOW())>=5 LIMIT 1");
?>