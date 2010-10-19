{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'BAS', {loader: 'bas', categories: [{id: 'zaakceptowane', label: 'Zaakceptowane', where: "`akcept`='1'"}, {id: 'doakceptu', label: 'Do akceptu', where: "`akcept`='0'"}], 'url_controll': true, 'item_pattern':'druk', 'default_category': 1});
	});
{/literal}