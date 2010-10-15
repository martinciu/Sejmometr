<?
  return $this->DB->selectRows("SELECT id, dopelniacz FROM druki_autorzy WHERE dopelniacz!='' ORDER BY autor ASC");
?>