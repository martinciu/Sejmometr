<?
  $id = $_PARAMS;
  if( !is_numeric($id) ) return false;
  
  $this->DB->q("DELETE FROM blog WHERE id='$id'");
  return $this->DB->affected_rows;
?>