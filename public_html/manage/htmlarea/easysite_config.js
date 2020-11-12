function EasySite() {
}


EasySite.Config = function () {

	this.imgURL = "images/";
	this.popupURL = "popups/";
	
	/** CUSTOMIZING THE TOOLBAR
	 * -------------------------
	 *
	 * It is recommended that you customize the toolbar contents in an
	 * external file (i.e. the one calling HTMLArea) and leave this one
	 * unchanged.  That's because when we (InteractiveTools.com) release a
	 * new official version, it's less likely that you will have problems
	 * upgrading HTMLArea.
	 */
	this.toolbar = [
		[ "fontname", "space",
		  "fontsize", "space",
		  "formatblock", "space",
		  "bold", "italic", "underline", "strikethrough", "separator",
		  "subscript", "superscript", "separator",
		  "copy", "cut", "paste", "space", "undo", "redo" ],

		[ "justifyleft", "justifycenter", "justifyright", "justifyfull", "separator",
		  "lefttoright", "righttoleft", "separator",
		  "insertorderedlist", "insertunorderedlist", "outdent", "indent", "separator",
		  "forecolor", "hilitecolor", "separator",
		  "inserthorizontalrule", "createlink", "insertimage", "inserttable", "htmlmode", "separator",
		  "es_insert_variable", "separator", "popupeditor", "separator", "showhelp", "about" ]
	];


	function cut_copy_paste(e, cmd, obj) {
		e.execCommand(cmd);
	};

	// ADDING CUSTOM BUTTONS: please read below!
	// format of the btnList elements is "ID: [ ToolTip, Icon, Enabled in text mode?, ACTION ]"
	//    - ID: unique ID for the button.  If the button calls document.execCommand
	//	    it's wise to give it the same name as the called command.
	//    - ACTION: function that gets called when the button is clicked.
	//              it has the following prototype:
	//                 function(editor, buttonName)
	//              - editor is the HTMLArea object that triggered the call
	//              - buttonName is the ID of the clicked button
	//              These 2 parameters makes it possible for you to use the same
	//              handler for more HTMLArea objects or for more different buttons.
	//    - ToolTip: default tooltip, for cases when it is not defined in the -lang- file (HTMLArea.I18N)
	//    - Icon: path to an icon image file for the button (TODO: use one image for all buttons!)
	//    - Enabled in text mode: if false the button gets disabled for text-only mode; otherwise enabled all the time.
	this.btnList = {
		bold: [ "Bold", "ed_format_bold.gif", false, function(e) {e.execCommand("bold");} ],
		italic: [ "Italic", "ed_format_italic.gif", false, function(e) {e.execCommand("italic");} ],
		underline: [ "Underline", "ed_format_underline.gif", false, function(e) {e.execCommand("underline");} ],
		strikethrough: [ "Strikethrough", "ed_format_strike.gif", false, function(e) {e.execCommand("strikethrough");} ],
		subscript: [ "Subscript", "ed_format_sub.gif", false, function(e) {e.execCommand("subscript");} ],
		superscript: [ "Superscript", "ed_format_sup.gif", false, function(e) {e.execCommand("superscript");} ],
		justifyleft: [ "Justify Left", "ed_align_left.gif", false, function(e) {e.execCommand("justifyleft");} ],
		justifycenter: [ "Justify Center", "ed_align_center.gif", false, function(e) {e.execCommand("justifycenter");} ],
		justifyright: [ "Justify Right", "ed_align_right.gif", false, function(e) {e.execCommand("justifyright");} ],
		justifyfull: [ "Justify Full", "ed_align_justify.gif", false, function(e) {e.execCommand("justifyfull");} ],
		insertorderedlist: [ "Ordered List", "ed_list_num.gif", false, function(e) {e.execCommand("insertorderedlist");} ],
		insertunorderedlist: [ "Bulleted List", "ed_list_bullet.gif", false, function(e) {e.execCommand("insertunorderedlist");} ],
		outdent: [ "Decrease Indent", "ed_indent_less.gif", false, function(e) {e.execCommand("outdent");} ],
		indent: [ "Increase Indent", "ed_indent_more.gif", false, function(e) {e.execCommand("indent");} ],
		forecolor: [ "Font Color", "ed_color_fg.gif", false, function(e) {e.execCommand("forecolor");} ],
		hilitecolor: [ "Background Color", "ed_color_bg.gif", false, function(e) {e.execCommand("hilitecolor");} ],
		inserthorizontalrule: [ "Horizontal Rule", "ed_hr.gif", false, function(e) {e.execCommand("inserthorizontalrule");} ],
		createlink: [ "Insert Web Link", "ed_link.gif", false, function(e) {e.execCommand("createlink", true);} ],
		insertimage: [ "Insert/Modify Image", "ed_image.gif", false, function(e) {e.execCommand("insertimage");} ],
		inserttable: [ "Insert Table", "insert_table.gif", false, function(e) {e.execCommand("inserttable");} ],
		htmlmode: [ "Toggle HTML Source", "ed_html.gif", true, function(e) {e.execCommand("htmlmode");} ],
		popupeditor: [ "Enlarge Editor", "fullscreen_maximize.gif", true, function(e) {e.execCommand("popupeditor");} ],
		about: [ "About this editor", "ed_about.gif", true, function(e) {e.execCommand("about");} ],
		showhelp: [ "Help using editor", "ed_help.gif", true, function(e) {e.execCommand("showhelp");} ],
		undo: [ "Undoes your last action", "ed_undo.gif", false, function(e) {e.execCommand("undo");} ],
		redo: [ "Redoes your last action", "ed_redo.gif", false, function(e) {e.execCommand("redo");} ],
		es_insert_variable: [ "Insert Internal Variable", "es_insert_variable.gif", false, insertVariable ],
		cut: [ "Cut selection", "ed_cut.gif", false, cut_copy_paste ],
		copy: [ "Copy selection", "ed_copy.gif", false, cut_copy_paste ],
		paste: [ "Paste from clipboard", "ed_paste.gif", false, cut_copy_paste ],
		lefttoright: [ "Direction left to right", "ed_left_to_right.gif", false, function(e) {e.execCommand("lefttoright");} ],
		righttoleft: [ "Direction right to left", "ed_right_to_left.gif", false, function(e) {e.execCommand("righttoleft");} ]
	};
	/* ADDING CUSTOM BUTTONS
	 * ---------------------
	 *
	 * It is recommended that you add the custom buttons in an external
	 * file and leave this one unchanged.  That's because when we
	 * (InteractiveTools.com) release a new official version, it's less
	 * likely that you will have problems upgrading HTMLArea.
	 *
	 * Example on how to add a custom button when you construct the HTMLArea:
	 *
	 *   var editor = new HTMLArea("your_text_area_id");
	 *   var cfg = editor.config; // this is the default configuration
	 *   cfg.btnList["my-hilite"] =
	 *	[ function(editor) { editor.surroundHTML('<span style="background:yellow">', '</span>'); }, // action
	 *	  "Highlight selection", // tooltip
	 *	  "my_hilite.gif", // image
	 *	  false // disabled in text mode
	 *	];
	 *   cfg.toolbar.push(["linebreak", "my-hilite"]); // add the new button to the toolbar
	 *
	 * An alternate (also more convenient and recommended) way to
	 * accomplish this is to use the registerButton function below.
	 */
	for (var i in this.btnList) {
		var btn = this.btnList[i];
		btn[1] = _editor_url + this.imgURL + btn[1];
		if (typeof HTMLArea.I18N.tooltips[i] != "undefined") {
			btn[0] = HTMLArea.I18N.tooltips[i];
		}
	}

}


function insertVariable(editor, buttonId) {
	
		var outparam = null;
		
		Dialog( "variableChooser.php?htmlarea=1", function(param) {
		
		if (!param) {	// user must have pressed Cancel
			return false;
		}
		editor.insertHTML( '{internal resource_type=\''+param.resource_type+'\' resource_id=\''+param.resource_id+'\'}' );
		return true;
	}, null);

}