<?
  $t_start = microtime();
  $id = $_PARAMS;
  $data = $this->S('posiedzenia/mapa/pobierz_beta', $id);
  
  // var_export($data); die();
  
  
  $items = array();
  $data['projekty'] = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty.autor_id, druki_autorzy.autor, druki.dokument_id, druki.numer, projekty.opis FROM projekty LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.id='".implode("' OR projekty.id='", $data['projekty'])."'");
  foreach( $data['projekty'] as $item ) $items[ $item['id'] ] = $item;
  $data['projekty'] = $items;
  
  
  
 
  $data['pytania_biezace'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.typ_id=2 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC");
  
  $data['informacje_biezace'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi, informacje_biezace.klub_id, informacje_biezace.tytul, druki_autorzy.autor FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id JOIN informacje_biezace ON punkty_wypowiedzi.id=informacje_biezace.id JOIN druki_autorzy ON informacje_biezace.klub_id=druki_autorzy.id WHERE informacje_biezace.akcept='1' AND punkty_wypowiedzi.typ_id=3 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC");
 
   $data['specjalne'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, debaty_specjalne.tytul, punkty_wypowiedzi.opis, posiedzenia_dni.data FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id JOIN debaty_specjalne ON punkty_wypowiedzi.id=debaty_specjalne.id WHERE debaty_specjalne.akcept='1' AND punkty_wypowiedzi.typ_id=4 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC");
    
  $data['oswiadczenia'] = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.typ_id=6 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC");
  

  
  
  $t_end = microtime();
  $data['time'] = ($t_end - $t_start);
  return $data;
?>