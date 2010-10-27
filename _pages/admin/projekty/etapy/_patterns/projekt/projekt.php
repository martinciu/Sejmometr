<?
  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) return false;
  
  $_etapy_typy = array('druk', 'czytanie_komisje', 'wypowiedzi', 'glosowania', 'skierowanie');

  $projekt = $this->DB->selectAssoc("SELECT projekty.id, projekty.sejm_id, projekty.druk_id, druki.numer, projekty.autor_id, projekty.typ_id, projekty.status_slowny, projekty.opis, projekty.html_zmiana FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.id='$id'");
  $projekt['html'] = @file_get_contents( ROOT.'/graber_cache/projekty/'.$id.'.html' );
 
  $druki = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM projekty_druki LEFT JOIN druki ON projekty_druki.druk_id=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE projekty_druki.projekt_id='$id' ORDER BY druki.data ASC");
  
  $punkty_wypowiedzi = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi, punkty_wypowiedzi.sejm_id FROM projekty_punkty_wypowiedzi LEFT JOIN punkty_wypowiedzi ON projekty_punkty_wypowiedzi.punkt_id=punkty_wypowiedzi.id LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE projekty_punkty_wypowiedzi.projekt_id='$id' ORDER BY posiedzenia_dni.data ASC, punkty_wypowiedzi.ord ASC");
  
  $punkty_glosowania = $this->DB->selectAssocs("SELECT punkty_glosowania.id, posiedzenia_dni.data, punkty_glosowania.ilosc_glosowan, punkty_glosowania.sejm_id, punkty_glosowania.pp FROM projekty_punkty_glosowania LEFT JOIN punkty_glosowania ON projekty_punkty_glosowania.punkt_id=punkty_glosowania.id LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id WHERE projekty_punkty_glosowania.projekt_id='$id' AND punkty_glosowania.aktywny='1' ORDER BY posiedzenia_dni.data ASC, punkty_glosowania.ord ASC");
  
  $isap = $this->DB->selectAssocs("SELECT isap.id, isap.sejm_id FROM projekty_isap LEFT JOIN isap ON projekty_isap.isap_id=isap.id WHERE projekty_isap.projekt_id='$id'");
  
  $wyroki = $this->DB->selectAssocs("SELECT wyroki_tk.id, wyroki_tk.sejm_id, wyroki_tk.data, wyroki_tk.wynik FROM projekty_wyroki LEFT JOIN wyroki_tk ON projekty_wyroki.wyrok_id=wyroki_tk.id WHERE projekty_wyroki.projekt_id='$id'");
  
  $etapy = $this->DB->selectAssocs("SELECT typ_id, subtyp, etap_id FROM projekty_etapy WHERE projekt_id='$id' ORDER BY ord ASC");
  for( $i=0; $i<count($etapy); $i++ ) {
    switch($etapy[$i]['typ_id']){
      case 1: {
        $etap_id = $etapy[$i]['etap_id'];
        $etapy[$i]['data'] = $this->DB->selectValue("SELECT data FROM czytania_komisje WHERE id='".$etap_id."'");
        $etapy[$i]['komisje'] = implode(',', $this->DB->selectValues("SELECT komisja_id FROM czytania_komisje_komisje WHERE czytanie_id='".$etap_id."'"));
        break;
      } 
      case 4: {
        $etap_id = $etapy[$i]['etap_id'];
        list($etapy[$i]['data'], $etapy[$i]['data_zalecenie']) = $this->DB->selectRow("SELECT data, data_zalecenie FROM skierowania WHERE id='".$etap_id."'");
        $etapy[$i]['adresat'] = implode(',', $this->DB->selectValues("SELECT autor_id FROM skierowania_adresaci WHERE skierowanie_id='".$etap_id."'"));
        break;
      }
      case 6: {
        $etap_id = $etapy[$i]['etap_id'];
        $etapy[$i]['data'] = $this->DB->selectValue("SELECT data FROM aklamacje WHERE id='".$etap_id."'");
        break;
      }
      case 7: {
        $etap_id = $etapy[$i]['etap_id'];
        $etapy[$i]['data'] = $this->DB->selectValue("SELECT data FROM punkty_wypowiedzi_bz WHERE id='".$etap_id."'");
        break;
      }
    }
  }
   
  return array(
    'projekt' => $projekt,
    'druki' => $druki,
    'punkty_wypowiedzi' => $punkty_wypowiedzi,
    'punkty_glosowania' => $punkty_glosowania,
    'etapy' => $etapy,
    'isap' => $isap,
    'wyroki' => $wyroki,
  );
?>