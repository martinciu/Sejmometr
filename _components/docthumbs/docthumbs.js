function docthumbs_create(id, size, params){
  var img = new Element('img', {src: '/d/'+size+'/'+id+'.gif'});
  var obj = $ANCHOR(img, {className: 'docthumbs_a '+['','A','B','C','D','E'][size], docid: id});
  
  if( params && params.lfloat ) obj.addClassName('lfloat');
  
  return obj;
}