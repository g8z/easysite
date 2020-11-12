// Title: COOLjsTree Professional
// URL: http://javascript.cooldev.com/scripts/cooltreepro/
// Version: 2.6.0
// Last Modify: 08 Feb 2005
// Author: Alex Kunin <alx@cooldev.com>
// Notes: Registration needed to use this script on your web site.
// Copyright (c) 2001-2005 by CoolDev.Com
// Copyright (c) 2001-2005 by Sergey Nosenko
// Copyright (c) 2001-2005 by Alex Kunin

// Options: PROFESSIONAL



function COOLjsTreePRO(_name, _nodes, _format) {
        this.name = this._name = _name;
        this.bw = new _BrowserDetector();

        var _fmt = {};

        _fmt.left = _format[0];
        _fmt.top = _format[1];
        _fmt._show = { nb:_format[2], nf:_format[5] };
        _fmt.clB = _format[3][0];
        _fmt.exB = _format[3][1];
        _fmt.iE = _format[3][2];
        _fmt._buttonWidth = _format[4][0];
        _fmt._buttonHeight = _format[4][1];
        _fmt.Ew = _format[4][2];
        _fmt.clF = _format[6][0];
        _fmt.exF = _format[6][1];
        _fmt.iF = _format[6][2];
        _fmt._iconWidth = _format[7][0];
        _fmt._iconHeight = _format[7][1];
        _fmt.ident = _format[8];
        _fmt._cssClass = _format[10];
        _fmt._cssClasses = _format[11];
        _fmt.so = _format[12];
        _fmt.pg = _format[13][0];
        _fmt.sp = _format[13][1];
        _fmt.exp = _format[14];
        _fmt.expimg = _format[15];
        _fmt.expimgsize = _format[16];
        _fmt.cook = _format[17];
        _fmt.rel = _format[18];
        _fmt.rels = _format[19];
        _fmt.resize = _format[20];
        _fmt.sel = _format[21];
        _fmt.selC = _format[22];
        _fmt._cssClassForSelectedNode = _format[22] ? _format[22][2] : '';
        _fmt._cssClassForOpenedNode = _format[22] ? _format[22][3] : '';
        _fmt._wrappingMargin = _format[23] || 0;
        _fmt._imageAlignment = _format[24] || 'middle';
        _fmt._imageAlignment = _format[24] || 'middle';

        if (_fmt._show.nb)
                _preloadImages(_format[3]);

        if (_fmt._show.nf)
                _preloadImages(_format[6]);

        this._format = _fmt;

        if (!this.bw._ver3)
                this._back = new _COOLjsTreeBackPRO(_format[9], this);
        if (_und(window.CTrees))
                window.CTrees = [];
        window.CTrees[_name] = this;
        this.jsPath = "window.CTrees['" + _name + "']";
        this.Nodes = this._nodes = [];
        this._lastLocalIndex = -1;
        this._layerIndex = 0;

        this._layersToDetach = {};

        this._backbone = this._prepareNode(([ {id:null}, '', null, null, {format:{}} ]).concat(_nodes));

        this._px = this.bw._operaOld ? '' : 'px';
        this._dynamic = this.bw.dom && !this.bw._operaOld;
        this._rtl = document.body && document.body.dir == 'rtl';

        this._redrawAfter = -1;
}

$ = COOLjsTreePRO.prototype;

$.__handlers = {};

$._handler = function (_event, _prefix, _argument) {
        return _attribute('on' + _event, 'return ' + this.jsPath + '.' + _prefix + '_on' + _event + '(' + _argument + ')');
}

$._handlers = function (_prefix, _argument) {
        if (_und(this.__handlers[_prefix])) {
                this.__handlers[_prefix] = {};

                for (var _handler in this)
                        if (_handler.match(new RegExp('^' + _prefix + '_on(.+)$')))
                                this.__handlers[_prefix][RegExp.$1] = true;
        }

        var _result = '';

        for (var _handler in this.__handlers[_prefix])
                _result += this._handler(_handler, _prefix, _argument);

        return _result;
}

$.getAdditionalColumns = function (_node) {
        return '';
}

$.getRoot = $._getRoot = function () {
        if (!this._root) {
                this._backbone._object = this._root = new _COOLjsTreeNodePRO(this._backbone, this, null);
                this._root._setExpanded(true);
        }

        return this._root;
}

$._findNode = function (_index) {
        var _path = this._nodePathBy('n', _index, this._backbone._children);

        if (!_path)
                return null;

        var _parent = this._backbone, i = 0;

        for (var i = 0; i < _path.length - 1; i++)
                _parent = _parent._children[_path[i]];

        return [ _parent, _path[i] ];
}

$._stripObjects = function (_node) {
        if (this._root && _node._index == this._root._index)
                this._root = null;

        if (_node._object) {
                _node._object._detachLayers();
                _node._parentNode = null;
                _node._object = null;
        }

        for (var i in _node._children)
                if (_node._children[i]._object)
                        this._stripObjects(_node._children[i]);
}

$._insertNodes = function (_parentIndex, _minorIndex, _definition) {
        var _parent;

        if (_parentIndex == this._backbone._index)
                _parent = this._backbone;
        else {
                var _pair = this._findNode(_parentIndex);
                if (_pair)
                        _parent = _pair[0]._children[_pair[1]];
        }

        if (_parent) {
                this._stripObjects(_parent);

                for (var i in _definition)
                        _definition[i] = this._prepareNode(_definition[i]);

                _minorIndex = Math.max(0, Math.min(_minorIndex, _parent._children.length));

                if (_minorIndex == _parent._children.length) {
                        _parent._children = _parent._children.concat(_definition);
                } else {
                        var _children = _parent._children;
                        _parent._children = [];

                        for (var i in _children) {
                                if (i == _minorIndex)
                                        _parent._children = _parent._children.concat(_definition);
                                _parent._children[_parent._children.length] = _children[i];
                        }
                }

                return _minorIndex;
        } else
                return null;
}

$._replaceDefinition = function (_index, _definition, _reuseId, _reuseFormat, _reuseChildren) {
        var _pair = this._findNode(_index);

        if (_pair) {
                var _parent = _pair[0], _children = _parent._children, _index = _pair[1];
                this._stripObjects(_parent);
                var _definition = this._prepareNode(_definition);
                if (_reuseId)
                        _definition[0] = _parent._children[_index][0];
                if (_reuseFormat)
                        _definition[4] = _parent._children[_index][4];
                if (_reuseChildren)
                        _definition._children = _parent._children[_index]._children;
                _parent._children[_index] = _definition;
        }
}

$._deleteNode = function (_parent, _index) {
        if (!_und(_parent._children[_index])) {
                var _children = _parent._children;
                this._stripObjects(_parent);
                _parent._children = [];
                for (var i in _children)
                        if (i != _index)
                                _parent._children[_parent._children.length] = _children[i];
        }
}

$.dump = function (_node, _prefix) {
        var _result = '';

        if (!_node) {
                _node = this._backbone;
                _prefix = '';
        }

        _result = _prefix + "{" + _node._index + "} - [";

        if (_node[0].id !== null)
                _result += '{id:' + _node[0].id + '}, ';

        _result += '"' + _node[1] + '", ';
        _result += (_node[2] === null ? 'null' : '"' + _node[2] + '"') + ', ';
        _result += _node[3] === null ? 'null' : '"' + _node[3] + '"';

        if (_node._children.length) {
                _result += ",\n";
                for (var i in _node._children)
                        _result += this.dump(_node._children[i], _prefix + "\t");
                _result += _prefix + "],\n";
        } else
                _result += "],\n";

        return _result;
}

$.getSelectedNode = function () {
        return this.nodeByIndex(this._selectedNodeIndex);
}

$._isNodeSelected = function (_node) {
        return this._selectedNodeIndex === _node._index;
}

$._needAdvancedWrapping = function () {
        return this._dynamic && this._format._wrappingMargin && this._format.exp;
}

$._walk_ns4_layers = function (_collection) {
        for (var i in _collection) {
                this._ns4_layers[_collection[i].id] = _collection[i];
                if (_collection[i].layers)
                        this._walk_ns4_layers(_collection[i].layers);
        }
}

$._getElement = function (_id) {
        if (this.bw.ns4) {
                if (!this._ns4_layers) {
                        this._ns4_layers = {};
                        this._walk_ns4_layers(document.layers);
                }
                return this._ns4_layers[_id];
        } else
                return (document.all && document.all[_id]) || document.getElementById(_id);
}

$.moveTo = function (x, y) {
        this._back.top = y;
        this._back.left = y;
        this._back.moveTo(x, y);
        this._format.top = y;
        this._format.left = x;
        this.draw();
}

$.ensureVisible = function (_index, _noredraw) {
        var _node = this.nodeByIndex(_index);
        var _redraw = false;
        while (_node) {
                _node = _node._parentNode;

                if (_node._isRoot())
                        break;

                if (!_node._isExpanded()) {
                        this.expandNode(_node._index, 1);
                        _redraw = true;
                }
        }

        if (_redraw && !_noredraw)
                this.draw();
}

$._nodePathBy = function (_field, _value, _nodes) {
        for (var i in _nodes) {
                if (typeof(_value) != 'object' ? _nodes[i][_field] == _value : ('' + _nodes[i][_field]).match(_value))
                        return [i];

                var _subPath = this._nodePathBy(_field, _value, _nodes[i]._children);
                if (_subPath)
                        return [i].concat(_subPath);
        }

        return null;
}

$._nodeBy = function (_field, _value) {
        return this._getRoot()._getNodeByPath(this._nodePathBy(_field, _value, this._backbone._children));
}

$.nbn = $.nodeByName = function (_value) { return this._nodeBy('c', _value); }
$.nodeByID = function (_value) { return this._nodeBy('i', _value); }
$.nodeByURL = function (_value) { return this._nodeBy('u', _value); }

$.nodeByIndex = function (_value) {
        if (!this._nodes[_value])
                this._nodes[_value] = this._nodeBy('n', _value);
        return this._nodes[_value];
}

$.nodeByXY = function (_X, _Y) {
        for (var i in this._nodes)
                if (this._nodes[i])
                        with (this._nodes[i])
                                if (visible && _x <= _X && _y <= _Y && _x + w > _X && _y + h > _Y)
                                        return this._nodes[i];
        return null;
}

$._redraw = function (_y) {
        if (!this._redrawTO)
                this._redrawTO = window.setTimeout(this.jsPath + '.draw()', 1);
        if (typeof(_y) == 'number')
                this._redrawAfter = Math.min(_y, this._redrawAfter);
        else
                this._redrawAfter = -1;
}

$._detachLayers = function (_node) {
        if (this._canDetachImmediately)
                _node._actuallyDetachLayers();
        else {
                this._layersToDetach[_node._index] = true;
                this._redraw();
        }
}

$._actuallyDetachLayers = function () {
        if (this._dynamic)
                for (var _index in this._layersToDetach) {
                        var _node = this.nodeByIndex(_index);
                        if (_node)
                                _node._actuallyDetachLayers();
                }

        this._layersToDetach = {};
}

$.draw = function () {
        if (this.bw._ver3 || !this._redrawComplete)
                return;

        this._actuallyDetachLayers();
        this._canDetachImmediately = true;

        this._maxHeight = 0;
        this._maxWidth = 0;

        with (this._getRoot()) {
                draw(true);
                if (this._rtl)
                        draw(true);
        }

        if (this._format.rel && this._format.resize || !this._format.rel)
                this._back._resize(this._maxWidth-this._back.left, this._maxHeight);
        this._redrawTO = null;
        this._redrawAfter = 10000000;

        if (this.ondraw)
                this.ondraw(this);

        this._canDetachImmediately = false;
}

$.expandNode = function (_index, _noRedraw, _selectNode) {
        if (!this.bw._ver3) {
                var _node = this.nodeByIndex(_index);
                if (_selectNode)
                        this.selectNode(_index);
                if (_node && _node._hasChildren()) {
                        var _newState = !_node._isExpanded();
                        if (this._format.so) {
                                this.collapseAll();
                                this.ensureVisible(_node.index, true);
                        }
                        _node._setExpanded(_newState);
                        this._redraw(_node._y);
                }
        }
}

$._selectNode = $.selectNode = function (_index) {
        this._selectedNodeIndex = _index;
        this._redraw();
}

$._readNode = function (_raw, _parent, _hasNext) {
        var _node = this._nodes[_raw._index] = new _COOLjsTreeNodePRO(_raw, this, _parent);

        _node._hasNext = _hasNext;

        _node._initImages();

        return _node;
}

$.__setStateGlobally = function (_state, _node) {
        for (var i in _node._children) {
                this.__setStateGlobally(_state, _node._children[i]);
                if (_node._children[i]._children.length)
                        if (_node._children[i]._object)
                                _node._children[i]._object._setExpanded(_state);
                        else
                                _node._children[i][4].format.expanded = _state;
        }
}

$._setStateGlobally = function (_state) {
        this.__setStateGlobally(_state, this._backbone);
        this._redraw();
}

$.collapseAll = function () {
        this._setStateGlobally(false);
}

$.expandAll = function () {
        this._setStateGlobally(true);
}

$._prepareNode = function (_node) {
        if (_und(_node[_node.length - 1]))
                _node = _node.slice(0, _node.length - 1);

        if (_und(_node[0].id))
                _node = ([{id:null}]).concat(_node);

        if (_und(_node[4]) || _und(_node[4].format))
                _node = _node.slice(0, 4).concat([{format:{}}]).concat(_node.slice(4));

        var _index = this._lastLocalIndex++;
        var _children = _node.slice(5);
        _node = _node.slice(0, 5);
        _node._children = [];

        for (var i in _children)
                _node._children[i] = this._prepareNode(_children[i]);

        _node.i = _node[0].id;
        _node.c = _node[1];
        _node.u = _node[2];
        _node.t = _node[3];
        _node.f = _node[4].format;
        _node.n = _node._index = _index;

        _node._object = null;

        return _node;
}

$.init = function () {
        var s = this._getRoot()._getHtml(!this._dynamic);

        if (this._format.cook) {
                this._selectNode(this._getCookie('Selected'));
                this._setState(this._getCookie('State'));
        }

        if (!this.bw._ver3)
                s = this._back._init(s);

        if (this.bw.ns4)
                s = '<div id="' + this._name + 'dummytreediv" style="position:absolute;"></div>' + s;

        document.write(s);
}

$._getCookie = function(_name){
        return document.cookie.match(new RegExp('(\\W|^)' + this._name + _name + '=([^;]+)')) ? RegExp.$2 : null;
}

$._setCookie = function (_name, _value) {
        document.cookie = this._name + _name + '=' + _value + '; path=/';
}

$.__getState = function (_node) {
        var _result = '';

        for (var i in _node._children)
                if (_node._children[i]._children.length)
                        _result += (_node._children[i][4].format.expanded ? 1 : 0) + this.__getState(_node._children[i]);

        return _result;
}

$._getState = function () {
        return this.__getState(this._backbone);
}

$.__setState = function (_node, _state, _index) {
        for (var i in _node._children) {
                if (_node._children[i]._children.length) {
                        if (_node._children[i]._object)
                                _node._children[i]._object._setExpanded(_state.charAt(_index) == '1');
                        else
                                _node._children[i][4].format.expanded = _state.charAt(_index) == '1';
                        _index = this.__setState(_node._children[i], _state, _index + 1);
                }
        }

        return _index;
}

$._setState = function (_state) {
        this.__setState(this._backbone, _state || '', 0);
}

$.image_onclick = $.button_onclick = $.caption_onclick = function (_node) {
        this.expandNode(_node.index, 1, 1);
        return true;
}

$.button_onclick = function (_node) {
        this.expandNode(_node.index);
        return true;
}

$.image_onmouseover = $.button_onmouseover = $.caption_onmouseover = function (_node) {
        window.status = _node.text;
        return true;
}

$.image_onmouseout = $.button_onmouseout = $.caption_onmouseout = function (node) {
        window.status = window.defaultStatus;
        return true;
}

function _COOLjsTreeNodePRO(_definition, _tree, _parent) {
        var _index = _definition._index;
        this._definition = _definition;
        this._index = this.index = _index;
        this.jsPath = _tree.jsPath + '.nodeByIndex(' + _index + ')';
        this.treeView = this._tree = _tree;
        this._parentNode = this.parentNode = _parent;
        this.text = _definition[1];
        this.url = _definition[2];
        this.target = _definition[3];

        this.nodeID = _definition[0].id;
        this._format = _definition[4].format;

        this._previousExpanded = null;
        this._setExpanded(this._definition[4].format.expanded);
        this.children = this._children = [];
        this._level = this.level = _parent ? _parent._level + 1 : -1;
        this.visible = false;
        this._layers = {};
        this._exceeds = false;
        this._imagesToUpdate = {};
}

$ = _COOLjsTreeNodePRO.prototype;

$._isRoot = function () {
        return this._tree._backbone._index == this._index;
}

$._isExpanded = function () {
        return this._definition[4].format.expanded;
}

$.id = function () {
        return this._id;
}

$._setProperties = function (_caption, _url, _target) {
        this._tree._replaceDefinition(this._index, [ _und(_caption) ? this._getCaption() : _caption, _und(_url) ? this._getUrl() : _url, _und(_target) ? this._getTarget() : _target ], true, true, true);
        this._tree._redraw();
}

$.getTree = function () { return this._tree; }

$.getParent = function () { return this._parentNode; }

                $.getId = function () { return this._definition[0].id; }
$._getCaption = $.getCaption = function () { return this._definition[1]; }
$._getUrl = $.getUrl = function () { return this._definition[2]; }
$._getTarget = $.getTarget = function () { return this._definition[3]; }
                $.getFormat = function () { return this._definition[4].format; }

$.setCaption = function (_value) { this._setProperties(_value, this._undefined, this._undefined); }
$.setUrl = function (_value) { this._setProperties(this._undefined, _value, this._undefined); }
$.setTarget = function (_value) { this._setProperties(this._undefined, this._undefined, _value); }

$.hasChildren = $._hasChildren = function () {
        return !!this._definition._children.length;
}

$._isItFolder = function () {
        return this._hasChildren() || this._definition[4].format.isFolder;
}

$._getNodeByPath = function (_path) {
        if (_path)
                return _path.length ? this._getChild(_path[0])._getNodeByPath(_path.slice(1)) : this;

        return null;
}

$._setExpanded = function (_value) {

        this._definition[4].format.expanded = !!_value;
        if (this._layersAttached) {
                this._updateImage('nb', this._getButtonImage());
                this._updateImage('nf', this._getIconImage());
        }
}

$._getButtonImage = function () {
        if (!this._tree._format._show.nb || this._format.nobuttons)
                return null;

        if (!this._hasChildren())
                return null;

        if (this._tree._format.exp) {
                var _images = this._format.eimages || this._tree._format.expimg;
                if (this._hasNext)
                        return _images[this._isExpanded() ? 3 : 5];
                else
                        return _images[this._isExpanded() ? 4 : 6];
        } else if (this._format.buttons)
                return this._isExpanded() ? this._format.buttons[1] : this._format.buttons[0];
        else
                return this._isExpanded() ? this._tree._format.exB : this._tree._format.clB;
}

$._getIconImage = function () {
        if (!this._tree._format._show.nf || this._format.nofolders)
                return null;

        if (this._tree._format.exp) {
                var _images = this._format.eimages || this._tree._format.expimg;
                return this._isItFolder() ? _images[this._isExpanded() ? 1 : 0] : _images[2];
        } else if (this._format.folders)
                return this._isItFolder() ? (this._isExpanded() ? this._format.folders[1] : this._tree._format.folders[0]) : this._tree._format.folders[2];
        else
                return this._isItFolder() ? (this._isExpanded() ? this._tree._format.exF : this._tree._format.clF) : this._tree._format.iF;
}

$._updateImage = function (_suffix, _src) {
        if (_src) {
                var _img = (this._getLayer().document || document).images[this._id + _suffix];
                if ((this._tree._format._show[_suffix] || this._tree._format.exp) && _img && _img.src != _src)
                        this._imagesToUpdate[_suffix] = { _image:_img, _path:_src };
        }
}

$._initImages = function () {
        if (this._tree._format.exp) {
                var esz = this._tree._format.expimgsize;
                this.wimg = this._iconWidth = this._buttonWidth = esz[0];
                this.himg = this._iconHeight = this._buttonHeight = esz[1];
        } else {
                this._buttonWidth = _und(this._format.bsize) ? this._tree._format._buttonWidth : this._format.bsize[0];
                this._buttonHeight = _und(this._format.bsize) ? this._tree._format._buttonHeight : this._format.bsize[1];
                this._iconWidth = _und(this._format.fsize) ? this._tree._format._iconWidth : this._format.fsize[0];
                this._iconHeight = _und(this._format.fsize) ? this._tree._format._iconHeight : this._format.fsize[1];
        }
}

$._getHtml = function (_recursive) {
        var _result = '';

        if (!this._isRoot()) {
                this._id = 'nt' + this._tree._name + '_' + this._tree._layerIndex++;
                _result += this._tree.bw._ver3 ? this._getContent() : '<div' + this._tree._handlers('layer', this.jsPath) + ' id="' + this._id + 'd" style="position:absolute;visibility:hidden;z-index:' + (this.index + 10) + ';">' + this._getContent() + '</div>';
        }

        if (_recursive)
                for (var i = 0; i < this._getNumberOfChildren(); i++)
                        _result += this._getChild(i)._getHtml(_recursive);

        return _result;
}

$._anchor = function (_url, _prefix, _content, _cssClass, _needId) {
        return '<a' + this._tree._handlers(_prefix, this.jsPath) + ' href="' + (_url || 'javascript:void(0)') + '"' + _attribute('target', _url && this.target) + _attribute('id', _needId && (this._id + 'an')) + _attribute('class', _cssClass) + '>' + _content + '</a>';
}

$._square = function (_prefix, _suffix, _imgSrc, _needAnchor, _needUrl, w, h, _background) {
        if (!w || !_imgSrc)
                return '';
        if (!this._tree._needAdvancedWrapping() && _background && _imgSrc == this._tree._format.iE)
                _imgSrc = _background;
        var i = '<img' + (_suffix ? ' name="' + this._id + _suffix + '" id="' + this._id + _suffix + '"' : '') + ' src="' + _imgSrc + '" width="' + w + '" height="' + h + '" border="0"' + (this._tree.bw.ns4 ? '' : ' style="display: block"') + ' />';
        return '<td' + (this._tree._needAdvancedWrapping() ? _attribute('background', _background) : '') + ' style="font-size: 1px;" valign="' + (this._tree._format.exp ? 'top' : this._tree._format._imageAlignment) + '" width="' + w + '">' + (_needAnchor ? this._anchor(_needUrl && this.url, _prefix, i) : i) + '</td>';
}

$._lineSquares = function () {
        return this._level >= 0 ? this._parentNode._lineSquares() + this._square('', '', this._tree._format.iE, false, false, this._tree._format.expimgsize[0], this._tree._format.expimgsize[1], this._hasNext && this._tree._format.expimg[7]) : '';
}

$._getIndent = function () {
        with (this._tree._format)
                return _und(ident[this._level]) ? ident[0] * this._level : ident[this._level];
}

$._getContent = function () {
        var w = this._tree._format._wrappingMargin;

        var s = '<table' + _attribute('width', w) + ' cellpadding="' + this._tree._format.pg + '" cellspacing="' + this._tree._format.sp + '" border="1" class="cls' + this._tree._name + '_back' + this._level + '"><tbody><tr>';

        if (this._tree._format.exp) {
                s += this._parentNode._lineSquares();
                if (!this._hasChildren())
                        s += this._square('', '', (this._hasNext ? this._tree._format.expimg[8] : this._tree._format.expimg[9]), false, false, this._tree._format.expimgsize[0], this._tree._format.expimgsize[1], this._hasNext && this._tree._format.exp && this._tree._format.expimg[7]);
        } else
                s += this._square('', '', this._tree._format.iE, false, false, this._getIndent() + (this._hasChildren() ? 0 : this._tree._format.Ew), 1);

        s += this._square('button', 'nb', this._getButtonImage(), true, false, this._buttonWidth, this._buttonHeight, this._hasNext && this._tree._format.exp && this._tree._format.expimg[7]);
        s += this._square('image', 'nf', this._getIconImage(), true, true, this._iconWidth, this._iconHeight, this._isExpanded() && this._hasChildren() && this._tree._format.exp && this._tree._format.expimg[7]);
        s += '<td' + (w ? '' : ' nowrap="nowrap"') + '><div id="' + this._id + 'a" style="position:relative;">' + this._anchor(this.url, 'caption', this.text, this._getCssClass(), true) + '</div></td>';

        return s + this._tree.getAdditionalColumns(this).replace(/\{node\}/g, this.jsPath) + '</tr></tbody></table>';
}

$._getCssClass = function () {
        var _result;

        if (this._tree._format.sel)
                if (this._isSelected())
                        _result = this._tree._format._cssClassForSelectedNode;
                else if (this._hasChildren() && this._isExpanded())
                        _result = this._tree._format._cssClassForOpenedNode;

        if (!_result)
                with (this._tree._format)
                        _result = _cssClasses[this._level] || _cssClass;

        if (typeof(_result) != 'string')
                _result = _result[this._level];

        return _result || '';
}

$._moveTo = function (_x, _y) {
        if (this._x != _x || this._y != _y) {
                with (this._getLayer())
                        if (this._tree.bw.ns4)
                                moveTo(_x, _y);
                        else {
                                style.left = _x + this._tree._px;
                                style.top = _y + this._tree._px;
                        }

                this._x = _x;
                this._y = _y;
        }
}

$._attachLayers = function () {
        if (!this._layersAttached) {
                if (this._tree._dynamic) {
                        var _el = this._el = document.createElement('div');

                        _el.style.position = this._tree._format.rel ? 'relative' : 'absolute';
                        _el.innerHTML = this._getHtml();
                        this._tree._back._getLayer().appendChild(_el);
                }

                this._layersAttached = true;
                this._layers = {};
        }
}

$._detachLayers = function () {
        this._tree._detachLayers(this);
}

$._actuallyDetachLayers = function () {
        if (this._layersAttached && this._getLayer()) {
                with (this._getLayer()) {
                        style.visibility = 'hidden';
                        innerHTML = '';
                }
                this._layersAttached = false;
                this._layers = {};
                this.w = this.h = 0;
                this._x = this._y = 0;
        }
}

$._updateVisibility = function () {
        with (this._getLayer())
                if (this._tree.bw.ns4)
                        visibility = this.visible ? 'show' : 'hide';
                else
                        style.visibility = this.visible ? 'visible' : 'hidden';

        if (this.visible) {
                for (var i in this._imagesToUpdate)
                        with (this._imagesToUpdate[i])
                                _image.src = _path;

                this._imagesToUpdate = {};
        }
}

$._updatePosition = function () {
        this._moveTo(this._tree._rtl ? (this._tree.bw.gecko ? this._tree._maxWidth : 0) - this.w : 0, this._tree._currTop);
}

$._updateStyle = function () {
        if (this._tree._format.sel) {
                if (this._isSelected() == !this._lastSelected) {
                        var _backgroundColor = this._tree._format.selC[this._isSelected() ? 1 : 0];

                        with (this._getLayer('a'))
                                if (this._tree.bw.ns4)
                                        bgColor = _backgroundColor;
                                else
                                        style.backgroundColor = _backgroundColor;

                        this._lastSelected = this._isSelected();
                }

                if (this._tree.bw.dom) {
                        if (_und(this._originalClassName))
                                this._lastCssClass = this._originalClassName = this._tree._getElement(this._id + 'an').className;

                        var _cssClass = this._getCssClass();

                        if (_cssClass != this._lastCssClass) {
                                this._getLayer('an').className = this._lastCssClass = _cssClass;
                                this.h = 0;
                        }
                }
        }
}

$._updateDimensions = function (_force) {
        if (!this.h || _force) {
                if (this._tree.bw.gecko)
                        with (this._getLayer().childNodes[0]) {
                                this.w = offsetWidth;
                                this.h = offsetHeight;
                        }
                else if (this._tree.bw.ns4)
                        with (this._getLayer()) {
                                this.w = clip.width;
                                this.h = clip.height;
                        }
                else
                        with (this._getLayer()) {
                                this.w = offsetWidth || scrollWidth || style.pixelWidth;
                                this.h = offsetHeight || scrollHeight || style.pixelHeight;
                        }

                if (this._tree._needAdvancedWrapping())
                        this._exceeds = this._tree._format.exp && this.h > this._tree._format.expimgsize[1];
        }
}

$.draw = function (_visible) {
        var _visibilityChanged = this.visible != _visible;
        var _wasAttached = this._layersAttached;
        var _wasExceeding = this._exceeds;

        if (this._isRoot()) {
                this._tree._currTop = 0;
                this.visible = _visible;
        } else if (this._y < this._tree._redrawAfter) {
                this._tree._currTop = this._y + this.h;
                this._tree._maxWidth = this._maxWidth;
                this._tree._maxHeight = this._maxHeight;
        } else if (this.visible || _visible) {
                this._tree._redrawAfter = -1;
                this.visible = _visible;
                this._updateVisibility();

                if (this.visible) {
                        this._updatePosition();
                        if (_wasAttached)
                                this._updateStyle();
                        this._updateDimensions();

                        if ( this._exceeds && this._previousExpanded != this._isExpanded() && _wasAttached) {
                                this._actuallyDetachLayers();
                                this.visible = _visible;
                                this._updateDimensions();
                                this._updatePosition();
                                this._updateVisibility();
                        }

                        this._tree._maxWidth = Math.max(this._tree._format.left + this.w, this._tree._maxWidth);
                        this._tree._currTop += this.h;
                        this._tree._maxHeight = Math.max(this._tree._currTop, this._tree._maxHeight);

                        this._maxWidth = this._tree._maxWidth;
                        this._maxHeight = this._tree._maxHeight;
                }
        }

        if (
                (this.visible && (this._previousExpanded || this._isExpanded()))
                || (!this.visible && _visibilityChanged && this._previousExpanded)
        )
                this._drawChildren(this._isExpanded() && this.visible);

        this._previousExpanded = this._isExpanded();
}

$._drawChildren = function (_visible) {
        for (var i = 0; i < this._getNumberOfChildren(); i++)
                this._getChild(i).draw(_visible);
}

$._isSelected = function () {
        return this._tree._isNodeSelected(this);
}

$._getNumberOfChildren = function () {
        return this._definition._children.length;
}

$._getChild = function (_minorIndex) {
        with (this._definition._children[_minorIndex]) {
                if (!_object)
                        _object = this._tree._readNode(this._definition._children[_minorIndex], this, _minorIndex < this._getNumberOfChildren() - 1);
                return _object;
        }
}

$.getMinorIndex = function () {
        var _result = 0;

        while (_result < this._parentNode._definition._children.length)
                if (this._parentNode._definition._children[_result]._index == this._index)
                        return _result;
                else
                        _result++;

        return null;
}

$.addNode = function (_minorIndex, _raw) {
        return this._getChild(this._tree._insertNodes(this._index, _minorIndex, [ _raw ]));
}

$.recreate = function (_raw, _reuseChildren) {
        this._tree._replaceDefinition(this._index, _raw, false, false, _reuseChildren);
        this._tree._redraw();
}

$.deleteNode = function (_index) {
        this._tree._deleteNode(this._definition, _index);
}

$.getLayer = $._getLayer = function (_suffix) {
        if (!_suffix)
                _suffix = 'd';

        if (!this._layers[_suffix]) {
                this._attachLayers();
                return this._layers[_suffix] = this._tree._getElement(this._id + _suffix);
        }

        return this._layers[_suffix];
}

function _COOLjsTreeBackPRO(_color, _tree) {
        this._tree = _tree;
        this.left = _tree._format.left;
        this.top = _tree._format.top;
        this._name = 'cls' + _tree._name + '_back';
        this.color = _color;
        this._getLayer = function () {
                return this._tree._getElement(this._name);
        }
        this._resize = function (_width, _height) {
                if (this._tree.bw._operaOld && !this._first) {
                        this._first = true;
                        return;
                } else with (this._getLayer()) {
                        if (this._tree.bw.ns4)
                                this._getLayer().resizeTo(_width, _height);
                        else {
                                style.width = _width + this._tree._px;
                                style.height = _height + this._tree._px;
                        }
                }
        }
        this._init = function (_html) {
                var p = 'relative', l = 0, t = 0, w = 1, h = 1;
                if (this._tree._format.rel) {
                        w = this._tree._format.rels[0];
                        h = this._tree._format.rels[1];
                } else {
                        l = this.left;
                        t = this.top;
                        p = 'absolute';
                }

                return '<div style="overflow:' + (this._tree._operaOld ? 'scroll' : 'hidden') + ';' + (this.color == "" ? "" : (this._tree.bw.ns4 ? 'layer-' : '') + 'background-color:' + this.color + ";") + 'position:' + p + ';top:' + t + 'px;left:' + l + 'px;width:' + w + 'px;height:' + h + 'px;z-index:0;" id="' + this._name + '">'
                        + (this._tree.bw.ns4 ? '<img src="' + this._tree._format.iE + '" width="' + w + '" height="' + h + '" />' : '') + (this._tree._format.rel ? _html + '</div>' : '</div>' + _html);
        }
}

function _und(_value) {
        return typeof(_value) == 'undefined';
}

_attribute = function (_name, _value) {
        return _value ? ' ' + _name + '="' + _value + '"' : '';
}

function _RedrawAllTrees() {
        for (var i in window.CTrees) {
                window.CTrees[i]._redrawComplete = true;
                window.CTrees[i].draw();
        }
}

function RedrawAllTrees() {
        if (!new _BrowserDetector().ns4)
                _RedrawAllTrees();
}

function _BrowserDetector() {
        var _is_major = parseInt(navigator.appVersion);

        this.ver = navigator.appVersion;
        this.agent = navigator.userAgent;
        this.dom = document.getElementById ? 1 : 0;
        this.opera = window.opera ? 1 : 0;
        this.ie5 = this.ver.match(/MSIE 5/) && this.dom && !this.opera;
        this.ie6 = this.ver.match(/MSIE 6/) && this.dom && !this.opera;
        this.ie4 = document.all && !this.dom && !this.opera;
        this.ie = this.ie4 || this.ie5 || this.ie6;

        this.ie3 = this.ver.match(/MSIE/) && _is_major < 4;
        this.hotjava = this.agent.match(/hotjava/i);
        this.ns4 = document.layers && !this.dom && !this.hotjava;

        this._ver3 = this.hotjava || this.ie3;
        this.opera7 = this.agent.match(/opera.7/i);
        this.gecko = this.agent.match(/gecko/i);
        this._operaOld = this.opera && !this.opera7;
}

function _preloadImages(_list) {
        for (var i in _list)
                (new Image()).src = _list[i];
}

window._oldCTPOnLoad = window.onload;
window._oldCTPOnUnLoad = window.onunload;

window.onload = function () {
        var bw = new _BrowserDetector();
        if (bw._operaOld)
                window.operaResizeTimer = setTimeout('resizeHandler()', 1000);
        if (typeof(window._oldCTPOnLoad) == 'function')
                window._oldCTPOnLoad();
        if (bw.ns4) {
                window.onresize=resizeHandler;
                _RedrawAllTrees();
        }
}

window.onunload = function () {
        for (var i in window.CTrees)
                with (window.CTrees[i])
                        if (_format.cook) {
                                _setCookie('Selected', _selectedNodeIndex);
                                _setCookie('State', _getState());
                        }
        if (typeof(window._oldCTPOnUnLoad) == 'function')
                window._oldCTPOnUnLoad();
}

function resizeHandler() {
        if (window.reloading) return;
        if (!window.origWidth){
                window.origWidth=window.innerWidth;
                window.origHeight=window.innerHeight;
        }
        var reload=window.innerWidth != window.origWidth || window.innerHeight != window.origHeight;
        window.origWidth=window.innerWidth;window.origHeight=window.innerHeight;
        if (window.operaResizeTimer)clearTimeout(window.operaResizeTimer);
        if (reload) {window.reloading=1;document.location.reload();return};
        if (new _BrowserDetector()._operaOld){window.operaResizeTimer=setTimeout('resizeHandler()',500)};
}

