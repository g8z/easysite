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
		if ( elemType == 'text' || elemType == 'password' ) {

			if ( elemValue == "" ) {
				alert( 'One or more required fields was left empty.' );
				theForm.elements[i].focus();
				return false;
			}
		}

	}

	if ( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,7})+$/.test(theForm.adminEmail.value) == false ) {
		alert( 'Please enter valid email address.' );
		theForm.adminEmail.focus();
		return false;
	}

	if ( theForm.adminPassword1.value != theForm.adminPassword2.value ) {
		alert( 'Passwords you entered are not the same. Please check them and correct the error.' );
		theForm.adminPassword1.focus();
		return false;
	}


	return true;

}
//-->
</script>
{/literal}

<tr><td colspan=2></td></tr>

<tr><td colspan=2 class=subtitle>Step 4: Administrator Account</td></tr>

{if !$submitted}
<tr><td colspan=2 class=normal>Please provide some information about the site administrator.</td></tr>
</table>

<form method="post" align="center" name="installInfo" method="post">
<table align="center" cellpadding="2" cellspacing="7" class="normal" width="70%">
<tr><td colspan=2>
<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

<tr><td width="30%">Admin Name: </td><td><input type=text size=20 name=adminName value="Website Administrator"></td></tr>

<tr><td>Admin E-Mail: </td><td><input type=text size=20 name=adminEmail value="admin@site.com"></td></tr>

<tr><td>Admin Login: </td><td><input type=text size=20 name=adminLogin value="admin"></td></tr>

<tr><td>Admin Password: </td><td><input type=password size=20 name=adminPassword1 value="pass"></td></tr>

<tr><td>Repeat Password: </td><td><input type=password size=20 name=adminPassword2 value="pass"></td></tr>

<tr><td>Use MD5 user's password encription?: </td><td><select name="use_md5"><option value="yes">yes</option><option value="no">no</option></select></td></tr>

</td></tr></table>
</td></tr>
<tr><td>&nbsp;</td><td align=right><input type=submit name=submit value="Continue >>" onclick="javascript:return fieldsAreValid();"></td></tr>
</table>
</form>
<table align="center" cellpadding="2" cellspacing="7" class="normal" width="70%">
{else}
{if $error}
	<tr><td colspan=2>
	<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>
	<tr><td>
	<font style="color:red;">An error occurred while creating the administrator account.</font>
	</td></tr>
	</table>
	</td></tr>
	<tr><td>&nbsp;</td><td align=right><input type=button value="<< Back" onclick="javascript: document.location.href='?step=4';"></td></tr>
{else}
	<tr><td colspan=2>
	<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>
	<tr><td>
	<font style="color:green;">The administration account was successfully created.</font>
	</td></tr>
	</table>
	</td></tr>
	<tr><td>&nbsp;</td><td align=right><input type=button value="Continue >>" onclick="javascript: document.location.href='?step=5';"></td></tr>
{/if}
{/if}