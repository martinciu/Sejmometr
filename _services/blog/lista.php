<?
  return $this->DB->selectAssocs("SELECT id, url_title, tytul, DATE(data_zapisania) as 'data' FROM blog WHERE robocza='0' ORDER BY data_zapisania DESC");
?>