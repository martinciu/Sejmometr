<?
  $kluby = $this->DB->selectValues("SELECT id FROM kluby");
  foreach( $kluby as $klub ) $this->S('kluby/stats', $klub);
?>