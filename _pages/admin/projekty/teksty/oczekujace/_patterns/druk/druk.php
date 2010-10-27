<?
  $id = $_PARAMS['id'];
  $category = $_PARAMS['c'];
  
  $result = $this->DB->selectAssoc("SELECT id, numer, dokument_id, sejm_id FROM druki WHERE id='$id'");
  $result['dokumenty'] = $this->DB->selectAssocs("SELECT id, scribd_doc_id, scribd_access_key FROM dokumenty WHERE (typ='druk' AND gid='$id')");
  
  return $result;
?>