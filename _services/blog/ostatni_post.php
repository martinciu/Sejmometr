<?
  return $this->DB->selectAssoc("SELECT id, tytul, opis, tresc, autor, data_zapisania FROM blog WHERE robocza='0' ORDER BY data_zapisania DESC LIMIT 1");
?>