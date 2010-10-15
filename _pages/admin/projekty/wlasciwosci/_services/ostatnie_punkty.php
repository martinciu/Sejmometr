<?
  function mark_druki($text){
    if( preg_match_all('/\((.*?)\)/i', $text, $matches) ) {
      for( $i=0; $i<count($matches[0]); $i++ ) {
        $a = $matches[1][$i];
        $s = $matches[1][$i];
        if( preg_match_all('/([A-F0-9\-])+/', $s, $m) ) {
          for( $j=0; $j<count($m); $j++ ) {
            $s = str_ireplace($m[0][$j], '<span class="_druk" numer="'.$m[0][$j].'">'.$m[0][$j].'</span>', $s);
          }
        }
        $text = str_replace($a, $s, $text);
      }
    }
    
    $text = preg_replace('/druku nr ([0-9\-A]+)/i', '<span class="_druk" numer="$1">druku nr $1</span>', $text);
    $text = preg_replace('/druk nr ([0-9\-A]+)/i', '<span class="_druk" numer="$1">druku nr $1</span>', $text);
    
    return $text;
  }

  list( $posiedzenie_id, $data ) = $_PARAMS;
  
  $result= $this->DB->selectRows("SELECT posiedzenia_dni.data, punkty_wypowiedzi.sejm_id FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id = posiedzenia_dni.id WHERE dzien_id IN (SELECT id FROM `posiedzenia_dni` WHERE (`posiedzenie_id`='".$posiedzenie_id."' AND DATA <= '".$data."') ) ORDER BY posiedzenia_dni.data DESC , punkty_wypowiedzi.ord DESC");
  for( $i=0; $i<count($result); $i++ ) {
    $result[$i][1] = mark_druki($result[$i][1]);
  }
  
  return $result;
?>