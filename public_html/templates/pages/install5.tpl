{literal}
<script language="javascript">
<!--
function fieldsAreValid() {
	var theForm = document.installInfo;
	
	if ( theForm.mailType.value == 'smtp' && (theForm.smtpHost.value == '' || theForm.smtpPort.value == '' ) ) {
		alert( 'Please specify the SMTP host and port' );
		return false;
	}
	if ( theForm.mailType.value == 'sendmail' && theForm.smPath.value == '' ) {
		alert( 'Please specify the sendmail path' );
		return false;
	}
	
	if ( theForm.smtpAuth.checked && ( theForm.smtpUser.value == '' || theForm.smtpPassword.value == '' ) ) {
		alert( 'Please specify the smtp user and password' );
		return false;
	}
	return true;
}
//-->
</script>
{/literal}
<tr><td colspan=2></td></tr>
<tr><td colspan=2 class=subtitle>Step 4: Mail settings (Optional)</td></tr>

<tr><td colspan=2 class=normal>The EasySite installer needs some information about your mail settings, so that form submissions can be correctly processed. If you do not know this information, then please skip this step. EasySite will attempt to use the Standard PHP Mail functions by default.</td></tr>

<form action="?step=6" method="post" align="center" name="installInfo">
<tr><td colspan=2><table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

<tr><td width="30%">Mail format: </td><td><select name="mailFormat">{html_options values=$formatValues output=$formatNames}</select></td></tr>

<tr><td>Send mail by: </td><td><select name="mailType">{html_options values=$typeValues output=$typeNames}</select></td></tr>

<tr><td>SMTP Host: </td><td><input type=text size=20 name=smtpHost></td></tr>

<tr><td>SMTP Port: </td><td><input type=text size=20 name=smtpPort value="25" size=4></td></tr>

<tr><td nowrap>Use SMTP Authenication: </td><td><input type=checkbox size=20 name=smtpAuth value="1"></td></tr>

<tr><td>SMTP User: </td><td valign="top"><input type=text size=20 name=smtpUser></td></tr>

<tr><td>SMTP Password: </td><td><input type=text size=20 name=smtpPassword>

<tr><td>Path to Sendmail: </td><td><input type=text size=20 name=smPath value="{$sendMailPath}"></td></tr>

<tr><td colspan=2>You must input the path to Sendmail if you chose Sendmail above. If you do not know this value, you should contact your website administrator.<br /><br />If you choose the SMTP mail option, please note that some commercial mail services, like Yahoo, GMail, and Hotmail, usually reject SMTP mail requests from PHP scripts. You should not choose this option unless you control your own SMTP mail server. In other words, using <i>smtp.mail.yahoo.com</i> will not work.</td></tr>

</td></tr>
</td></tr></table>
<tr><td>&nbsp;</td><td align=right><input type=submit name=submit value="Continue >>" onclick="javascript:return fieldsAreValid();"></td></tr>
</form>