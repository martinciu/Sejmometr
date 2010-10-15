<?
  $id = $_PARAMS['id'];
  
  $dzien = $this->DB->selectAssoc("SELECT posiedzenia_dni.id, posiedzenia_dni.sejm_id, posiedzenia_dni.data, posiedzenia_dni.posiedzenie_id, posiedzenia_dni.status, posiedzenia_dni.analiza_wystapienia, posiedzenia.numer as 'posiedzenie_numer' FROM posiedzenia_dni LEFT JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE posiedzenia_dni.id='$id'");
  $statusy = $this->DB->selectValues("SELECT DISTINCT(status) FROM `dni_modele` WHERE `dzien_id`='$id' AND `typ`='1'");  

  $punkty_wypowiedzi = $this->DB->selectAssocs("SELECT id, sejm_id, ilosc_wypowiedzi FROM punkty_wypowiedzi WHERE dzien_id='$id' ORDER BY ord ASC");
  $punkty_glosowania = $this->DB->selectAssocs("SELECT id, sejm_id, ilosc_glosowan FROM punkty_glosowania WHERE dzien_id='$id' AND aktywny='1' ORDER BY ord ASC");
  
  return array(
    'dzien' => $dzien,
    'statusy' => $statusy,
    'punkty_wypowiedzi' => $punkty_wypowiedzi,
    'punkty_glosowania' => $punkty_glosowania,
  );
?>