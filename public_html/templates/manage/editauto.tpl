{literal}
<script language="JavaScript">
<!--

function switchOverride( o ) {
	if ( o.value != '0' )
		document.getElementById( 'hideable' ).style.display='none'
	else
		document.getElementById( 'hideable' ).style.display='block';
}


function checkForm() {
	
	theForm = document.backupForm;
	
	if ( !theForm.name.value ) {
		alert( 'Please enter auto-backup name' );
		return false;
	}
	
	if ( theForm.backup_id.value==0 ) {
		alert( 'Please select confugured backup' );
		return false;
	}
	
	if ( !isValidEmail( theForm.email.value ) ) {
		alert( 'Please enter valid e-mail address' );
		return false;
	}
	
	if ( !theForm.subject.value ) {
		alert( 'Please select e-mail subject' );
		return false;
	}
}

-->
</script>

<style>
#inputForm label, #inputForm input, #inputForm select, #inputForm textarea {
	display: block;
	float: left;
	margin-bottom: 10px;
}

#inputForm label {
	text-align: right;
	width: 150px;
	padding-right: 20px;
}

#inputForm br {
	clear: left;
}

#inputForm .button {
	margin-left: 5px;
}

</style>
{/literal}

<table border=0 cellpadding=0 cellspacing=5 class=normal>

<tr><td colspan=2>{$logoutLink} {$pathway}</td></tr>
<tr><td colspan="2">[ <a href="backup.php?action=backupScreen">Backup</a> ] [ <a href="backup.php?action=restoreScreen">Restore</a> ] [ <a href="autoBackups.php">Auto-Backup</a> ]</td></tr>
</table>

<p class="subtitle">Auto-Backup Details</p>

<p class="normal">After saving the auto-backup configuration, you will be able to set up a cronjob to receive backups by e-mail.</p>

<form method="post" name="backupForm" id="inputForm" class="normal">
<input type="hidden" name="backupFormSubmitted" value="1">
<input type="hidden" name="id" value="{$backup.id}">

<label for="name">Name:</label>
<input type="text" name="name" value="{$backup.name}"><br />

<!--<label for="override_id">Use settings from:</label>
<select name="override_id" onchange="javascipt: switchOverride( this )">{html_options values=$abackupIds output=$abackupTitles selected=$backup.override_id}</select><br />
-->  
<label for="backup_id">Select configured backup:</label>
<select name="backup_id">{html_options values=$backupIds output=$backupTitles selected=$backup.backup_id}</select><br />

<label for="email">E-Mail Address:</label>
<input type="text" name="email" value="{$backup.email}"><br />

<label for="subject">E-Mail Subject:</label>
<input type="text" name="subject" value="{$backup.subject|default:'New Backup'}"><br />

<label for="message">Include this Message with the Backup:</label>
<textarea name="message" cols="30" rows="5">{$backup.message}</textarea><br />

<br />
<input type="submit" name="submitButton" value="Submit" class="button" onclick="return checkForm();"> <input type="reset" name="resetButton" value="Reset" class="button"> <input type="button" name="cancelButton" value="Cancel" class="button" onclick="javascript: document.location.href='autoBackups.php'">
</form><br /><br />