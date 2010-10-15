<?
  $id = $_PARAMS['id'];
  
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
  
  $result['punkt'] = $this->DB->selectAssoc("SELECT punkty_wypowiedzi.id, punkty_wypowiedzi.typ_id, punkty_wypowiedzi.sejm_id, punkty_wypowiedzi.ilosc_wypowiedzi, posiedzenia.id as 'posiedzenie_id', posiedzenia_dni.data, posiedzenia.numer as posiedznie_numer FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id LEFT JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE punkty_wypowiedzi.id='$id'");
    
  $druki_data = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM punkty_wypowiedzi_druki LEFT JOIN druki ON punkty_wypowiedzi_druki.druk_id=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE punkty_wypowiedzi_druki.punkt_id='$id'");
  
  $result['punkt']['druki'] = array();
  if( is_array($druki_data) ) foreach( $druki_data as $druk ) $result['punkt']['druki'][] = $druk['id'];
  $result['druki_data'] = $druki_data;
  
  
  list($dzien_id, $ord) = $this->DB->selectRow("SELECT dzien_id, ord FROM `wypowiedzi` WHERE punkt_id='$id' ORDER BY ord ASC LIMIT 1");
  $result['wypowiedzi'] = $this->DB->selectAssocs("SELECT wypowiedzi.id, wypowiedzi.autor_id, wypowiedzi.text, wypowiedzi.funkcja, ludzie.imie, ludzie.nazwisko FROM `wypowiedzi` LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id WHERE wypowiedzi.punkt_id='$id' OR ( wypowiedzi.dzien_id='$dzien_id' AND wypowiedzi.ord+1=$ord ) ORDER BY wypowiedzi.ord ASC LIMIT 10");
  foreach( $result['wypowiedzi'] as &$item ) {
    $item['imie'] = (string) $item['imie'];
    $item['nazwisko'] = (string) $item['nazwisko'];
    $item['text'] = mark_druki( $item['text'] );
  }
      
  return $result;
?>