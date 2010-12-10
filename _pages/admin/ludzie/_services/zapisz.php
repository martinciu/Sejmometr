<?
  $id = $_PARAMS['id'];
  
  $this->DB->update_assoc('ludzie', array(
    'nazwa' => $_PARAMS['nazwa'],
    'fraza' => $_PARAMS['fraza'],
    'avatar' => $_PARAMS['avatar'],
    'akcept' => '1',
  ), $id);
  
  $this->S('liczniki/nastaw/ludzie');
  $this->DB->q("UPDATE wypowiedzi JOIN punkty_wypowiedzi ON wypowiedzi.punkt_id = punkty_wypowiedzi.id SET punkty_wypowiedzi.status='0' WHERE wypowiedzi.autor_id='$id'");
  return 4;
?>