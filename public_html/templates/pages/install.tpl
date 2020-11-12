<tr><td colspan=2></td></tr>
<tr><td colspan=2 class=subtitle>Step 2: Database Configuration</td></tr>
</table>
<form action="?step=3" method="post" align="center" name="installInfo">
{literal}
<script type="text/javascript">
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

  // check if user checked 'backup' checkboxes

  if ( theForm.install_type[1].checked && ( theForm.backup_web.checked == false || theForm.backup_database.checked == false ) ) {
    alert( 'Please confirm that your web files and database have been backed up.' );
    theForm.backup_web.focus();
    return false;
  }
  return true;
}
//-->
</script>
{/literal}
<table align="center" cellpadding="2" cellspacing="7" class="normal" width="70%">
<tr><td colspan=2><br /> Is this a new installation or upgrade from a previous version? You must have at least version 2.0 or higher to upgrade your system. The "Table Prefix" field must be the same as your current installation, otherwise the installer will create new tables. Be sure to BACKUP your system first!</td></tr>

<tr><td colspan=2><br />
<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

<tr>
  <td valign="top">
    <input type=radio name=install_type {if $smarty.post.install_type eq 'new' or $smarty.post.install_type eq ''}checked{/if} value="new">
  </td>
  <td width="100%">
    This is a NEW installation of EasySite<br />
    <input type="checkbox" name="demo_mode" value=1 {if $smarty.post.demo_mode eq 1}checked{/if}>  Install in DEMO mode.<br /><br />
  </td>
</tr>
<tr>
  <td valign="top">
    <input type=radio name=install_type value="upgrade" {if $smarty.post.install_type eq 'upgrade'}checked{/if}>
  </td>
  <td>
    This is an upgrade for my current EasySite installation. The installer will add any fields missing from your current tables. Existing fields will NOT be removed.<br /><br />
    <input type=checkbox name=backup_web value=1 {if $smarty.post.backup_web eq 1}checked{/if}>  I have completely backed up my current EasySite web files.<br />
    <input type=checkbox name=backup_database value=1 {if $smarty.post.backup_database eq 1}checked{/if}>  I have completely backed up my database.<br />
  </td>
</tr>

</table>
</td></tr>

<tr><td colspan=2 class=normal><br />The EasySite installer needs some information about your database to finish the installation. If you do not know this information, then please contact your website host or administrator. Please note that this is probably NOT the same as your FTP login information!</td></tr>

<tr><td colspan=2><br />
<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

{if $errorLogin eq 1}
<tr><td colspan="2" calss=normal><font color="red">The installer has detected that one of the following is invalid: username, password or host. Please check these three values for accuracy and try again.<br /><br />Remember: the database username and password is usually NOT the same as your FTP username and password. For most servers, the host is 'localhost', but you may wish to check with your server administrator.</font><br /></td></tr>
{/if}

{if $errorDB eq 1}
<tr><td colspan="2" calss=normal><font color="red">There was an unknown fatal DB error. Please contact your server administrator to determine what may have caused this error.</font><br /></td></tr>
{/if}

{if $errorDBname}
<tr><td colspan="2" calss=normal>

	<font color="red">The database name you provided was invalid - it either does not exist, or is not accessible with the username, password, and host that you provided.<br /><br />
	The following database(s) can be accessed with this login information:<br /><br /></font>

		{foreach item=itemname from=$errorDBname key=i}
		<b>{$itemname.$i}</b><br />
	 	{/foreach}

	<br /></td></tr>
{/if}

<tr><td width="30%">Database Name: </td><td><input type=text size=20 name=name value="{$name}"></td></tr>

<tr><td>Database User: </td><td><input type=text size=20 name=user value="{$user}"></td></tr>

<tr><td nowrap>Database Password:</td><td valign="top"><input type=password size=20 name=password value="{$password}"></td></tr>

<tr><td>Database Host: </td><td><input type=text size=20 name=host value="{$host}">
</td></tr>

<tr><td>Database Type: </td><td><select name="dbType">{html_options values=$typeValues output=$typeNames selected=$dbType}</select></td></tr>

<tr><td>Table Prefix:</td><td valign="top"><input type=text size=20 name=dbPrefix value="{$dbPrefix}"></td></tr>

<tr><td colspan=2>This prefix will be prepended to any table names that the EasySite installer creates. You may leave this blank if desired.</td></tr>

<tr><td>Document Root:</td><td valign="top"><input type=text size=20 name=docroot value="{$docroot}"></td></tr>

<tr><td colspan=2>If you are installing EasySite at <font color="#0000FF">http://www.yoursite.com/easysite/</font> then the Document Root is <font color="#0000FF">/easysite/</font>. If EasySite will be used for your primary website domain, then use <font color="#0000FF">/</font> as the Document Root.
</td></tr>

{*
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td colspan=2><b>Upgrade or New Install?</b></td></tr>

<tr><td colspan=2><input type=radio name=upgrade value="" {if $version lt 1.41}checked{/if}> This is a new installation, not an upgrade</td></tr>

<tr><td colspan=2><input type=radio name=upgrade value="upgrade_1.4_1.4.1" {if $version eq 1.41}checked{/if}> I am upgrading from 1.4 to 1.4.1</td></tr>

<tr><td colspan=2>Users with versions prior to 1.4 are not able to upgrade. You must perform a fresh installation.</td></tr>
*}

</td></tr></table>
<tr><td>&nbsp;</td><td align=right><input type=submit name=submit value="Continue >>" onclick="javascript:return fieldsAreValid();"></td></tr>
</table>
</form>
<table align="center" cellpadding="2" cellspacing="7" class="normal" width="70%">
