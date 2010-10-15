<?
  $q = "SELECT SQL_CALC_FOUND_ROWS id, numer FROM druki WHERE `ilosc_dokumentow`=0 ORDER BY data_dodania DESC LIMIT $limit_start, $per_page";
  $data = $this->DB->selectAssocs($q);
?>