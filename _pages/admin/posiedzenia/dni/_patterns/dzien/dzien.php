<?
  $id = $_PARAMS['id'];
  
  $statusy_analizy_wystapienia = array(
    '0' => 'Nieprzetworzone',
    '1' => 'Trwa przetwarzanie',
    '2' => 'Błędy rozpoznawania autorów',
    '3' => 'Wypowiedzi z tego dnia są już zapisane',
    '4' => 'OK',
    '5' => 'Błąd statusów',
    '6' => 'Dzień został skasowany',
    '7' => 'Dzień został zmieniony',
  );
  
  $statusy_analizy_glosowania = array(
    '0' => 'Nieprzetworzone',
    '1' => 'Trwa przetwarzanie',
    '2' => 'Nieznany błąd',
    '3' => 'Głosowania z tego dnia są już zapisane',
    '4' => 'OK',
    '5' => 'Błąd statusów',
  );
  
  
  $dzien = $this->DB->selectAssoc("SELECT posiedzenia_dni.id, posiedzenia_dni.sejm_id, posiedzenia_dni.data, posiedzenia_dni.posiedzenie_id, posiedzenia_dni.status, posiedzenia_dni.analiza_wystapienia, posiedzenia_dni.analiza_glosowania, posiedzenia.numer as 'posiedzenie_numer' FROM posiedzenia_dni LEFT JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE posiedzenia_dni.id='$id'");
  
  $dzien['analiza_wystapienia_slownie'] = $statusy_analizy_wystapienia[ $dzien['analiza_wystapienia'] ];
  $dzien['analiza_glosowania_slownie'] = $statusy_analizy_glosowania[ $dzien['analiza_glosowania'] ];
  
  
  
  $statusy_wypowiedzi = $this->DB->selectRows("SELECT status, COUNT(*) FROM `dni_modele` WHERE (`dzien_id`='$id' AND `typ`='1') GROUP BY status ORDER BY status ASC");  
  $statusy_glosowania = $this->DB->selectRows("SELECT status, COUNT(*) FROM `glosowania` WHERE `dzien_id`='$id' GROUP BY status ORDER BY status ASC");  

  $punkty_wypowiedzi = $this->DB->selectAssocs("SELECT id, sejm_id, ilosc_wypowiedzi FROM punkty_wypowiedzi WHERE dzien_id='$id' ORDER BY ord ASC");
  $punkty_glosowania = $this->DB->selectAssocs("SELECT id, sejm_id, ilosc_glosowan FROM punkty_glosowania WHERE dzien_id='$id' AND aktywny='1' ORDER BY ord ASC");
  
  return array(
    'dzien' => $dzien,
    'statusy_wypowiedzi' => $statusy_wypowiedzi,
    'statusy_glosowania' => $statusy_glosowania,
    'punkty_wypowiedzi' => $punkty_wypowiedzi,
    'punkty_glosowania' => $punkty_glosowania,
  );
?>