<?
  $id = $_PARAMS['id'];
  $result = array();
  
  
  
  $item = $this->DB->selectAssoc("SELECT poslowie.id, poslowie.imie, poslowie.drugie_imie, poslowie.nazwisko, poslowie.sejm_id, poslowie.nazwa, poslowie.data_wybrania, poslowie.lista, poslowie.okreg_nr, poslowie.liczba_glosow, poslowie.staz, poslowie.data_urodzenia, poslowie.stan_cywilny, poslowie.wyksztalcenie, poslowie.szkola, poslowie.zawod, poslowie.data_slubowania, poslowie.tytul, poslowie.data_wygasniecia, poslowie.klub, poslowie.image_url, poslowie.image_md5, ludzie.avatar FROM poslowie LEFT JOIN ludzie ON poslowie.id=ludzie.id WHERE poslowie.id='$id'");
  
  
  $zmiany = $this->DB->selectAssocs("SELECT id, typ, nazwa, wartosc, data_dodania FROM poslowie_pola WHERE posel_id='$id' AND akcept='0' ORDER BY id ASC");
  
  

  $result['item'] = $item;
  $result['zmiany'] = $zmiany;
  return $result;
?>