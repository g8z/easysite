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


{if $configCreated eq 1}
<tr><td colspan=2 class=normal><span class=title>Congratulations!</span>

<br /><br />

EasySite now ready to be used. Please be sure to delete the installation file.

<br /><br />

You may also wish to adjust the permissions of the templates, templates_c, temp, and cache folders to the minimum required file-writing permissions for your server, and adjust the permissions of config.php to the minimum required file-reading permissions for your server. If you do not know how to do that, or if you do not know what the minimum required permissions are, please contact your website host. 

<br /><br />

</td></tr>
<tr><td colspan="2"><input type="button" name="finish" onclick="javascript:document.location.href='index.php'" value="Finish"></td></tr>
{else}
<tr><td colspan=2 class=normal>There were some problems with the installation. Please re-check the options from the step 1, and ensure that all files have been correctly uploaded to your server. Then try the installation again. If you continue to experience problems, please report this problem on the <a href="http://www.tufat.com/phpBB2/">TUFaT.com Support Forum</a>.<br /><br /></td></tr>
<tr><td colspan="2"><input type="button" name="start" onclick="javascript:document.location.href='install.php'" value="Reinstall"></td></tr>
{/if}
