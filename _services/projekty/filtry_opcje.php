<?
  require_once( ROOT.'/_components/getlink/getlink.php' );
  list($filtr_id, $typ_id, $w) = $_PARAMS;
    
  $_typy_schematy = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '1',
    '5' => '3',
    '7' => '2',
    '8' => '3',
    '10' => '3',
    '11' => '3',
    '12' => '2',
  );

  $_statusy = array(
    '1' => array(
      '1' => 'Przed pierwszym czytaniem',
      '2' => 'Rozpatrywane w Sejmie',
      '3' => 'Rozpatrywane w Senacie',
      '4' => 'Rozpatrywane przez Prezydenta',
      '7' => 'Rozpatrywane w Trybunale Konstytucyjnym',
      '5' => 'Przyjęte',
      '6' => 'Odrzucone',
    ),
    '2' => array(
      '1' => 'Przyjęte',
      '2' => 'Rozpatrywane',
      '3' => 'Odrzucone',
    ),
    '3' => array(
      '1' => 'Przyjęte',
      '2' => 'Rozpatrywane',
      '3' => 'Odrzucone',
    ),
  );
  
  $schemat = $_typy_schematy[$typ_id];
  $statusy = $_statusy[$schemat];
  
  
     
  switch( $filtr_id ) {
    
    case 'autor': {
      $result = $this->DB->selectAssocs("SELECT projekty.autor_id as 'id', druki_autorzy.autor as 'tytul', COUNT(*) as 'ilosc' FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE $w GROUP BY projekty.autor_id ORDER BY ilosc DESC");
      break;
    }
    
    case 'status': {
      $result = $this->DB->selectAssocs("SELECT projekty.status as 'id', COUNT(*) as 'ilosc' FROM projekty WHERE $w GROUP BY projekty.status ORDER BY projekty.status ASC");
      foreach( $result as &$item ) $item['tytul'] = $statusy[ $item['id'] ];
      break;
    }
    
  }
  
  foreach( $result as &$r ) {
    $r['href'] = sf_getlink(array(
      $filtr_id => $r['id'],
      '_reset' => 'str',
    ));
  }
  
  
  return $result;
?>