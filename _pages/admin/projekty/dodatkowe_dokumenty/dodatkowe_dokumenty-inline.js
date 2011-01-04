{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Dodatkowe dokumenty', {loader: 'dodatkowe_dokumenty', categories: [{label: 'Zaakceptowane', id: 'zaakceptowane'}, {label: 'Do akceptu', id: 'doakceptu'}], 'url_controll': true, 'item_pattern':'item', 'default_category': 1});
	});
{/literal}