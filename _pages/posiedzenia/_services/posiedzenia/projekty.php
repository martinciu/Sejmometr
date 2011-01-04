<?
  $t_start = microtime();
  $id = $_PARAMS;
  $data = $this->S('posiedzenia/mapa/pobierz', $id);
  
  
  
  $items = array();
  $data['projekty'] = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty.autor_id, druki_autorzy.autor, druki.dokument_id, druki.numer, projekty.opis FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.id='".implode("' OR projekty.id='", $data['projekty'])."'");
  foreach( $data['projekty'] as $item ) $items[ $item['id'] ] = $item;
  $data['projekty'] = $items;
  
  
  
  $items = array();
  $data['debaty'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, punkty_wypowiedzi.opis, posiedzenia_dni.data FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.id='".implode("' OR punkty_wypowiedzi.id='", $data['debaty'])."'");
  foreach( $data['debaty'] as $item ) $items[ $item['id'] ] = $item;
  $data['debaty'] = $items;
  
  
 
  $data['pytania_biezace'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.typ_id=2 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data DESC, punkty_wypowiedzi.ord DESC");
  
  $data['informacje_biezace'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.typ_id=3 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data DESC, punkty_wypowiedzi.ord DESC");
 
   $data['specjalne'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, debaty_specjalne.tytul, punkty_wypowiedzi.opis, posiedzenia_dni.data FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id JOIN debaty_specjalne ON punkty_wypowiedzi.id=debaty_specjalne.id WHERE debaty_specjalne.akcept='1' AND punkty_wypowiedzi.typ_id=4 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data DESC, punkty_wypowiedzi.ord DESC");
    
  $data['oswiadczenia'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.typ_id=6 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data DESC, punkty_wypowiedzi.ord DESC");
  

  
  
  $t_end = microtime();
  $data['time'] = ($t_end - $t_start);
  return $data;
?>