<?
	$_SERVER['DOCUMENT_ROOT'] = '/sejmometr/';
  require_once( $_SERVER['DOCUMENT_ROOT'].'/_lib/mPortal/REQUEST.php' );
  $M = new REQUEST(array(
    'DONT_VERIFY_USER' => true
  ));
  $DB = &$M->DB;
  $M->USER['group'] = '2';  
  
  
  
  // CRON
  echo "cron\n";
  $M->S('graber/cron');



  // ISAP
  echo "isap\n";
  $M->S('graber/isap/pobierz_wszystkie');



  // DOKUMENTY
  echo "dokumenty - pobieranie\n";
  $M->S('graber/dokumenty/pobieranie/pobierz_wszystkie_niepobrane');
  
  echo "dokumenty - obrazy\n";
  $M->S('graber/dokumenty/obrazy/pobierz_wszystkie_niepobrane');
  
  echo "dokumenty - scribd przesyłanie\n";
  $M->S('graber/dokumenty/scribd/przeslij_wszystkie_nieprzeslane');


    
  // POSIEDZENIA
  echo "posiedzenia - dni\n";
  $M->S('graber/posiedzenia/dni/pobierz_nowe');
  
  echo "posiedzenia - wypowiedzi\n";
  $M->S('graber/posiedzenia/wypowiedzi/pobierz_nowe');
  
  echo "posiedzenia - analizator - wypowiedzi\n";
  $M->S('graber/posiedzenia/analizator/analizuj_wszystkie_wypowiedzi');
  
  echo "posiedzenia - analizator - głosowania\n";
  $M->S('graber/posiedzenia/analizator/analizuj_wszystkie_glosowania');
  
  echo "posiedzenia - głosowania\n";
  $M->S('graber/posiedzenia/glosowania/pobierz_nowe');
  
  
  
  
  
  
  
  // DEBATY
  echo "debaty - generowanie opisów i banerów\n";
  $M->S('debaty/info_wszystkie');
  
  echo "wypowiedzi - generowanie skrótów\n";
  $M->S('wypowiedzi/skrot_wszystkie');
  
  
  // GŁOSOWANIA
  echo "głosowania - głosy\n";
  $M->S('graber/posiedzenia/glosowania/pobierz_kluby');
  
  echo "głosowania - rozpoznowanie posłów\n";
  $M->S('graber/glosowania/rozpoznawanie/wszystkie');
  
  
  
 
 
 
  // $M->S('poslowie/kluby_historia/przetworz_wszystkie');
?>