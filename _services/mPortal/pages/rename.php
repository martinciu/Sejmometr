<?
  list( $old_id, $new_id ) = $_PARAMS;
  if( empty($old_id) || empty($new_id) ) return 0;
  
  $this->DB->q("UPDATE ".DB_TABLE_pages." SET id='$new_id' WHERE id='$old_id'");
  $this->DB->q("UPDATE ".DB_TABLE_pages_access." SET page='$new_id' WHERE page='$old_id'");
  $this->DB->q("UPDATE ".DB_TABLE_pages_components." SET page='$new_id' WHERE page='$old_id'");
  $this->DB->q("UPDATE ".DB_TABLE_pages_libs." SET page='$new_id' WHERE page='$old_id'");
  $this->DB->q("UPDATE ".DB_TABLE_services." SET page='$new_id' WHERE page='$old_id'");
  $this->DB->q("UPDATE ".DB_TABLE_services_access." SET page='$new_id' WHERE page='$old_id'");
?>