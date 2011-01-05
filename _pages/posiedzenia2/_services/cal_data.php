<?
  list( $year, $month ) = $_PARAMS;
  $year = (int) $year;
  $month = (int) $month;
  $result = array();
  
  $min = mktime(0, 0, 0, $month-12, 1, $year);
  $max = mktime(0, 0, 0, $month+12, 1, $year);
  
  $data = array();
  for( $i=-12; $i<12; $i++ ){
    $time = mktime(0, 0, 0, $month-$i, 1, $year);
    $data[ date('Y', $time).'-'.date('m', $time) ] = array();
  }
  
  $min_date = date('Y', $min).'-'.date('m', $min).'-01';
  $max_date = date('Y', $max).'-'.date('m', $max).'-01';
  
  
  $posiedzenia = array();
  $dni = $this->DB->selectAssocs("SELECT posiedzenia_dni.id, posiedzenia_dni.data, posiedzenia_dni.posiedzenie_id, posiedzenia.numer FROM posiedzenia_dni JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE posiedzenia.pokazuj='1' AND posiedzenia_dni.data>='$min_date' AND posiedzenia_dni.data<'$max_date' ORDER BY posiedzenia_dni.data ASC, posiedzenia_dni.posiedzenie_id ASC");
  
  foreach( $dni as $dzien ) {
    
    $m = substr($dzien['data'], 0, 7);
    $data[ $m ][] = $dzien;
    
  }  
  
  $result['data'] = $data;
  $result['year'] = $year;
  $result['month'] = $month;
  
  return $result;
?>