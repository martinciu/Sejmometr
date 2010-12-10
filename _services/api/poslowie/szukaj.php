<?
  $_sort_fields = array('nazwisko', 'liczba_glosow', 'data_urodzenia');
  $_sort_orders = array('asc', 'desc');
  
  $limit = (int) ($_REQUEST['limit']>0 && $_REQUEST['limit']<100 ) ? $_REQUEST['limit'] : 100;
  $sort_field = in_array($_REQUEST['sort_field'], $_sort_fields) ? $_REQUEST['sort_field'] : 'nazwisko';
  $sort_order = in_array($_REQUEST['sort_order'], $_sort_orders) ? $_REQUEST['sort_order'] : 'asc';  
  
  $offset = (int) $_REQUEST['offset'] ? $_REQUEST['offset'] : 0;
  $q = $_REQUEST['q'] ? $_REQUEST['q'] : '';
  
  $where = ($q=='') ? '1' : 'nazwa LIKE "%'.$q.'%"';
  
  
  return $this->DB->selectAssocs("SELECT id, aktywny, plec, imie, drugie_imie, nazwisko, nazwa, klub, lista, okreg_nr, liczba_glosow, data_urodzenia, miejsce_urodzenia, stan_cywilny, wyksztalcenie, zawod, image_url as 'avatar_url' FROM poslowie WHERE $where ORDER BY $sort_field $sort_order LIMIT $offset, $limit");
?>