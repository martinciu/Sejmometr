{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Wypowiedzi', {loader: 'wypowiedzi', categories: [{label: 'Zaakceptowane', id: "zaakceptowane"}, {id: 'doakceptu', label: 'Do akceptu'}, {id: 'problemy', label: 'Problemy'}, {id: 'bezautora', label: 'Bez autora'}], 'url_controll': true, 'item_pattern':'item', 'default_category': 1});
	});
{/literal}