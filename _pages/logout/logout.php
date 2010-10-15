<?  
  $this->logout();
  header('Location: '.SITE_ADDRESS.$_REQUEST['next']);
  die();
?>