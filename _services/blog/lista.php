<?
  return $this->DB->selectAssocs("SELECT id, tytul, opis, tresc, autor, DATE(data_zapisania) as 'data_zapisu' FROM blog ORDER BY data_zapisania DESC");
?>