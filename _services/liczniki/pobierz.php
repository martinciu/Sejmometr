<?
  $result = array();
  $result['data'] = $this->DB->selectAssocs("SELECT id, nazwa, href, licznik, data_aktualizacji FROM liczniki ORDER BY nazwa ASC");
  $result['count'] = $this->DB->selectCount("SELECT SUM(licznik) FROM liczniki");
  return $result;
?>