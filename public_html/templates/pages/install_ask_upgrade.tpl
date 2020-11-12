{literal}
<script language="javascript">
<!--
function fieldsAreValid() {
	var theForm = document.installInfo;

	var formElements = theForm.elements;
	var numElements = theForm.elements.length;

	// determine if valid by comparing size
	// if size = 6 & is not number, then not valid input
	// if size = 10 & is not color code, then not valid input

	for ( var i = 0; i < numElements; i++ ) {
		var elemName = theForm.elements[i].name;
		var elemValue = theForm.elements[i].value;
		var elemSize = theForm.elements[i].size;
		var elemType = theForm.elements[i].type;

		// all fields are required
		if ( elemType == 'text' && elemName != 'password' && elemName != 'dbPrefix' ) {

			if ( elemValue == "" ) {
				alert( 'One or more required fields was left empty.' );
				theForm.elements[i].focus();
				return false;
			}
		}
	}
	return true;
}
//-->
</script>
{/literal}
<tr><td colspan=2></td></tr>



<tr><td colspan=2 class=normal><span class=subtitle>Choose Installation Type</span></td></tr>

<tr><td colspan=2><br /><b>Warning: The database specified in not empty.</b><br /> Is this a new installation or upgrade from a previous version? You must have at least version 2.0 or higher to upgrade your system. The "Table Prefix" field must be the same as your current installation, otherwise the installer will create new tables. Be sure to BACKUP your system first!</td></tr>

<tr><td colspan=2><br />
<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

<tr><td><input type=radio name=install_type></td><td>This is a NEW installation of Easysite</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td><input type=radio name=install_type></td><td>This is an upgrade for my current Easysite installation</td></tr>

</table>

</td></tr>
<tr><td colspan="2"><input type="button" name="finish" onclick="javascript:document.location.href='index.php'" value="Finish"></td></tr>
