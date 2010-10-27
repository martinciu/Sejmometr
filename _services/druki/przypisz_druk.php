<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;

  $projekty = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_druki WHERE druk_id='$id'");
  $przypisany = ($projekty==0) ? '0' : '1';
  $this->DB->update_assoc('druki', array('przypisany'=>$przypisany), $id);
  
  $zalaczniki = $this->DB->selectValues("SELECT zalacznik FROM druki_zalaczniki WHERE druk='$id'");
  foreach( $zalaczniki as $zalacznik_id ) $this->S('druki/przypisz_zalacznik', $zalacznik_id);
  
  $this->S('liczniki/nastaw/druki_nieprzypisane');
?>