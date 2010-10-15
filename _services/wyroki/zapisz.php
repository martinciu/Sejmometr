<?
	/* return codes
	  
	  2 - wrong id
	  3 - not saved
	  4 - saved
	  
	*/

  $id = $_PARAMS['id'];
  if( strlen($id)!=5 ) { return 2; }
  
  $wyrok = array(
    'data' => $_PARAMS['data'],
    'wynik' => (int) $_PARAMS['wynik'],
    'akcept' => 1,
    'data_akceptacji' => 'NOW()',
  );
  
  $this->DB->update_assoc('wyroki_tk', $wyrok, $id);
  $this->S('liczniki/nastaw/wyroki');

  return $this->DB->affected_rows ? 4 : 3;
?>