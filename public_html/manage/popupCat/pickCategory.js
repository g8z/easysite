var C = new Picker();

function popup( combo, field ) {
    this.field = field;
    this.combo = combo;
	var width = 400;
	var height = 400;

	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

    //win = launchCentered( 'popupCat/index.php', 400, 400, 'scrollbars=yes,resizable=yes,status=yes,dependent=yes' );
    win = window.open( 'popupCat/index.php', null, "top="+top+",left="+left+",width="+width+",height="+height+",status=yes,scrollbars=yes,resizable=yes,dependent=yes", true );
    win.opener = window;
}

function Picker() {
    this.popup = popup;
    this.title = '';
    this.value = '';
}
