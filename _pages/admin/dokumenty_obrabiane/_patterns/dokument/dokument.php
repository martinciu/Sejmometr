<?
  function sm_pobrano_status($status){
    switch( $status ) {
      case '0': return 'Oczekiwanie na pobranie pliku ze źródła';
      case '1': return '<span class="error">Przekroczono maksymalny czas pobierania pliku ze źródła</span>';
      case '2': return '<span class="ok">Plik został pobrany</span>';
      case '3': return '<span class="error">Plik nie został znaleziony</span>';
    }
  }
  
  function sm_obraz_status($status){
    switch( $status ) {
      case '0': return 'Obraz nie jest jeszcze przygotowywany';
      case '1': return 'Oczekiwanie na przerobienie Automatorem';
      case '2': return 'Oczekiwanie na przetworzenie plików Automatora';
      case '3': return '<span class="ok">Obraz został dołączony</span>';
    }
  }
  
  function sm_scribd_status($status){
    switch( $status ) {
      case '0': return 'Scribd nie jest jeszcze przygotowywany';
      case '1': return 'Oczekiwanie na przesłanie plików do Scribd';
      case '2': return '<span class="error">Przekroczono maksymalny czas przesyłania pliku do Scribd, lub plik nie został przyjęty</span>';
      case '3': return 'Oczekiwanie na stwierdzenie poprawności przesyłu';
      case '4': return '<span class="error">Błąd przetwarzania Scribd</span>';
      case '5': return '<span class="ok">Scribd został utworzony</span>';
    }
  }
  
  $this->SMARTY->register_modifier('pobrano_status', 'sm_pobrano_status');
  $this->SMARTY->register_modifier('obraz_status', 'sm_obraz_status');
  $this->SMARTY->register_modifier('scribd_status', 'sm_scribd_status');

  $id = $_PARAMS['id'];
  $category = $_PARAMS['c'];

  $data = $this->DB->selectAssoc("SELECT id, plik, scribd_doc_id, pobrano, obraz, scribd, download_url FROM dokumenty WHERE id='$id'");
  return $data;
?>