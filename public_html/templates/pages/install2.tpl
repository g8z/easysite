<tr><td colspan=2>
{literal}
<script language="JavaScript">
<!--

function switchDiv( divId ) {
	var div;
	var adiv;
	if ( document.getElementById ) {
		div = document.getElementById( divId );
		adiv = document.getElementById( 'a' + divId );
		if ( div.style.display == 'none' ) {
			div.style.display = 'block';
			adiv.innerHTML = 'Hide Details';
		}
		else {
			div.style.display = 'none';
			adiv.innerHTML = 'Show Details';
		}
	}
}

-->
</script>
{/literal}
<br />

<table width="100%" style="border:1px solid #000000" bgcolor="EEEEEE" class=normal>

<tr><td>
<font color=green>The database connection was established successfully.</font>
</td></tr>

<tr><td>

{if $configCreated eq 1}<font color=green>The config.php file was created and updated successfully.</font>{/if}

{if $configCreated eq 0}<font color="red"><b>ERROR: </b> The config.php file was not created successfully. There may still be a write permissions problem with config.php that the EasySite installer could not detect. Please contact your website host or administrator regarding this problem.</font>{/if}
</td></tr>

{if $upgrade}

	<tr><td>

	{if $upgradeSuccessull eq 1}<font color=green>The database tables were successfully upgraded.</font>{/if}
	
	{if $upgradeSuccessull eq 0}<font color="red"><b>ERROR: </b> One or more database tables could not be upgraded. Check the appropriate SQL file to make sure there are no syntax errors in it, and be sure that you have the correct prior version of EasySite.</font>{/if}

	</td></tr>

{else}

	<tr><td>

	{if $tablesCreated eq 1}<font color=green>The database tables were created successfully.</font>{/if}
	
	{if $tablesCreated eq 0}
	  <font color="red"><b>ERROR: </b> One or more database tables could not be created. Check the SQL file to make sure there are no syntax errors in it.</font> [ <a href="#" onclick="javascript: switchDiv( 'tableError' ); return false;" id="atableError">Show Details</a> ]
	  <div id="tableError" style="display: none;">
	    <b>Error Message:</b> {$tableError.message}<br />
	    <b>Error Code:</b> {$tableError.code}<br />
	    <b>More Info:</b> {$tableError.info}<br />
	  </div>
	{/if}

	</td></tr>

	<tr><td>

	{if $sampleInserted eq 1}<font color=green>The sample data was inserted successfully.</font>{/if}

	{if $sampleInserted eq 0}
	  <font color="red"><b>ERROR: </b> Some of the sample data from {$smarty.const.SAMPLE_FILE} could not be added. Check the SQL file to make sure there are no syntax errors in it.<br /><br />If you have installed EasySite previously, some of this sample data may already exist, in which case you may not need to do anything. Or, you can return to the previous step, choose a different table prefix, and try this step (insertion of sample data) again.</font> [ <a href="#" onclick="javascript: switchDiv( 'sampleError' ); return false;" id="asampleError">Show Details</a> ]
	  <div id="sampleError" style="display: none;">
	    <b>Error Message:</b> {$sampleError.message}<br />
	    <b>Error Code:</b> {$sampleError.code}<br />
	    <b>More Info:</b> {$sampleError.info}<br />
	  </div>
	{/if}

	</td></tr>
{/if}

</td></tr>

</table>

<tr><td>

{if ($tablesCreated ne 1 or $configCreated ne 1 or $sampleInserted ne 1) and $upgrade ne 1}

	There were some problems with the installation.<br /><br />Please check to ensure that there are no errors in prior steps of the installation. If you have made any modifications to the {$smarty.const.SQL_FILE} or {$smarty.const.SAMPLE_FILE} files, check carefully to be sure that there are no syntax errors in these files.

	<!-- the database login parameters are correct, and that the database username and password that you have provided provide sufficient permissions to create and update tables. -->

	<br /><br />

	<input type="button" name="start" onclick="javascript:document.location.href='?step=2'" value="<< Back">
	
{elseif $upgrade eq 1 and $upgradeSuccessull ne 1}

There were some problems with the upgrade.<br /><br />Please check to ensure that there are no errors in the upgrade file, and that you have not attempted to repeat the upgrade on an already-upgraded system.
<br /><br />

<input type="button" name="start" onclick="javascript:document.location.href='?step=2'" value="<< Back">

{else}

<p class=normal>The final step of the EasySite installation is optional, and allows you to set mail settings for purposes of submitting forms. Click Continue below to proceed to this final step.</p>

<input type=submit name=submit value="Continue >>" onclick="javascript:document.location.href='?step={if $upgrade}5{else}4{/if}'">

{/if}

</td></tr>