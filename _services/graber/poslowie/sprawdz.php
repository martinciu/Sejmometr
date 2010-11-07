<?
  return $this->S('graber/poslowie/pobieranie/pobierz', $this->DB->selectValue("SELECT id FROM poslowie ORDER BY data_sprawdzenia LIMIT 1"));
?>