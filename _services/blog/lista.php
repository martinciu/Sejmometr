<?
  return $this->DB->selectAssocs("SELECT id, url_title, tytul, DATE(data_zapisania) as 'data' FROM blog ORDER BY data_zapisania DESC");
?>