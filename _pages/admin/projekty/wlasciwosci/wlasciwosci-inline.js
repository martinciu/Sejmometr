{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Projekty', {loader: 'projekty-wlasciwosci', categories: [{label: 'Zaakceptowane', id: 'zaakceptowane'}, {id: 'doakceptu', label: 'Do akceptu', where: "`akcept`='0'"}, {label: 'Skasowane', id: 'skasowane'}], 'url_controll': true, 'item_pattern':'projekt', 'default_category': 1});
	});
{/literal}
	var _projekty_typy_options = {$_projekty_typy|@json_encode};
	var _autorzy_options = {$_autorzy_options|@json_encode};
