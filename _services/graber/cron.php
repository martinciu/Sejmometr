<?
  $actions = $this->DB->selectAssocs( "SELECT action, initDate, freq, hourIn, hourOut FROM graber_schedule WHERE `enabled`='1'", 'assoc', false );
    
  $z = floor( time()/300 );
  for( $i=0; $i<count($actions); $i++ ){
    $hourIn = $actions[$i]['hourIn'];
    $hourOut = $actions[$i]['hourOut'];
    
    $g = (int) date('G');
    if( ( ($hourIn==0) && ($hourOut==0) ) || ( ($g>=$hourIn) && ($g<=$hourOut) ) ) {
      $action = $actions[$i]['action'];
      $initDate = $actions[$i]['initDate'];
      $freq = $actions[$i]['freq'];
      if( (($z-$initDate)%$freq)==0 ) { $this->S('graber/actions_stack/push', $action); }  
    }      
  }
    
  for( $_i=0; $_i<5; $_i++ ) {
    $action = $this->S('graber/actions_stack/shift');
    if( $action!==false ) {      
      $this->DB->q("UPDATE `graber_schedule` SET `lastRun`=NOW() WHERE action='$action'");
      switch( $action ) {
        case 'druki/pobierz_nowe': { $this->service('graber/druki/pobierz_nowe'); break; }
        case 'druki/sprawdz_ostatnie': { $this->service('graber/druki/sprawdz_ostatnie'); break; }
        case 'druki/sprawdz_oczekujace': { $this->service('graber/druki/sprawdz_oczekujace'); break; }
        case 'posiedzenia/pobierz_nowe': { $this->service('graber/posiedzenia/pobierz_nowe'); break; }
        case 'posiedzenia/sprawdz_ostatnie': { $this->service('graber/posiedzenia/sprawdz_ostatnie'); break; }
        case 'projekty/dodaj_nowe': { $this->service('graber/projekty/dodaj_nowe'); break; }
        case 'projekty/sprawdz_ostatnie': { $this->service('graber/projekty/sprawdz_ostatnie'); break; }
        case 'glosowania_pp/pobierz_nowe_dni': { $this->service('graber/glosowania_pp/pobierz_nowe_dni'); break; }
        case 'poslowie/sprawdz': { $this->service('graber/poslowie/sprawdz'); break; }
        case 'komisje/dodaj_nowe': { $this->service('graber/komisje/dodaj_nowe'); break; }
        case 'interpelacje/dodaj_nowe': { $this->service('graber/inderpelacje/dodaj_nowe'); break; }
        
      }
    }
  }
?>