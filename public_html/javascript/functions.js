function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function launchCentered( url, width, height ) {
	launchCentered( url, width, height, '' );
}


function MyLaunchCentered( url, width, height, options) {
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

	var options = "top="+top+",left="+left+",width="+width+",height="+height+","+options;
	return window.open( url, (new Date()).getTime(), options );
}


function launchCentered( url, width, height, options) {
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;

	var options = "top="+top+",left="+left+",width="+width+",height="+height+","+options;
	launch( url, options );
}

function launch( url, params ) {
	self.name = 'opener';
	var remote = window.open( url, (new Date()).getTime(), params );
}

String.prototype.trim = function() {
	var x=this;
	x=x.replace( /^\s*/, "" );
	x=x.replace( /\s*$/, "" );
	return x;
}

function isValidEmail( fieldValue ) {
	if ( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,7})+$/.test(fieldValue) )
		return true;

	return false;
}

function isValidURL(url) {

	if ( url == null )
		return false;

// space extr
	var reg='^ *';
//protocol
	reg = reg+'(?:([Hh][Tt][Tt][Pp](?:[Ss]?))(?:\:\\/\\/))?';
//usrpwd
	reg = reg+'(?:(\\w+\\:\\w+)(?:\\@))?';
//domain
	reg = reg+'([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|localhost|([Ww][Ww][Ww].|[a-zA-Z0-9].)[a-zA-Z0-9\\-\\.]+\\.[a-zA-Z]{2,6})';
//port
	reg = reg+'(\\:\\d+)?';
//path
	reg = reg+'((?:\\/.*)*\\/?)?';
//filename
	reg = reg+'(.*?\\.(\\w{2,4}))?';
//qrystr
	reg = reg+'(\\?(?:[^\\#\\?]+)*)?';
//bkmrk
	reg = reg+'(\\#.*)?';
// space extr
	reg = reg+' *$';

	return url.match(reg);
}

// returns true if checkStr contains only characters specified in checkOK
// probably can be replaced with a more efficient regular expression

function isValidString( checkStr, checkOK ) {

	if ( !checkOK )
		var checkOK = '';

	var allValid = true;

	//return checkStr.match( '^(['+checkOk+']+)$' );

	for (i = 0;  i < checkStr.length;  i++) {
		ch = checkStr.charAt(i);

		for (j = 0;  j < checkOK.length;  j++)
			if (ch == checkOK.charAt(j))
				break;

		if (j == checkOK.length) {
			allValid = false;
			break;
		}
	}

	return allValid;
}

var alphabeticChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
var numericChars = "0123456789";

function isNumeric( val ) {
	return isValidString( val, numericChars );
}

function isNumeric( val, addChars ) {
	return isValidString( val, numericChars + addChars );
}

function isAlphabetic( val ) {
	return isValidString( val, alphabeticChars );
}

function isAlphabetic( val, addChars ) {
	return isValidString( val, alphabeticChars + addChars );
}

function isAlphaNumeric( val ) {
	return isValidString( val, alphabeticChars + numericChars );
}

function isAlphaNumeric( val, addChars ) {
	return isValidString( val, alphabeticChars + numericChars + addChars );
}


// returns true if fieldValue is a valid hex color code, with # in front
// like #FA329C
function isColorCode( fieldValue ) {
	var checkOK = "#0123456789ABCDEFabcdef";
	var checkStr = fieldValue;
	var allValid = true;
	var allNum = "";

	for (i = 0;  i < checkStr.length;  i++)
	{
		ch = checkStr.charAt(i);

		for (j = 0;  j < checkOK.length;  j++)
			if (ch == checkOK.charAt(j))
				break;

		if (j == checkOK.length)
		{
			allValid = false;
			break;
		}

		if (ch != ",")
			allNum += ch;
	}

	// now check length and that only first letter is # symbol
	if ( fieldValue.length != 7 || fieldValue.lastIndexOf( '#' ) != 0 )
		allValid = false;

	return allValid;
}



var isIE=document.all?true:false;
var isDOM=document.getElementById?true:false;
var isNS4=document.layers?true:false;

function toggleT(_w,_h) {
  if (isDOM)
  {
    if (_h=='s') document.getElementById(_w).style.visibility='visible';
    if (_h=='h') document.getElementById(_w).style.visibility='hidden';
  }
  else if (isIE) {
    if (_h=='s') eval("document.all."+_w+".style.visibility='visible';");
    if (_h=='h') eval("document.all."+_w+".style.visibility='hidden';");
  }
  else if(isNS4)
  {
    if (_h=='s') eval("document.layers['"+_w+"'].visibility='show';");
    if (_h=='h') eval("document.layers['"+_w+"'].visibility='hide';");
  }
}
