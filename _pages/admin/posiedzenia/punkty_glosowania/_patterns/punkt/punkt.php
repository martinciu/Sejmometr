<?
  $id = $_PARAMS['id'];
  
  function mark_druki($text){
    if( preg_match_all('/\((.*?)\)/i', $text, $matches) ) {
      for( $i=0; $i<count($matches[0]); $i++ ) {
        $a = $matches[1][$i];
        $s = $matches[1][$i];
        if( preg_match_all('/([0-9])+/', $s, $m) ) {
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
  
  $result['punkt'] = $this->DB->selectAssoc("SELECT punkty_glosowania.id, punkty_glosowania.typ_id, punkty_glosowania.sejm_id, punkty_glosowania.ilosc_glosowan, posiedzenia.id as 'posiedzenie_id', posiedzenia_dni.data, posiedzenia.numer as posiedznie_numer FROM punkty_glosowania LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id LEFT JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE punkty_glosowania.id='$id'");
    
  $druki_data = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM punkty_glosowania_druki LEFT JOIN druki ON punkty_glosowania_druki.druk_id=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE punkty_glosowania_druki.punkt_id='$id'");
  
  $result['punkt']['druki'] = array();
  if( is_array($druki_data) ) foreach( $druki_data as $druk ) $result['punkt']['druki'][] = $druk['id'];
  $result['druki_data'] = $druki_data;
  
  
  
  
  
  $data = $this->DB->selectRows("SELECT dzien_id, MIN(ord), MAX(ord) FROM `wypowiedzi` WHERE glosowanie_id IN (SELECT id FROM glosowania WHERE punkt_id='$id') ORDER BY ord ASC");
  if( count($data)!=1 ) { 
    $result['wypowiedzi'] = 'ERROR';
  } else {
	  
	  $data = $data[0];
	  list($dzien_id, $ord_min, $ord_max) = $data;
	  $ord_max++;
	  	  
	  $q = "SELECT wypowiedzi.id, wypowiedzi.autor_id, wypowiedzi.text, wypowiedzi.funkcja, ludzie.imie, ludzie.nazwisko FROM `wypowiedzi` LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id WHERE wypowiedzi.dzien_id='$dzien_id' AND wypowiedzi.ord>='$ord_min' AND wypowiedzi.ord<='$ord_max' ORDER BY wypowiedzi.ord ASC LIMIT 100";
	  $result['wypowiedzi'] = $this->DB->selectAssocs($q);
	    
	  
	  foreach( $result['wypowiedzi'] as &$item ) {
	    $item['imie'] = (string) $item['imie'];
	    $item['nazwisko'] = (string) $item['nazwisko'];
	    $item['text'] = mark_druki( $item['text'] );
	  }
  
  }
  
  
  return $result;
?>