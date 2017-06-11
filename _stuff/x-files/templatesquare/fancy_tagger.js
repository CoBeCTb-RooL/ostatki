(function(global,doc,loc) {

// beep. bookmarklet is disallowed on Fancy.
if(/^(www\.)?fancy\.com$/.test(loc.hostname)) return alert("Fancy bookmarklet now installed.\nYou can now add things to your Fancy catalog from other sites around the web. Go give it a try!");

var host = 'http://fancy.com/bookmarklet/fancy.com', prefix = 'fancy-bookmarklet-tagger-',
    iframe  = {obj:null, id:prefix+'iframe', win: null, url:loc.protocol+'//'+host+'/bookmarklet/bookmarklet.html?v=0027'},
	marker  = {obj:null, id:prefix+'marker'}, // image marker
    style   = {url:loc.protocol+'//'+host+'/_ui/bookmarklet/tagger/css/bookmarklet.css?v=0007', id:prefix+'css'},
	factory = doc.createElement('div'),
	body    = doc.body,
	latest  = {img:null, images:[]};

extend(iframe, {
	show : function(){
		var im = doc.images, i, c, imgs=[], idx=-1, data, match, src, map={};
		if(doc.querySelectorAll) {
			im = [].slice.call(im);
			im = im.concat( [].slice.call(doc.querySelectorAll('[style*="background-image"]')) );
		}
		for(i=0,c=im.length;i<c;i++){
			match = /url\s*\("?(.+?)"?\)/.exec(im[i].style.backgroundImage);
			if((im[i].src || match) && im[i].offsetWidth > 150 && im[i].offsetHeight > 150) {
				src = match ? match[1] : im[i].src;
				if (!map[src]) {
					if(latest.img && latest.img === im[i]) idx = imgs.length;
					imgs.push(im[i]);
					im[i].setAttribute('data-fancy-src', src);
					map[src] = true;
				}
			}
		}
		latest.images = imgs;

		if(!latest.img || idx < 0) idx = 0;

		data = imageData(idx);

		this.obj.style.display = 'block';
		send(data);
	},
	hide : function(){
		this.obj.style.display = 'none';
		this.obj.setAttribute('src', 'about:blank');
	}
});

extend(marker, {
	show : function(){
		if(this.obj) this.obj.style.display = 'block';
	},
	hide : function(){
		if(this.obj) this.obj.style.display = 'none';
	}
});

var handlers = {
	doc : {
		keyup : function(event){
			event = global.event || event;
			if(event.keyCode != 27) return; // exit if pressed key isn't ESC

			iframe.hide();
		},
		mouseover : function(event){
			event = global.event || event;
			var el = event.target || event.srcElement, pos;

			if(!el.getAttribute('data-fancy-src')) return;

			latest.img = el;

			pos = offset(el);
			css(marker.obj, {top:pos.top+'px', left:pos.left+'px', width:el.offsetWidth+'px', height:el.offsetHeight+'px'});
			marker.show();
		}
	},
	marker : {
		click : function(event){
			event = global.event || event;

			try{
				event.preventDefault();
				event.stopPropagation();
			}catch(e){
				event.returnValue = false;
				event.cancelBubble = true;
			};

			iframe.show();
			marker.hide();
		},
		mouseout : function(event){
			event = window.event || event;
			var el = event.target || event.srcElement;
			if(el === marker.obj) marker.hide();
		}
	}
};

if('postMessage' in window){
	on(window, 'message', function(event){
		event = window.event || event;
		var args = unparam(event.data);
		onMessage(args);
	});
} else {
	var hash = '', hashTimer = null;
	(function(){
		if(loc.hash == hash || !/^#tagger:/.test(hash=loc.hash)) return hashTimer=setTimeout(arguments.callee, 100);
		var args = unparam(hash.replace(/^#tagger:/, ''));
		onMessage(args);
	})();
}

function onMessage(args){
	switch(args.cmd){
		case 'close':
			iframe.hide();
			tagger.cleanListeners();
			break;
		case 'resize':
			iframe.obj.style.height = args.h+'px';
			break;
		case 'index':
			args.idx = parseInt(args.idx);
 			data = imageData(args.idx);
			send(data);
			break;
	}
};

(function(){
	if(document.readyState !== 'complete') return setTimeout(arguments.callee, 100);

	// always create new iframe
	iframe.obj = elem(iframe.id);
	if(!iframe.obj) {
		factory.innerHTML = '<iframe id="'+iframe.id+'" allowtransparency="true" style="display:none;position:fixed;top:10px;right:10px;border:1px solid #4c515c;z-index:100001;margin:0;background:#eff1f7;width:279px;height:372px"></iframe>';
		iframe.obj = factory.lastChild;
		body.insertBefore(iframe.obj, body.firstChild);
		iframe.win = iframe.obj.contentWindow || iframe.obj;
	}
	iframe.show();

	// create a marker if it doesn't exist
	marker.obj = elem(marker.id);
	if(!marker.obj){
		factory.innerHTML = '<div id="'+marker.id+'" style="visibility:hidden;position:absolute;border:10px solid #8f0;z-index:100000;background:transparent url(http://s3.amazonaws.com/thefancy/_ui/images/f-plus.png) no-repeat 5px 5px"></div>';
		marker.obj = factory.lastChild;
		body.insertBefore(marker.obj, body.firstChild);

		css(marker.obj, {top:0, left:0});
		if(offset(marker.obj).top == 0) {
			css(marker.obj, {marginTop:'-10px',marginLeft:'-10px'});
		}
		css(marker.obj, {display:'none', visibility:'visible'});
	}

	each(handlers.doc, function(type,handler){ on(doc, type, handler) });
	each(handlers.marker, function(type,handler){ on(marker.obj, type, handler) });
})();

var tagger = {
	cleanListeners : function(){
		each(handlers.doc, function(type,handler){ off(doc, type, handler) });
		each(handlers.marker, function(type,handler){ off(marker.obj, type, handler) });
		clearTimeout(hashTimer);
	}
};
if(!global.thefancy_bookmarklet) global.thefancy_bookmarklet = {};
global.thefancy_bookmarklet.tagger = tagger;

// add event listsener to the specific element
function on(el,type,handler){ el.attachEvent?el.attachEvent('on'+type,handler):el.addEventListener(type,handler,false) };
// remove an event listener
function off(el,type,handler){ el.detachEvent?el.detachEvent('on'+type,handler):el.removeEventListener(type,handler) };
// get element by id
function elem(id){ return doc.getElementById(id) };
// set css
function css(el,prop){ for(var p in prop)if(prop.hasOwnProperty(p))try{el.style[p.replace(/-([a-z])/g,function(m0,m1){return m1.toUpperCase()})]=prop[p];}catch(e){} };
// get offset
function offset(el){ var t=0,l=0; while(el && el.offsetParent){ t+=el.offsetTop;l+=el.offsetLeft;el=el.offsetParent }; return {top:t,left:l} };
// each
function each(obj,fn){ for(var x in obj){if(obj.hasOwnProperty(x))fn.call(obj[x],x,obj[x],obj)} };
// extend object like jquery's extend() function
function extend(){ var a=arguments,i=1,c=a.length,o=a[0],x;for(;i<c;i++){if(typeof(a[i])!='object')continue;for(x in a[i])if(a[i].hasOwnProperty(x))o[x]=a[i][x]};return o };
// unparam
function unparam(s){ var a={},i,c;s=s.split('&');for(i=0,c=s.length;i<c;i++)if(/^([^=]+?)(=(.*))?$/.test(s[i]))a[RegExp.$1]=decodeURIComponent(RegExp.$3||'');return a };
// send message to iframe window
function send(data){ iframe.obj.setAttribute('src', iframe.url+'#tagger:'+data);try{(iframe.obj.contentWindow||iframe.obj).postMessage(data,loc.protocol+'//'+host)}catch(e){} };
// image data
function imageData(i){
	var imgs = latest.images;
	data = [
		'total='+imgs.length,
		'idx='+i,
		'loc='+encodeURIComponent(loc.protocol+'//'+loc.host+loc.pathname+loc.search)
	];
	if(imgs[i]){
		data.push('src='+encodeURIComponent(imgs[i].getAttribute('data-fancy-src')));
		data.push('title='+encodeURIComponent(imgs[i].getAttribute('alt') || imgs[i].getAttribute('title') || doc.title));
	}
	return data.join('&');
}

})(window,document,location);
