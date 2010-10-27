{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Projekty, teksty', {loader: 'projekty_teksty', categories: [{label: 'Zaakceptowane', id: 'zaakceptowane'}, {label: 'Do akceptu', id: 'doakceptu'}], 'url_controll': true, 'item_pattern':'item', 'default_category': 1});
	});
{/literal}
