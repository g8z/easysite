// Title: COOLjsMenuPRO
// URL: http://javascript.cooldev.com/scripts/coolmenupro/
// Version: 2.1.4
// Last Modify: 25 Mar 2004
// Author: Sergey Nosenko <darknos@cooldev.com>
// Notes: Registration needed to use this script on your web site.
// Copyright (c) 2001-2002 by CoolDev.Com
// Copyright (c) 2001-2002 by Sergey Nosenko
window.CMenus = [];
var BLANK_IMAGE="img/b.gif";

function _BrowserDetector() {
	//this.nver = parseInt(navigator.appVersion);
	this.ver = navigator.appVersion;
	this.agent = navigator.userAgent;
	this.dom = document.getElementById;
	this.opera = window.opera;
	this.ie55 = this.ver.indexOf("MSIE 5.5") > -1 && this.dom && !this.opera;
	this.ie5 = this.ver.indexOf("MSIE 5") > -1 && this.dom && !this.ie55 && !this.opera;
	this.ie6 = this.ver.indexOf("MSIE 6") > -1 && this.dom && !this.opera;
	this.ie4 = document.all && !this.dom && !this.opera;
	//this.ie = this.ie4 || this.ie5 || this.ie6;
	//this.mac = this.agent.indexOf("Mac") > -1;
	this.ns6 = this.dom && parseInt(this.ver) >= 5 ;
	//this.ie3 = this.ver.indexOf("MSIE") && this.nver < 4;
	this.hotjava = this.agent.toLowerCase().indexOf('hotjava') != -1;
	this.ns4 = document.layers && !this.dom && !this.hotjava;
	this.bw = this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera;
	//this.ver3 = this.hotjava || this.ie3;
	this.opera7 = this.agent.toLowerCase().indexOf('opera 7') > -1 || this.agent.toLowerCase().indexOf('opera/7') > -1;
	this.operaOld = this.opera && !this.opera7;
}

function nn(val){return val != null}
function und(val){return typeof(val)=='undefined'}

function COOLjsMenuPRO(_name, items){
	this.bi = new Image();
	this.bi.src = BLANK_IMAGE;
	if (this.bw.ns4)
		window.onresize = resizeHandler;
	window.CMenus[this.name = _name] = this;
	this._jsPath = 'window.CMenus.' + _name;
	this._isRelative = items[0].pos == 'relative';
	this._isPopup = items[0].popup;
	this.root = {
		par:null,
		cd:[],
		pos:this._isRelative?[0,0]:items[0].pos||[0,0],
		fmt:items[0],
		frameoff:items[0].pos?items[0].pos:[0,0],
		index:0
	};
	this.root.lvl = new _CMenuLevel(this, this.root);
	this.root.fmt.pos = this.root.pos;
	this.items = [];
	this._parseSublevel(items, this.root);
	this._timeouts = [];
	this._lastActiveItem = null;
	this._lastShownLevel = null;
}

$ = COOLjsMenuPRO.prototype;

$.bw = new _BrowserDetector();

$._parseSublevel = function (_items, _parentItem) {
	var _lastItem;
	for (var i = 1; i < _items.length; i++)
		if (!und(_items[i]))
			_lastItem = new _CMenuItem(this, _parentItem, _items[i], _items[i].format || _items[0], _lastItem);
}

$.get_div=function (_name) {
	return this.bw.ns4 ? document.layers[_name] : document.getElementById ? document.getElementById(_name) : document.all[_name];
}

if ($.bw.ns4)
	$._setLayerVisibility = function (_layerObject, _visible) {
		if (_layerObject) _layerObject.visibility = _visible ? 'show' : 'hide';
	}
else
	$._setLayerVisibility = function (_layerObject, _visible) {
		if (_layerObject) _layerObject.style.visibility = _visible ? 'visible' : 'hidden';
	}

$.drawTop = function () {
	var s = '';
	for (var i in this.root.cd)
		s += this.root.cd[i]._generateHtmlCode();
	if (this._isRelative){
		var w = 0, h = 0;
		for (var i in this.root.cd)
			with (this.root.cd[i]) {
				h = Math.max(h, pos[1] + size[0]);
				w = Math.max(w, pos[0] + size[1]);
			}
		s = '<div id="cm' + this.name + '_" style="position:relative;left:0px;top:0px;width:' + w + 'px;height:' + h + 'px;">' + s +'</div>';
	}
	return s;
}

$.drawOther = function () {
	var s = '';
	for (var i in this.items) {
		if (!this.items[i]._isTopLevel)
			s += this.items[i]._generateHtmlCode();
		if (this.items[i].lvl)
			s += this.items[i].lvl._additionalHtmlCode;
	}
	s += this.root.lvl._additionalHtmlCode;
	return s;
}

$.initTop = function () {document.write('<div id="' + this.name + 'dummy" style="left: 0; top: 0;"></div>'+this.drawTop()+this.drawOther())}

$.init = function() {}

$.hide = function () {
	this.showLevel(this.root.lvl);
	if (this._isPopup || this.root.fmt.hidden_top)
		this.root.lvl.vis(0);
}

$.mpopup = function (ev, offX, offY) {
	var x=ev.pageX?ev.pageX:(this.bw.opera?ev.clientX:this.bw.ie4?ev.clientX+document.body.scrollLeft:ev.x+document.body.scrollLeft);
	var y=ev.pageY?ev.pageY:(this.bw.opera?ev.clientY:this.bw.ie4?ev.clientY+document.body.scrollTop:ev.y+document.body.scrollTop);
	var po=this.root.fmt.popupoff;
	y += offY?offY:po?po[0]:0;
	x += offX?offX:po?po[1]:0;
	this.popup(x, y);
}

$.popup = function (_x, _y) {
	this.root.loff = [0, 0];
	this.root.ioff = [0, 0];
	this._updatePositions(_x, _y, true);
	this.showLevel(this.root.lvl);
	this.showLevel(null, 2400);
}

$.show=function(){
	this.move();
	this.showLevel(this.root.lvl);
}

$.move = function () {
	if (this._isRelative)
		this._updatePositions(this._absCoord('X'), this._absCoord('Y'));
}

$._updatePositions = function (_x, _y, _includingTopLevelItems) {
	if (this.root.pos[0] != _x || this.root.pos[1] != _y) {
		this.root.pos = [_x, _y];
		for (var i in this.items){
			this.items[i]._setPosFromParent();
			if (_includingTopLevelItems || !this.items[i]._isTopLevel)
				this.items[i].move();
		}
	}
}

$._absCoord = function (_coord) {
	this._relDiv = this._relDiv || this.get_div('cm'+this.name+'_');

	if (this.bw.ns4)
		return this._relDiv['page' + _coord];

	var _result = 0, _element = this._relDiv;

	while (_element && _element != document.body) {
		_result += _element['offset' + (_coord == 'X' ? 'Left' : 'Top')];
		_element = _element.offsetParent;
	}

	return _result;
}

$._setItemStateRecursively = function (_item, _state) {
	while (_item != this.root && _item) {
		if (_item.par.lvl && _item.par.lvl.v)
			_item.setVis(_state);
		_item = _item.par;
	}
}

$._enqeue = function (_method, _parameter, _delay) {
	this._timeouts[this._timeouts.length] = window.setTimeout(this._jsPath + '.' + _method + '(' + (_parameter ? _parameter._jsPath : 'null') + ')', _delay);
}

$.cancelQueued = function () {
	for (var i in this._timeouts)
		window.clearTimeout(this._timeouts[i]);
	this._timeouts = [];
}

$.setActiveItem = function (_item, _delay) {
	if (_delay) {
		this._enqeue('setActiveItem', _item, _delay);
		return;
	}

	this._setItemStateRecursively(this._lastActiveItem, 'n');
	this._lastActiveItem = _item;
	this._setItemStateRecursively(this._lastActiveItem, 'o');
}

$.showLevel = function (_level, _delay) {
	if (_delay) {
		this._enqeue('showLevel', _level, _delay);
		return;
	}

	var h = this._lastShownLevel ? this._lastShownLevel.getPath() : [];
	this._lastShownLevel = _level;
	var s = this._lastShownLevel ? this._lastShownLevel.getPath() : [];
	var i = 0, j;
	while (i < s.length && i < h.length && s[i] == h[i])
		i++;
	for (j = i; j < h.length; j++)
		h[j].vis(0);
	if (!s.length && !this._isPopup && !this.root.fmt.hidden_top) {
		s[0] = this.root.lvl;
		this.setActiveItem(null);
	}
	for (j = i; j < s.length; j++)
		s[j].vis(1);
}

$.onmouseover = function (_item) {
	this.cancelQueued();
	this.setActiveItem(_item);
	if (_item.lvl)
		this.showLevel(_item.lvl, 100);
	return true;
}

$.onclick = function (_item) {
	this.cancelQueued();
	this.showLevel(_item.lvl && _item.lvl.v ? _item.lvl.par.par.lvl : _item.lvl);
	return true;
}

$.onmouseout = function (_item) {
	this.cancelQueued();
	this.showLevel(null, this.root.fmt.delay || 600);
	return true;
}

function _CMenuLevel(_menu, _parentItem){
	this.menu = _menu;
	this.par = _parentItem;
	this.v = 0;
	this._isTopLevel = _parentItem == _menu.root;
	this._isPersistent = !_menu._isPopup && this._isTopLevel;
	this._additionalHtmlCode = (_menu.bw.ie55 || _menu.bw.ie6) && !this._isPersistent ? "<iframe frameborder=0 id=ifr"+this.menu.name+"_"+this.par.index+" src=\""+(location.protocol=="https:" ? this.menu.root.fmt.https_fix_blank_doc : '')+"\" scroll=none style=\"FILTER:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);visibility:hidden;height:1;position:absolute;width:1px;left:0px;top:0px;z-index:1\"></iframe>" : '';
	this._jsPath = this.par._jsPath + '.lvl';
}

$ = _CMenuLevel.prototype;

$.getPath = function () {
	return (this.par.par ? this.par.par.lvl.getPath() : []).concat([this]);
}

$.menu_rect = function () {
	var r = [ 65535, 65535, 0, 0 ];

	for (i in this.par.cd) {
		var itm = this.par.cd[i];
		if (itm._isTopLevel || itm.v) {
			var s = itm._shadowSize;
			var slt = s < 0 ? -s : 0;
			var srt = s > 0 ? s : 0;
			r[0] = Math.min(r[0], itm.pos[0] - slt);
			r[1] = Math.min(r[1], itm.pos[1] - slt);
			r[2] = Math.max(r[2], itm.size[1] + itm.pos[0] + srt);
			r[3] = Math.max(r[3], itm.size[0] + itm.pos[1] + srt);
		}
	}

	return r;
}

$._getLayerById = function (_layerId) {
	return this.menu.bw.ns4 ? document.layers[_layerId] : document.getElementById ? document.getElementById(_layerId) : document.all[_layerId];
}

$.vis = function (s) {
	if (s && !this._isTopLevel)
		this.menu.move();
	var ss = this.v;
	this.v = s;
	if (this.menu.onlevelshow)
		this.menu.onlevelshow(this);
	for (var i in this.par.cd)
		this.par.cd[i].vis(s);
	if (s != ss && this.menu.onlevelshow)
		this.menu.onlevelshow(this);
	if ((this.menu.bw.ie55 || this.menu.bw.ie6) && !this._isPersistent) {
		this._iframe = this._iframe || this.menu.get_div("ifr" + this.menu.name + "_" + this.par.index);
		if (s) {
			var r = this.menu_rect();
			with (this._iframe.style) {
				left = r[0];
				top = r[1];
				width = Math.abs(r[0] - r[2]);
				height = Math.abs(r[1] - r[3]);
			}
		}
		this.menu._setLayerVisibility(this._iframe, s);
	} else {
		var _list = this.menu.root.fmt.forms_to_hide;
		if (!this._isPersistent && _list)
			for (var i in _list)
				this.menu._setLayerVisibility(this._getLayerById(_list[i]), !this.menu._lastShownLevel || this.menu._lastShownLevel == this.menu.root.lvl);
	}
}

function _CMenuItem(_menu, _parentItem, _itemData, _format, _previousItem){
	this.par = _parentItem;
	this.code = _itemData.code;
	this.ocode = _itemData.ocode || _itemData.code;
	this.targ = und(_itemData.target) ? '' : 'target="' + _itemData.target + '" ';
	this.url = _itemData.url || 'javascript:void(0)';
	this.fmt = _format;
	this.menu = _menu;
	this.index = _menu.items.length;
	_menu.items[this.index] = this;
	this._jsPath = _menu._jsPath + '.items[' + this.index + ']';
	this._isTopLevel = _menu.root == _parentItem;
	this.cd = [];
	this.divs = [];
	_parentItem.cd[_parentItem.cd.length] = this;
	this.id = "cmi" + this.menu.name + "_" + this.index;
	this.v = 0;
	this.state = 'n';
	this.ioff=this.getf(this, "itemoff");
	this.loff=this.getf(this, "leveloff");
	this.imgsize=this.getf(this, "imgsize");
	this.arrsize=this.getf(this, "arrsize");
	this.image=this.getf(this, "image");
	this.oimage=this.getf(this, "oimage") || this.image;
	this.arrow=this.getf(this, "arrow");
	this.oarrow=this.getf(this, "oarrow") || this.arrow;
	this.style=this.getf(this, "style");
	this.size=this.getf(this, "size");
	if (this._isTopLevel) this.fmt.pos=this.getf(this, "pos");
	this.prev = _previousItem;
	this.z = this._isTopLevel ? 10: this.par.z + 1;

	var b = this.style.border;
	b = (b && this.style.borders) || [ b, b, b, b ];
	this._borderLeft = b[0];
	this._borderTop = b[1];
	this._borderWidth = b[0] + b[2];
	this._borderHeight = b[1] + b[3];

	this._shadowSize = this.style.shadow;

	this._setPosFromParent();
	if (_itemData.sub && _itemData.sub.length) {
		this.sub = _itemData.sub;
		this.lvl = new _CMenuLevel(_menu, this);
		this.menu._parseSublevel(_itemData.sub, this);
	}
}

$ = _CMenuItem.prototype;

$.bw = new _BrowserDetector();

$.div = function (n) { return this.divs[n] || (this.divs[n] = this.get_div(this.id + n)) }

$._genericImageTd = function (_backgroundColor, _dimensions, _source) {
	return '<td' + (_backgroundColor ? ' bgcolor="' + _backgroundColor + '"' : '') +' width="'+_dimensions[1]+'"><img src="'+_source+'" width="'+_dimensions[1]+'" height="'+_dimensions[0]+'" /></td>';
}

$._contentLayerHtml = function (_state) {
	return '<table cellpadding=0 cellspacing=0 width=' + (this.size[1] - this._borderWidth) + '" height="' + (this.size[0] - this._borderHeight) + '" border=0><tr>'
		+ (nn(this.image) ? this._genericImageTd(this.style.color[_state == 'n' ? 'imagebg' : 'oimagebg'], this.imgsize, _state == 'n' ? this.image : this.oimage) : '')
		+ '<td width="100%"><div class="' + this.style.css[_state == 'n' ? 'ON' : 'OVER'] + '">' + (_state == 'n' ? this.code : this.ocode) + '</div></td>'
		+ (nn(this.arrow) && this.cd.length ? this._genericImageTd('', this.arrsize, _state == 'n' ? this.arrow : this.oarrow) : '')
		+ '</tr></table>';
}

$._generateHtmlCode = function(){
	var s = this._shadowSize;
	return (s ? this._adiv('s', s, s, 0, 0, 'shadow', '') : '')
		+ (this.style.border ? this._adiv('b', 0, 0, 0, 0, 'border', '') : '')
		+ this._innerAdiv('n', 'bgON', this._contentLayerHtml('n'))
		+ this._innerAdiv('o', 'bgOVER', this._contentLayerHtml('o'))
		+ this._innerAdiv('e', '', '<a href="' + this.url + '" ' + this.targ + this._handler('onclick') + this._handler('onmouseover') + this._handler('onmouseout') + '><img src="' + this.menu.bi.src + '" width="' + this.size[1] + '" height="' + this.size[0] + '" border="0"></a>');
}

$._handler = function (_event) {
	return ' ' + _event + '="return ' + this.menu._jsPath + '.' + _event + '(' + this._jsPath + ')"';
}

$._innerAdiv = function (_suffix, _backgroundColorField, _htmlCode) {
	return this._adiv(_suffix, this._borderLeft, this._borderTop, this._borderWidth, this._borderHeight, _backgroundColorField, _htmlCode);
}

$._adiv = function (_suffix, _dX, _dY, _dWidth, _dHeight, _backgroundColorField, _htmlCode) {
	var _width = this.size[1] - _dWidth, _height = this.size[0] - _dHeight;
	return '<div id="'+this.id+_suffix+'" style="position:absolute;clip:rect(0px '+_width+'px '+_height+'px 0px);z-index:'+this.z+';left:'+(this.pos[0] + _dX)+'px;top:'+(this.pos[1] + _dY)+'px;width:'+_width+'px;height:'+_height+'px;visibility:hidden'+(_backgroundColorField?';'+(this.menu.bw.ns4 ? 'layer-' : '')+'background-color:'+this.style.color[_backgroundColorField]:'')+'">' + _htmlCode +'</div>';
}

$.vis = function (s) {
	if (!s)
		this.state = "n";
	this._setLayerVisibility("s", s);
	this._setLayerVisibility("b", s);
	this._setLayerVisibility("o", 0);
	this._setLayerVisibility("n", s);
	this._setLayerVisibility("e", s);
	this.v = s;
}

$.setVis = function (n) {
	if (this.state != n) {
		this._setLayerVisibility("n", n == "n");
		this._setLayerVisibility("o", n == "o");
		this.state = n;
	}
}

$._setLayerVisibility = function (_layerName, _visible) {
	this.menu._setLayerVisibility(this.div(_layerName), _visible);
}

$.getf = function (_obj, _name) {
	if (!und(_obj) && nn(_obj) && !und(_obj.fmt)) {
		if (!und(_obj.fmt[_name]))
			return _obj.fmt[_name];
		if (!_obj._isTopLevel && _obj.par && _obj.par.sub && _obj.par.sub[0][_name])
			return _obj.par.sub[0][_name];
		return this.getf(_obj.par, _name);
	}
}

$._setPosFromParent = function () {
	if (this.prev)
		this.pos = [ this.prev.pos[0] + this.ioff[1], this.prev.pos[1] + this.ioff[0] ];
	else if (!this._isTopLevel)
		this.pos = [ this.par.pos[0] + this.loff[1], this.par.pos[1] + this.loff[0] ];
	else
		this.pos = this.menu.root.pos;
}

$.get_div = function (_name) {
	if (this.bw.ns4)
		return (this.menu._isRelative && this._isTopLevel) ? document.layers["cm" + this.menu.name + "_"].layers[_name] : document.layers[_name];
	else
		return document.getElementById ? document.getElementById(_name) : document.all[_name];
}

$.move = function () {
	var x = this.pos[0], y = this.pos[1];
	if (this._shadowSize)
		this._moveLayerTo(x + this._shadowSize, y + this._shadowSize, 's');
	if (this.style.border) {
		this._moveLayerTo(x, y, 'b');
		x += this._borderLeft;
		y += this._borderTop;
	}
	this._moveLayerTo(x, y, 'o');
	this._moveLayerTo(x, y, 'n');
	this._moveLayerTo(x, y, 'e');
}

if ($.bw.ns4)
	$._moveLayerTo = function (_x, _y, _b) { this.div(_b).moveTo(_x, _y) }
else if ($.bw.operaOld)
	$._moveLayerTo = function (_x, _y, _b) { with (this.div(_b).style) { left = _x; top = _y; } }
else
	$._moveLayerTo = function (_x, _y, _b) { with (this.div(_b).style) { left = _x + 'px'; top = _y + 'px'; } }

window.oldCMOnLoad=window.onload;
function CMOnLoad(){
	var bw = new _BrowserDetector();
	if (bw.operaOld)window.operaResizeTimer=setTimeout('resizeHandler()',1000);
	if (typeof(window.oldCMOnLoad)=='function') window.oldCMOnLoad();
	if (bw.ns4) window.onresize=resizeHandler;
}
window.onload=CMOnLoad;
function resizeHandler() {
	if (window.reloading) return;
	if (!window.origWidth){
		window.origWidth=window.innerWidth;
		window.origHeight=window.innerHeight;
	}
	var reload=window.innerWidth != window.origWidth || window.innerHeight != window.origHeight;
	window.origWidth=window.innerWidth;window.origHeight=window.innerHeight;
	if (window.operaResizeTimer)clearTimeout(window.operaResizeTimer);
	if (reload) {window.reloading=1;document.location.reload();return}
	if (new _BrowserDetector().operaOld){window.operaResizeTimer=setTimeout('resizeHandler()',500)}
}
function CMenuPopUp(menu, evn, offX, offY){window.CMenus[menu].mpopup(evn, offX, offY)}
function CMenuPopUpXY(menu,x,y){window.CMenus[menu].popup(x,y)}
