<?
  list( $year, $month, $day ) = $_PARAMS;
  $result = array();

	$result['projekty'] = $this->DB->selectAssocs("SELECT projekty.id, projekty.typ_id, projekty.data_wplynal as 'data', projekty.tytul, druki_autorzy.autor, druki.numer, dokumenty.id as 'dokument_id' FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE projekty.akcept='1' AND YEAR(projekty.data_wplynal)=$year AND MONTH(projekty.data_wplynal)=$month ORDER BY data_wplynal ASC");

  $result['dni'] = $this->DB->selectAssocs("SELECT posiedzenia_dni.id, posiedzenia_dni.data, posiedzenia_dni.posiedzenie_id, posiedzenia.numer FROM posiedzenia_dni JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE YEAR(posiedzenia_dni.data)=$year AND MONTH(posiedzenia_dni.data)=$month ORDER BY posiedzenia_dni.data ASC");
  
  $result['posiedzenia'] = $this->DB->selectValues("SELECT posiedzenie_id FROM posiedzenia_dni WHERE YEAR(data)=$year AND MONTH(data)=$month GROUP BY posiedzenie_id ORDER BY data ASC");

  
  $result['year'] = $year;
  $result['month'] = $month;
  $result['day'] = $day;
  
  return $result;
?>