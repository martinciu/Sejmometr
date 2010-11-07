<?
  $this->DB->q("INSERT INTO zadania_zlecenia (zadanie) VALUES ('poslowie/uaktualnij_glosowania')");
  $zlecenie_id = $this->DB->insert_id;
  $this->DB->q("INSERT IGNORE INTO zadania (zlecenie, zadanie, input) (SELECT $zlecenie_id, 'poslowie/uaktualnij_glosowania', id FROM poslowie WHERE aktywny='1')");
?>