{literal}
<script type="text/javascript">
<!--
function validate() {
	var formObj = document.loginForm;
	
	if ( formObj.username.value.trim() == '' ) {
		alert( 'Please input a username.' );
		formObj.username.focus();
		return false;
	}
	else if ( formObj.password.value.trim() == '' ) {
		alert( 'Please input a password.' );
		formObj.password.focus();
		return false;
	}
}
//-->
</script>
{/literal}

{* the login was not found at all in the database *}

{if $login_error eq $smarty.const.LOGIN_NOT_FOUND}

	<p class=normal>
	The username / password combination that you entered could not be found.
	<br />
	<br />
	Please check to ensure that both the username and password are spelled correctly, and that you are accessing a page that you have the necessary permissions to access. Also, be sure to check that your CAPS LOCK and NUM LOCK keys are not activated.
	</font>
	</p>

{* the login was found, but it does not give access to this part of the website *}

{elseif $login_error eq $smarty.const.ACCESS_DENIED}

	<p class=normal>
	{if $smarty.post.username || $smarty.session.es_auth.login_id}
	  The username 

	  {if $smarty.post.username}
		  "{$smarty.post.username}"
	  {else}
		  "{$smarty.session.es_auth.login_id}"
	  {/if}

	  does not provide access to this section of the website.
	  <br />
	  <br />
	  If you believe that you should have access to this area, please contact your system administrator to grant access.
	{else}
	  You have not access to this area.
	{/if}
	</p>
	
{elseif $login_error eq $smarty.const.LOGIN_EXPIRED}

	<p class=normal>
	The username 
	
	{if $smarty.post.username}
		"{$smarty.post.username}"
	{else}
		"{$smarty.session.es_auth.login_id}"
	{/if}
	
	was found, but it has expired. To renew this login name & password, please contact your system administrator.
	</p>

{/if}

{if $smarty.const.DEMO_MODE}
<p class=normal><b>You are currently using EasySite in DEMO mode.</b> The default username and password for demo mode is 'admin' and 'pass'. These values have been inputted for you. In the non-demo version of EasySite, these fields would be left blank.</p>
{/if}

{if $smarty.server.QUERY_STRING ne ""}
{assign var=q value=?}
{/if}

<form action="" name="loginForm" method="post" action="{$smarty.server.PHP_SELF}{$q}{$smarty.server.QUERY_STRING}" onsubmit="javascript: return validate();">
<table border=0 cellpadding=2 cellspacing=1 class=normal>
<tr><td colspan=2 class=subtitle>Please Login</td></tr>
<tr><td colspan=2><img src="{$docroot}images/spacer.gif" width=1 height=5></td></tr>
<tr><td colspan=2>You have requested access to a restricted area of our website. Please authenticate yourself to continue.</td></tr>
<tr><td colspan=2><img src="{$docroot}images/spacer.gif" width=1 height=5></td></tr>
<tr><td width=10%>Username: </td><td><input type=text size=20 name=username value="{if $smarty.const.DEMO_MODE}admin{else}{$smarty.post.username}{/if}"></td></tr>
<tr><td>Password: </td><td><input type=password size=20 name=password value="{if $smarty.const.DEMO_MODE}pass{else}{$smarty.post.password}{/if}"></td></tr>

{*
<tr><td>&nbsp;</td><td><input type=checkbox name=remember value=1 {if $smarty.post.remember eq 1}checked{/if}>Remember Me</td></tr>
*}

<tr><td>&nbsp;</td><td><input type=submit name=login value=Login></td></tr>
</table>
<input type=hidden name=es_login value=1>
</form>

<script type="text/javascript">
// autofocus to the first field of the login form
document.loginForm.username.focus();
</script>