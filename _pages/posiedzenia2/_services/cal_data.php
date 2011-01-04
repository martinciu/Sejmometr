<?
  list( $year, $month ) = $_PARAMS;
  $year = (int) $year;
  $month = (int) $month;
  $result = array();

  $posiedzenia = array();
  $dni = $this->DB->selectAssocs("SELECT posiedzenia_dni.id, posiedzenia_dni.data, posiedzenia_dni.posiedzenie_id, posiedzenia.numer FROM posiedzenia_dni JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE posiedzenia.specjalne='0' AND YEAR(posiedzenia_dni.data)=$year AND MONTH(posiedzenia_dni.data)=$month ORDER BY posiedzenia_dni.data ASC, posiedzenia_dni.posiedzenie_id ASC");
  
  foreach( $dni as $dzien ) if( !in_array($dzien['posiedzenie_id'], $posiedzenia) ) $posiedzenia[] = $dzien['posiedzenie_id'];  
  
  $result['dni'] = $dni;
  $result['posiedzenia'] = $posiedzenia;

  
  $result['year'] = $year;
  $result['month'] = $month;
  
  return $result;
?>