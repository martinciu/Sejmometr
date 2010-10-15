<?
  $id = $_PARAMS['id'];

  $wyrok = $this->DB->selectAssoc("SELECT wyroki_tk.id, wyroki_tk.data, wyroki_tk.numer, wyroki_tk.tytul, wyroki_tk.wynik, wyroki_tk.dokument_ogloszony as 'dokument_id', dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key FROM wyroki_tk LEFT JOIN dokumenty ON wyroki_tk.dokument_ogloszony=dokumenty.id WHERE wyroki_tk.id='$id'");
  
  
  $result['wyrok'] = $wyrok;
  return $result;
?>