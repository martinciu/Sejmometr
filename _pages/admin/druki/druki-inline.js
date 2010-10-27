{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Druki', {loader: 'druki', categories: [{label: 'Zaakceptowane', id: 'zaakceptowane'}, {label: 'Do akceptu', id: 'doakceptu'}, {id: 'nieprzypisane', label: 'Nieprzypisane'}, {id: 'powtorzone_projekty', label: 'Powtórzone projekty'}], 'url_controll': true, 'item_pattern':'druk', 'default_category': 1});
	});
{/literal}
	var _druki_typy = {$_druki_typy|@json_encode};
	var _autorzy_options = {$_autorzy_options|@json_encode};
