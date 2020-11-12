{literal}
<script language=Javascript>
<!--
function submitPage() {
	var formObj = document.choosePage;

	if ( formObj.page_id.value == '' ) {
		formObj.add_page.value = 1;
	}
	else {
		formObj.edit_sections.vlaue = 1;
	}
	formObj.submit();
}
function submitForm() {
	var formObj = document.chooseForm;
	
	if ( formObj.form_id.value == '' ) {
		formObj.add_form.value = 1;
	}
	else {
		formObj.edit_form.value = 1;
	}
	formObj.submit();
}
//-->
</script>
{/literal}

<table>

<tr><td class=title>Content Management Tools</td></tr>

<tr><td class=normal>{$logoutLink} {$userGuideLink}</td></tr>

{if $smarty.session.cm_auth.cm_menu}
<tr><td>
<form method="post" action="editMenu.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditMenus.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing menus *}
<tr><td class=subtitle>Menu Manager</td></tr>
<tr><td class=normal>Create one or more menus for navigating your website. Also use this to change the color, position, and style of the menus, and to link pages to menu items.</td></tr>
<tr><td><input type=submit name=edit_menu value="Continue"></td></tr>
</table>

</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_page and ( $smarty.session.cm_auth.cm_page_add or $smarty.session.cm_auth.cm_page_edit or $smarty.session.cm_auth.cm_page_delete ) }
<tr><td>
<form method="post" action="editPages.php" name=choosePage>
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditPages.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing section content for a page *}
<tr><td class=subtitle>Page Editor</td></tr>
<tr><td class=normal>Which page would you like to edit?</td></tr>
<tr><td nowrap>
<select name=page_id>
{html_options values=$page_ids output=$page_titles}
</select>

<input type=button value="Continue" onClick="javascript:submitPage();">
<input type=hidden name=edit_sections value="">
<input type=hidden name=add_page value="">
</table>

</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_layer}
<tr><td>
<form method="post" action="editLayers.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditLayers.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing page or template layers *}
<tr><td class=subtitle>Layers</td></tr>
<tr><td class=normal>Floating boxes, containing text or images, that you can position at any coordinates on the screen.</td></tr>
<tr><td class=normal>

<select name=layer_id>
{html_options values=$layer_ids output=$layer_titles}
</select>

<input type=submit name="layers" value="Continue">
</td></tr>
</table>

</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_settings}
<tr><td>
<form method="post" action="globalSettings.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/SiteSettings.gif" height="100" width="100"></td><td valign=top>
<table border=0 cellpadding=2 cellspacing=0>

{* form for editing global settings *}
<tr><td class=subtitle>Global Settings</td></tr>
<tr><td class=normal>Edit settings like the background color of the page, site administrator information, x/y properties for the main body section, and more.</td></tr>
<tr><td class=normal><input type=submit name=editSiteSettings value="Continue"></td></tr>

</table>
</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_style}
<tr><td>
<form method="post" action="editStyles.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditCSS.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing css styles *}
<tr><td class=subtitle>Style Editor</td></tr>
<tr><td class=normal>Edit the styles and font properties for the site, or create your own. These are the styles that appear in the "style" drop-down list of the other tools.</td></tr>
<tr><td class=normal><input type=submit name=styles value="Continue"></td></tr>
</table>

</td></tr></table>
</form>
</td></tr>
{/if}

{if $smarty.session.cm_auth.cm_skin}
<tr><td>
<form method="post" action="editSkins.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditSkins.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing saved skins *}
<tr><td class=subtitle>Skins</td></tr>
<tr><td class=normal>Now that you have the styles & settings that you like, save them as a skin. You may also apply different skins to different pages and forms of your site.</td></tr>
<tr><td class=normal><input type=submit name=skins value="Continue"></td></tr>
</table>

</td></tr></table>
</form>
</td></tr>
{/if}



{if $smarty.session.cm_auth.cm_list}
<tr><td>
<form method="post" action="listIndex.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/Lists.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>
{* form for editing saved skins *}
<tr><td class=subtitle>Lists</td></tr>
<tr><td class=normal>Create lists for various purposes - as option combo lists for Forms, or as search option lists for modules.</td></tr>
<tr><td class=normal><input type=submit name=lists value="Continue"></td></tr>
</table>

</td></tr></table>
</form>
</td></tr>
{/if}



{if $smarty.session.cm_auth.cm_form and ( $smarty.session.cm_auth.cm_form_add or $smarty.session.cm_auth.cm_form_edit or $smarty.session.cm_auth.cm_form_delete ) }
<tr><td>
<form method="post" action="editForms.php" name=chooseForm>
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditForms.gif" height="100" width="100"></td><td valign=top>
<table border=0 cellpadding=2 cellspacing=0>
{* form for editing global settings *}
<tr><td class=subtitle>Form Editor</td></tr>
<tr><td class=normal>Quickly create user feedback forms using this tool. Configure the form to send the form contents to any email address.</td></tr>
<tr><td class=normal>

<select name=form_id>
{html_options values=$form_ids output=$form_titles}
</select>

<input type=button value="Continue" onClick="javascript:submitForm();">
<input type=hidden name=add_form value="">
<input type=hidden name=edit_form value="">
<input type=hidden name=forms value=1>
</td></tr>
</table>
</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_report}
<tr><td>
<form method="post" action="reports.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/Reports.gif" height="100" width="100"></td><td valign=top>
<table border=0 cellpadding=2 cellspacing=0>

{* form for editing global settings *}
<tr><td class=subtitle>Reports</td></tr>
<tr><td class=normal>Create advanced reports from using form submission data, using custom layouts, and advanced grouping and sorting options.</td></tr>
<tr><td class=normal><input type=submit name=files value="Continue"></td></tr>

</table>

</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_file}
<tr><td>
<form method="post" action="editFiles.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/EditFiles.gif" height="100" width="100"></td><td valign=top>
<table border=0 cellpadding=2 cellspacing=0>

{* form for editing global settings *}
<tr><td class=subtitle>File Manager</td></tr>
<tr><td class=normal>Upload files, such as Word/Excel documents, PDF files, and other types of 'large files'. These can then be downloaded by your users.</td></tr>
<tr><td class=normal><input type=submit name=files value="Continue"></td></tr>

</table>

</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_users}
<tr><td>
<form method="post" action="usersAndGroups.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/UserGroups.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>

{* form for editing users & groups *}
<tr><td class=subtitle>Users & Groups</td></tr>
<tr><td class=normal>Restrict access to any page, form, or file. You may establish 'groups' with predefined permissions or set custom permissions for any user.</td></tr>
<tr><td class=normal><input type=submit name=users value="Continue"></td></tr>

</table>
</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_share}
<tr><td>
<form method="post" action="editShares.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/shares.gif" height="100" width="100"></td><td valign=top>

<table border=0 cellpadding=2 cellspacing=0>

{* form for editing shares *}
<tr><td class=subtitle>Shares</td></tr>
<tr><td class=normal>Share any resource with any group or user.</td></tr>
<tr><td class=normal><input type=submit name=shares value="Continue"></td></tr>

</table>
</td></tr></table>
</form>
</td></tr>
{/if}

{if $smarty.session.cm_auth.cm_backup}
<tr><td>
<form method="post" action="backup.php">
<table border=0 cellpadding=5 cellspacing=0><tr><td><input type="image" src="{$docroot}images/icons/SaveRestore.gif" height="100" width="100"></td><td valign=top>
<table border=0 cellpadding=2 cellspacing=0>

{* form for backing up and restoring mysql tables *}
<tr><td class=subtitle>Backup & Restore</td></tr>
<tr><td class=normal>It is <b>strongly</b> recommended that you backup your system after making changes using these content management tools. Backups are saved in SQL format.</td></tr>
<tr><td class=normal><input type=submit name=backup value="Continue"></td></tr>

</table>
</td></tr></table>
</form>
</td></tr>
{/if}


{if $smarty.session.cm_auth.cm_module}
<tr><td>

<table border=0 cellpadding=5 cellspacing=0>

<tr><td valign="top"><img src="{$docroot}images/icons/Modules.gif" height="100" width="100"></td>
<td valign=top>

	{* link to module manager *}
	<form method="post" action="modules.php">
	<table border=0 cellpadding=2 cellspacing=0>
	<tr><td class=subtitle>Modules</td></tr>
	<!--<tr><td class=normal><input type=submit name=modules value="Continue"></td></tr>-->
	</table>
	</form>
	
	
	<form action='modules.php' method=post>
	<table class=normal>
	<!--<tr><td>Installed Modules:</td></tr>-->
	
	<tr>
	    <td valign=top>
	        <table cellpadding=1 cellspacing=0 border=0 class=normal>
	{foreach item=module from=$modules}
	
	            <tr>
	            	<td><strong>{$module.title} {$module.version}</strong>&nbsp;</td>
	            	<td>
	            	<a target=_blank href="{$docroot}modules/{$module.module_key}/index.php">View</a> |
	            	<a href="{$docroot}modules/{$module.module_key}/manage/index.php">Manage</a>
	            	
	            </td></tr>
	{foreachelse}
	<tr><td colspan=2>There are no modules currently installed.</td></tr>
	{/foreach}
	
	        </table>
	    </td>
	</tr>
	</table>
	</form>	
	
	<!--<form method="post" action="modules.php">
	<input type=submit name=modules value="View Detailed">
	</form>-->



</td></tr>


</table>

</td></tr> 
{/if}


<tr><td class=normal>{$logoutLink} {$userGuideLink}</td></tr>

</table>