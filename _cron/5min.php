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
  
  echo "głosowania - głosy\n";
  $M->S('graber/posiedzenia/glosowania/pobierz_kluby');
  
  
  $i = 0;
  while( $id = $M->DB->selectValue("SELECT id FROM wypowiedzi WHERE typ='1' AND _temp='0'") ) {
    $i++;
    $M->DB->update_assoc('wypowiedzi', array('_temp'=>'2'), $id);
    $skrot = $M->S('wypowiedzi/skrot', $id);
    $M->DB->update_assoc('wypowiedzi', array('skrot'=>addslashes($skrot), '_temp'=>'3'), $id);
  }
  if($i) $M->S('liczniki/nastaw/wypowiedzi');
  
  $i = 0;
  while( $id = $M->DB->selectValue("SELECT id FROM punkty_wypowiedzi WHERE status='0'") ) {
    $i++;
    $M->DB->update_assoc('punkty_wypowiedzi', array('status'=>'1'), $id);
    $opis = $M->S('debaty/info', $id);
    $M->DB->update_assoc('punkty_wypowiedzi', array('opis'=>addslashes($opis), 'status'=>'2'), $id);
  }
  if($i) $M->S('liczniki/nastaw/punkty_wypowiedzi');
  
 
  
  
  echo "głosowania - rozpoznowanie posłów\n";
  $M->S('graber/glosowania/rozpoznawanie/wszystkie');
 
?>