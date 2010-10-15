<?
	if( isset($_REQUEST['address']) && trim($_REQUEST['address'])!='' ) {
	  
	  $adres = trim($_REQUEST['address']);
    $ip = $_SERVER['REMOTE_ADDR'];
	  
	  $this->DB->query("INSERT INTO otwarcie_adresy (`adres`, `ip`) VALUES ('$adres', '$ip')");
	  header("Location: /?dodano=1");
	}
?>