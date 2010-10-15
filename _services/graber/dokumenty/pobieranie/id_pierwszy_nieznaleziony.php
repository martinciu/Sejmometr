<?
  return $this->DB->selectValue("SELECT id FROM dokumenty WHERE pobrano='3' ORDER BY data_dodania ASC LIMIT 1");
?>