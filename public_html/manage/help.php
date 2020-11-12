<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( '../init.php' );

switch( $_GET['type'] ) {

	case 'polls':

		$helpTitle = 'Polls';

		$content = 'A Poll is just a popup form, whose results are tabulated and saved to the database for statistical purposes. You may specify a particular target group or user for your poll, or you allow the poll to be seen by all visitors to your website. For example, if you have an "employees" group, and you want to poll your employees regarding some particular company issue, you would specify this group as the target audience. Thus, the poll would only appear when a member of this group logs in.

		For non-group and non-user specific polls, the system "remembers" who has completed the poll by looking at the user\'s IP address, which is unique for all users. For group- and user-specific polls, the user\'s unique login ID is used instead (not the login name, however, since this may not be unique).';

		break;

	case 'user_status':

		$helpTitle = 'User Status';

		$content = 'In some cases, companies and organizations may use the "Users and Groups" system for employees. You may track the status of your employees using this this option. In most cases, "Active", "Pending", "Suspended", and "Terminated" should be sufficient, although additional options may be added by directly editing the PHP code for this system.

		Please note that making a user "Suspended" or "Terminated" does NOT automatically invalidate the user\'s login or website, if one has been created. You must manually expire the user\'s login in such cases, by setting an expiration date and checking the "Expires?" checkbox.';

		break;

	case 'list_key':

		$helpTitle = 'List Keys';

		$content = 'A list key is a short, unique identifier for the list, which can be used in the Smarty .tpl files, if you are creating a custom module, for example. The syntax of the {list} command in Smarty is:

		{list key=$key}

		The list key should contain only letters and numbers ("alphanumeric" characters), although the underscore symbol _ is also allowed.';

		break;

	case 'lists':

		$helpTitle = 'Lists';

		$content = 'A list is simply a group of data which can be used throughout your website as part of one or more &lt;select&gt; combo boxes. For example, if you have created a form using the Forms tool, you may use a pre-existing list for the &lt;select&gt; list option.

		Or, if you have one or more modules installed, like the Real Estate system, you can edit the various lists that come with these modules using the Lists tool. Also, if you are creating a new module, you can use the special {list} keyword in your Smarty template file to automatically generate a &lt;select&gt; list from any list that you have saved in the Lists content management tool.';

		break;

	case 'logout_page':

		$helpTitle = 'Logout Page Overriding';

		$content = 'By default, the logout page is set to "logout.php". By setting an existing page as the logout page, you will override this default setting. Then, clicking on the new page (for example, through a menu that you have created), the user will logout of the website.';

		break;

	case 'login_form':

		$helpTitle = 'Login Page Overriding';

		$content = 'By default, the login page is set to "login.php", but you may override this setting with any form that you create using the Forms content management tools. The following special properties apply to forms that have been specified as the login form:

		1) There must be at least two fields in your form. The first field must be used as the "username" field, and the second field must be used as the "password" field. If you do not have at least two fields, or if these two fields are not text or password-type fields, then the form will NOT work properly as a login form.

		2) The form submission variables are not used upon submission. In other words, when a user clicks on "Submit" to submit a form that has been specified as the login form, there are no e-mails sent to anyone. Instead, the user is simply redirected to the content management tools index page. Thus, the "To", "CC", "Subject" and "Redirect To" settings are ignored.

		3) If you no un-check the option to use this form as the login form, then the "login.php" file will be used instead (i.e., you will always have *some* login form, even if it is not user-defined with the content management tools).';

		break;

	case 'default_page':

		$helpTitle = 'Default Pages and Forms';

		$content = 'You may specify any page or form that you create as the "default page" for your system. This is the page or form that users will be presented with when they access http://www.your_website.com/.

		You should be aware that if you currently have some other page or form specified as the default page or form, then checking this option will cause *this* page or form to be the default, thereby overriding any defaults that have previously been set for other pages or forms. In other words, your website can only have one default.

		If you are using the system in a multi-user environment, then each user\'s website may have its own default page or form.';

		break;

	case 'layer_restrict':

		$helpTitle = 'Restricting Layers to Forms & Pages';

		$content = '<b>Pages & Forms</b>

		You may specify which pages and forms a particular layer appears on. This allows a greater degree of customization of your website, and can provide a fairly simple way to skin your pages, without using the Skins tool. To achieve more advanced page customizations, you should skin your website.

		<b>Content Management Pages</b>

		By default, if a layer is displayed on "All Pages & Forms", then it also is displayed on the Content Management Tools pages. This may not be desirable if you have many layers, or if you have a layer which overlaps the body content area of your page. To resolve this, you can choose to show the layer on all pages, except the Content Management Tools.';

		break;

	case 'skin_permissions':

		$helpTitle = 'Skin Permissions';

		$content = 'A user\'s access capabilities in the Skins tool depend on access to other tools. For example, if the user has access to the Skins tool, but not to the Site Settings and/or Styles tools, then the number of available options for skinning will be restricted to only those options which can be edited by the user.

		If access is not granted to either of the settings or styles tools, then the user will be able to make the skin the default for his or her own website, but the user will not be able to edit, rename, or delete the skin. In addition, the user will not be able to add new skins.

		Confused? Here\'s a summary:

		1) If the user has access to the Skins tool and the Styles/Settings tools:
		 - the user can add skins
		 - the user can edit skins
		 - the user can delete skins
		 - the user can share skins

		2) If the user has access to the Skins tool and the Styles tool, but not the Settings tool:
		 - the user can add skins
		 - the user can edit skins
		 - the user can delete skins
		 - the user can share skins
		 - the user may only use the existing, pre-defined site settings with his or her skins

		3) If the user has access to the Skins tool and the Site Settings tool, but not the Styles tool:
		- the user can add skins
		- the user can edit skins
		- the user can delete skins
		- the user can share skins
		- the user may only use the exisitng, pre-defined styles with his or her skins

		4) If the user has access to the Skins tool but to neither the Settings nor the Styles tool:
		- the user may NOT add new skins
		- the user may NOT edit existing skins
		- the user may NOT delete skins
		- the user may NOT share skins
		- the user may only load existing, pre-defined skins
		- only the skins that have been shared with this user\'s group will be accessible
		- if no skins have been shared with this user, then the Skins tool will be useless to this user
		- the user will not be able to load the skin from within the Skins/Settings tools (only from within the Skins tool)';

		break;

	case 'load_from_skin':

		$helpTitle = 'Loading Settings and Styles from Skins';

		$content = 'When you load site settings, or styles, from a saved skin, you effectively make the skin\'s settings the default settings for the entire site (so be careful before you do it!).

		The "site settings" and the "styles" for the skin are loaded separately. Thus, to apply all of a skin\'s settings and styles to a particular site, you must load the site settings from the skin, then go to the styles tool, and load the styles from the skin. Or, if you want to do both of these steps at the same time, you can go to the skins tool, and simply load the desired skin.';

		break;

	case 'about_skins':

		$helpTitle = 'All About Skins';

		$content = 'A Skin is simply a set of saved settings and styles, which can you re-apply later, or share with other users. By saving your settings as a skin, you can quickly switch your entire website\'s look and feel.

		<b>Creating your first Skin</b>

		Skin creation may be summarized in the following steps:

		1) Using the site settings and styles tools, change your website settings and styles to a specific "look".

		2) Open the skins tool, and create a new skin by specifying the skin name and description. Make sure that the styles and settings that you want to include with the skin are checked.

		3) All done! Now, you can associate specific pages and forms with this skin, and share the skin with other users.

		<b>My Default Skin</b>

		You may specify the default skin for your website in the "Site Settings" Tool, by loading any existing saved skin. Loading the skin settings automatically makes those settings the default settings.

		Since a skin comprises both "Site Settings" and "Styles", to fully apply a skin as the default skin for a website, you must apply it in both the Settings and Styles content-management tools.

		<b>Sharing Skins with Other Users</b>

		If you have created additional users, and given your users their own websites, then you may specify that those users only have access to particular skins, which you\'ve defined.

		For example, suppose that you are a company with 50 employees, and each employee has his or her own website, created using the Users and Groups tool. Now, suppose that you want to enforce specific styles and settings for the employee websites. You can create a few "allowed" skins and give access to these skins to your employees.

		By default, if your employees have access to the skins tool but not the settings or styles tools, then the user will only be able to choose among existing skins, but not create, edit, or remove skins (since they need to have access to the settings and styles tools to do that!).

		<b>Editing and Removing Skins</b>

		If you remove the default skin, then the system uses the default system skin (which you created before saving the skin) as the default skin. Thus, even if you remove the default skin, you will still be able to correctly view your site. In addition, if you remove a skin which has been associated with a particular page or form, then the default settings will be applied to the page or form.';

		break;

	case 'skins':

		$helpTitle = 'Page- and Form-Specific Skins';

		$content = 'If you have one or more skins defined for your website, you may assign a skin to an individual page or form. Thus, the page or form in question can have a look and feel that is distinct from the rest of your website. For example, you may wish to have a different set of color bars on each page, or you may wish to have a different footer on each page.

		Skins can be added and removed using the "Skins" content-management tool, if you have access to this tool (if your website was created by an administrator, then you may or may not have access to the skin manager). When creating skins, you may specify individual settings to associate with a skin. The page or form with which you associate a skin will only assume those settings that have been set for the skin.';

		break;

	case 'skin_menu':

		$helpTitle = 'Getting menu settings for a skin';

		$content = 'Your website is allowed infinitely-many menus, and each menu may have a different look and feel to it. You may use the display settings from any particular menu by specifying the menu settings using this combo list.

		Remember: whatever menu settings you choose to save with this skin will be applied to ALL menus when applied. Thus, if you have 4 menus, and load a skin with a particular set of menu settings, those menu settings will be applied to all 4 menus! Thus, you should be careful when including menu settings with a skin.';

		break;

	case 'skin_name':

		$helpTitle = 'Skin Names';

		$content = 'The skin name is only to help you identify the skin. If you share the skin with other users, then those users will also see this skin name. You should choose a skin name which is fairly descriptive, so that you do not have to load the skin to recall what it looks like.

		You may choose to apply a specific skin to a single page or form, thus giving each part of your website a unique look. In the page and menu editing tools, the skin names are displayed in the ';

		break;

	case 'share_skin':

		$helpTitle = 'Sharing a Skin';

		$content = 'If you\'ve created an interesting looking skin, you can share it with other users. If the user has his or her own site (created with the Users & Groups tool), then you may even allow the user to use your skin on a different site altogether.';

		break;

	case 'wysiwyg':

		$helpTitle = 'The WYSIWYG Editor';

		$content = 'The WYSIWYG Editor allows you to input text using a Word Processor-like tool, so that you can easily use complex formatting like tables, indentation, and colors in your text.

		WYSIWYG = "What You See Is What You Get"

		The WYSIWYG Editor is only available to users of Microsoft Internet Explorer >= 5.5 or Mozilla >= 0.7. When enabled, you will have two small icons available to you in the page and layer editing tools:

		<img src="' . DOC_ROOT . 'images/editor.gif"> <img src="' . DOC_ROOT . 'images/multiedit.gif">

		When the user clicks on either of these, the WYSIWYG editor will appear if the browser type is compatible:

		<img src="' . DOC_ROOT . 'images/help/wysiwyg.gif">';

		break;

	case 'site_settings':

		$helpTitle = 'General Website Settings';

		$content = 'With the Site Settings Tool, you can change "global" properties of your website, which affect all pages and forms, and even the content management tools that you are using now!.

		This diagram shows some of the basic website settings that you can control with this tool.

		<div align=center><img src="' . DOC_ROOT . 'images/help/site_settings.gif" border=0></div>';

		break;

	case 'section_format':

		$helpTitle = 'Section Formats';

		$content = 'Have you ever been in a situation where you wanted to put a bulleted list in HTML, but the indentation never quite works the way that you want it to? The Format option in EasySite is designed to solve that problem. You can choose from several common list formats, including bullets & numbers.

		Here\'s how it works: You input normal text into the Content section, and separate the lines with a single carriage return. Or, separate the lines with 2 carriage returns if you want to have more spaced between the bullet/number lists.

		For example:

		This is my first bullet point.
		This is my second bullet point.
		This is my third bullet point.

		When the page is viewed, these lines will be automatically converted to a propertly-indented bullet list, with proper word-wrapping applied.';

		break;

	case 'user_websites':

		$helpTitle = 'User Websites';

		$content = 'With the EasySite system, every user that you create can have their own website, and even their own content-management tools to manage and control it. You don\'t even have to re-install the system! All of the database tables and PHP files which you\'ve already uploaded for EasySite are used for the user-specific websites.

		<b>How does it work?</b>

		In the database, which holds all of the information about your website - menus, pages, forms, layers, etc. - there is a "site key" property. Every user-specific website has a different site key, so EasySite "knows" which website information to output to the web browser, based on the site key. In tech-speak this is known as a "filter".

		<b>How is the site accessed?</b>

		Normally, to access your default website (the one that you\'re creating now), you simply go to www.your_domain.com or www.your_domain.com/some_folder, depending on where you installed the EasySite files to. For user-specific websites, you go to

		www.your_domain.com/?site={site}

		<b>or</b>

		www.your_domain.com/some_folder/?site={site}.

		<b>How does the user edit his site?</b>

		The same way that you do! Through the content-management tools.

		Suppose that you have created an employee called "John Doe" and assigned him to the site named "john", and my domain is "acme_widgets.com" then to access John\'s website they would use

		www.acme_widgets.com/?site=john

		After accessing this URL, a cookie is set by the web browser, which tells the server that we are now in a user-specific website. John would then be able to go to:

		www.acme_widgets.com/manage/

		... and he would login using the login ID & password that was created for him with the "Users and Groups" tool.

		John would now be able to edit the pages, menus, layers, and files for his site, but not for any other user\'s site. John would NOT, however, have access to the "Users and Groups" tool, since this would allow him the ability to create more websites for himself and others. ONLY the primary administrator (YOU) can create and remove user websites.

		<b>Who would use this?</b>

		This is a great resource if, for example, you are a business whereby each employee is given his or her own website. Or, if you are a teacher and want to allow every student his or her own website. After accessing the user-specific site, the user can then go to /manage/ and login to access his or her content-management tools.

		Every user is the administrator of his or her own website via the EasySite content-management tools.

		<b>What if I want to associate a domain with the user website?</b>

		It is very common to have multiple domains (.com, .org, .net, etc.) \'parked\' on a single server. For example, CNN.com and CNNMoney.com are located on the same physical server - in essence, the domains are \'parked\' on the same server, and based on the URL that is detected, the server outputs either CNNMoney information, or CNN information.

		You can do this with EasySite by using simple redirects! For example, suppose that you have a primary website, and 5 employees, each of whom has his or her own EasySite-based website, each with its own \'site key\'. Let\'s say that we have a user named Jane with site key = \'jane\', and another user named Bill with site key = \'bill\'.

		Thus, the two user websites are:
		...?site=jane
		...?site=bill

		The default website would be:
		...?site=default
		(or, if you do not use ?site=default at all, the system will assume \'default\')

		In your index.php file, you can simply add a small amount of PHP code, like this:

<pre>$url = getenv(HTTP_HOST);
if ( $url == "http://www.bills_website.com" ) {
  header( "Location: index.php?site=bill" );
}
else if ( $url == "http://www.janes_website.com" ) {
  header( "Location: index.php?site=jane" );
}</pre>';

		break;

	case 'date_codes':

		$helpTitle = 'Date / Time Codes';

		$t->assign( 'noBreaks', true );

		$content = '<table border=0 cellpadding=2 width=100% cellspacing=0 class=normal>
    <thead>
      <tr>
        <td><b>Code</b></td>
        <td><b>Description</b></td>
      </tr>
    </thead>
    <tr>
      <td> <b>a</b></td>
      <td>Lowercase
          Ante meridiem and Post meridiem</td>
    </tr>
    <tr>
      <td> <b>A</b></td>
      <td>Uppercase
          Ante meridiem and Post meridiem</td>
    </tr>
    <tr>
      <td> <b>B</b></td>
      <td>Swatch
          Internet time</td>
    </tr>
    <tr>
      <td> <b>c</b></td>
      <td>ISO
          8601 date (added in PHP 5)</td>
    </tr>
    <tr>
      <td> <b>d</b></td>
      <td>Day
          of the month, 2 digits with leading zeros</td>
    </tr>
    <tr>
      <td> <b>D</b></td>
      <td>A
          textual representation of a day, three letters</td>
    </tr>
    <tr>
      <td> <b>F</b></td>
      <td>A
          full textual representation of a month, such as January or March</td>
    </tr>
    <tr>
      <td> <b>g</b></td>
      <td>12-hour
          format of an hour without leading zeros</td>
    </tr>
    <tr>
      <td> <b>G</b></td>
      <td>24-hour
          format of an hour without leading zeros</td>
    </tr>
    <tr>
      <td> <b>h</b></td>
      <td>12-hour
          format of an hour with leading zeros</td>
    </tr>
    <tr>
      <td> <b>H</b></td>
      <td>24-hour
          format of an hour with leading zeros</td>
    </tr>
    <tr>
      <td> <b>i</b></td>
      <td>Minutes
          with leading zeros</td>
    </tr>
    <tr>
      <td> <b>I</b> (capital i)</td>
      <td>Whether
          or not the date is in daylights savings time</td>
    </tr>
    <tr>
      <td> <b>j</b></td>
      <td>Day
          of the month without leading zeros</td>
    </tr>
    <tr>
      <td nowrap> <b>l</b> (lowercase L)</td>
      <td>A
          full textual representation of the day of the week</td>
    </tr>
    <tr>
      <td> <b>L</b></td>
      <td>Whether
          it\'s a leap year</td>
    </tr>
    <tr>
      <td> <b>m</b></td>
      <td>Numeric
          representation of a month, with leading zeros</td>
    </tr>
    <tr>
      <td> <b>M</b></td>
      <td>A
          short textual representation of a month, three letters</td>
    </tr>
    <tr>
      <td> <b>n</b></td>
      <td>Numeric
          representation of a month, without leading zeros</td>
    </tr>
    <tr>
      <td> <b>O</b></td>
      <td>Difference
          to Greenwich time (GMT) in hours</td>
    </tr>
    <tr>
      <td> <b>s</b></td>
      <td>Seconds,
          with leading zeros</td>
    </tr>
    <tr>
      <td> <b>S</b></td>
      <td>English
          ordinal suffix for the day of the month, 2 characters</td>
    </tr>
    <tr>
      <td> <b>t</b></td>
      <td>Number
          of days in the given month</td>
    </tr>
    <tr>
      <td> <b>T</b></td>
      <td>Timezone
          setting of this machine</td>
    </tr>
    <tr>
      <td> <b>U</b></td>
      <td>Seconds
          since the Unix Epoch (January 1 1970 00:00:00 GMT)</td>
    </tr>
    <tr>
      <td> <b>w</b></td>
      <td>Numeric
          representation of the day of the week</td>
    </tr>
    <tr>
      <td> <b>W</b></td>
      <td>ISO-8601
          week number of year, weeks starting on Monday (added in PHP 4.1.0)</td>
    </tr>
    <tr>
      <td> <b>Y</b></td>
      <td>A
          full numeric representation of a year, 4 digits</td>
    </tr>
    <tr>
      <td> <b>y</b></td>
      <td>A
          two digit representation of a year</td>
    </tr>
    <tr>
      <td> <b>z</b></td>
      <td>The
          day of the year (starting from 0)</td>
    </tr>
    <tr>
      <td> <b>Z</b></td>
      <td>Timezone
          offset in seconds. The offset for timezones west of UTC is always negative,
          and for those east of UTC is always positive.</td>
    </tr>
  </table>
';

		break;

	case 'menu_link':

		$helpTitle = 'Linking pages & forms to menus';

		$content = 'You may link forms & pages to menu items, so that clicking on the menu item opens the desired form or page within your website template.

		If you do not link the page or form to a menu item, you can still use the URL for the page or form to link directly, instead of from the menu.';

		break;

	case 'bg_image':

		$helpTitle = 'Corner Background Image';

		$content = '<img align=right src="' . DOC_ROOT . 'images/help/image_key.gif"> The primary background image is usually a company or organization logo. It can be any dimensions (pixel height and width), but must be under 64 KB when uploaded. It is strongly recommended that you make this as small (in KB) as possible to reduce page-loading delays, for example, images under 20 KB are ideal.

		By default, the Primary Background Image is automatically fixed in the upper left corner of the screen. This is the most common location for such background images in most websites. You can adjust the positioning to your liking, however, by adjusting the HTML table in default.tpl (within the templates folder on your server).';

		break;

	case 'hz_image':

		$helpTitle = 'Horizontal Color Bar';

		$content = '<img align=right src="' . DOC_ROOT . 'images/help/image_key.gif"> The horizontal color bar is a horizontally-repeating (x-repeat) image. Typically, this is a solid vertical bar which forms the top X-pixels of your site design.

		The maximum size of the color bar is 64 KB, although it is recommended that you keep this as small in KB as possible, to reduce page-loading delays. The color bars can be very thin, since they repeat along the axis. Thus, you could make the horizontal color bar 1 pixel wide and X pixels high... most such images are under 2 KB in size.';

		break;

	case 'vt_image':

		$helpTitle = 'Vertical Color Bar';

		$content = '<img align=right src="' . DOC_ROOT . 'images/help/image_key.gif"> The vertical color bar is a vertically-repeating (y-repeat) image. Typically, this is a solid vertical bar which forms the left-most Y-pixels of your site design.

		The maximum size of the color bar is 64 KB, although it is recommended that you keep this as small in KB as possible, to reduce page-loading delays. The color bars can be very thin, since they repeat along the axis. Thus, you could make the vertical color bar 1 pixel high and X pixels wide... most such images are under 2 KB in size.';

		break;

	case 'menu_manage':

		$helpTitle = 'Menu Content & Structure';

		$content = 'To edit the content and structure of your website\'s menus, you may use the the buttons next to each field.

		Here\'s a quick summary of what these buttons mean:

		<table border=0 cellpadding=3 cellspacing=0 class=normal><tr><td valign=top><b>X</b></td><td valign=top>Delete this menu item and all its decendents.</td></tr><tr><td valign=top><b>^</b></td><td valign=top>Bump this menu item & all its decendents up.</td></tr><tr><td valign=top><b>></b></td><td valign=top>Move this menu item & all its decendents one level deeper.</td></tr><tr><td valign=top><b><</b></td><td valign=top>Move this menu item & all its decendents one level higher.</td></tr><tr><td valign=top><b>*</b></td><td valign=top>Create a new menu item immediately below the current menu item.</td></tr></table>
		To change the name of any menu item, simply edit the appropriate textfield, then click on Update All Items. In fact, clicking any of the buttons updates the menu text for all textfields.

		<b>Using External, Embedded, and Framed URLs</b>

		If you wish to link a menu item to something which is not an integrated part of the website, then you can use one of these URL options. For example, suppose that you wish to link a menu item directly to http://www.yahoo.com, or perhaps to a specialized module that you have installed on your server.

		In the case of external URLs, you can simply create a direct, "External" link to the URL by inputting the full HTTP path in the popup window that appears when this option is selected.

		For modules that you have installed on your server, you can also use the External link option, or if you wish to directly embed the module within your website styles and settings (i.e, you want to apply your site design to the external module), you have two options:

		Option 1: Use a frame (specifically, an "i-frame"), by specifying a "Framed URL" in the "Link To" combo menu.

		Option 2: Attempt to embed the external URL content within your website design. This option uses a specialized PHP program called Snoopy to fetch the external URL data, and embed it within your website design. This option does not work for all websites that you may wish to embed (only the very simplest). Also, embedding with Snoopy slows down navigation of the embedded website substantially, because Snoopy must fetch the site, parse it and do various link replacements, and then finally embed the external URL content within your website design (a lot of work!).

		The overall best option is simply to link to an external URL rather than attempting to embed either with a frame or with Snoopy.';

		break;

	case 'form_title':

		$helpTitle = 'Form Titles';

		$content = 'The form title appears at the top of the form in large print. For example, \'Feedback Form\' or \'Contact Us\' would be examples of common form titles.

		It is recommended that the form title be kept to only a few words in length.';

		break;

	case 'form_redirect':

		$helpTitle = 'Form Redirects';

		$content = '<b>Redirect to a Thank-you Page</b>

		After submission of a form, the user can be redirected to another page, for example, a page thanking them for completing & submitting the form. You may choose any existing page (which you created previously using the page content-management tool) to redirect your users to.

		<b>Multiple-page Forms</b>

		Alternatively, you can redirect to a form rather than a page. In this case, the values from the previous form are added to the next page\'s form as hidden variables, so that when the second page is submitted, all of the second page\'s values AND the values from the first form, are all submitted at once! In fact, you could go even further and create forms with dozens of pages, with each form\'s values carried over to the next form.

		In multi-page forms, only the To, Subject, and CC information of the LAST form is used for the form submission. In addition, the Submissions value is only incremented for the final form in a series. Thus, if you have a 3-part form, whereby form 1 redirects to form 2, form 2 redirects to form 3, and form 3 redirects to a thank-you page, only the Submission value of form 3 would be incremented. The View value would be incremented for all 3 form parts, however. The To, Subject, and CC values of the 3rd form part would be used to determine the submission behavior.';

		break;

	case 'layer_options':

		$helpTitle = 'Layer Options';

		$content = 'Layers are like floating pieces of text on your page... they are not embedded within any HTML structure, and they can be positioned almost anywhere on your page. These options control the position & minimum size of the layer. Here\'s what each of them mean:

		When you are editing the layer positioning, it\'s possible to put a layer directly over form input. In such cases, you may discover that you are no longer able to input data into the form... this is because the layer is blocking it. Also, if you cannot see a layer that you\'ve added, it\'s possible that another layer is occluding it. This situation can be corrected by specifying a higher value for the z-order of the layer that you cannot see. In other words, if you add a layer but cannot see it, there is probably a good reason!

		<b>Overriding Settings:</b> Let\'s suppose that you have 5 layers, all of which look the same and are positioned at the same coordinates, but which appear on different pages and forms. Instead of re-inputting the layer settings 5 times, you can simply specify one of the layers from which to draw the settings (x/y position, width/height, alignment and color). That way, if you want to adjust all 5 layers on the various pages, then you only need to adjust the settings of one layer!

		<b>Name:</b> This is ONLY used for reference purposes. The layer name does not appear anywhere but in the drop-down list for the "override settings" option, and also as the name="" and id="" attributes in the &lt;div&gt; tag which defines the layer... for example, if you need to embed some javascript to control the layer, you can use document.getElementById([layer name]).

		<b>Top Offset:</b> Number of pixels from the top of the screen that this layer should appear at.

		<b>Z-Order:</b> The stacking-order of the layer. If two overlapping layers are present, then the layer with a higher Z-Order will appear on top. The Z-Order should be an integer between 1-1000.

		<b>Width:</b> The default, minimum width of the layer, in pixels. This value can be either a single number, or a percentage. If a percentage, you can set the alignment to "center" to auto-center any layer content on the page.

		<b>Left:</b> Number of pixels from the left side of the screen that this layer should appear at.

		<b>Padding:</b> The amount of padding, in pixels, between the layer contents and the layer border.

		<b>Color:</b> The background color of this layer. You should use standard HTML color notation for this, in Hexidecimal values. For help in choosing a color, use the color picker.';

		break;

	case 'file':

		$helpTitle = 'File to Upload';
		$content = 'This is the actual file that you wish to upload to the server. All files are uploaded DIRECTLY to your database. Thus, there may be some size restrictions, depending on your server configurations.

		<b>IMPORTANT: </b> Most MySQL servers, for example, impose a limit of 1 MB on data that can be inserted into a database directly. If you need to upload more data than that, you should consult with your website host or administrator to increase the maximum size of database adds. In some cases, the limit is imposed by your web server, like Apache or IIS, or even by PHP. This is very server-specific, however, and is probably different for all server environments.

		With the current version of EasySite, you are allowed to upload a wide variety of files, including PDF documents, Word and Excel documents, and many more. For a complete list of the currently-supported types, please refer to the getObject.php file, which is included in your EasySite distribution. If you have some PHP knowledge, you may be able to add support for additional file types (mime-types).

		The local file path will be saved along with your upload, so that you can easily remember which file was used.';

		break;

	case 'download_name':

		$helpTitle = 'File Download Name';
		$content = 'When a user clicks on the link to download a file, the web browser typically assigns a fairly obscure name for it. This option allows you to define a more common name for the file when downloaded by the user.

		<b>IMPORTANT: </b> This option is not supported by all web browsers, so if you set the download name, it may not appear for all users. For best compatibility, you should avoid non-alphanumeric symbols and spaces in the download name.';

		break;

	case 'field_size':

		$helpTitle = 'Field Size';

		$content = 'The width of the field. For example, a common field width for standard, single-line text fields, is about 20 pixels (in which case, you would input <b>20</b> into this textfield).

		If the field type is a multi-line text field, then you may input two numbers, separated by a comma, as the field size. For example, <b>5,40</b>. In this case, the first number will be used as the number of rows, and the second number will be used as the number of columns for the text field.

		For multi-line text fields (also known as textareas), it is recommended that you use at least <b>3,40</b> for the field size. For single-line text fields, it is recommended that you use at least <b>10</b> for the field size.';

		break;

	case 'required_fields':

		$helpTitle = 'Form Options - Required Fields';

		$content = 'By checking this box, the user will not be permitted the submit a form without completing this field. The \'required\' option is only available for text fields, either single-line or multi-line, and password fields.';

		break;

	case 'form_options':

		$helpTitle = 'Form Options - List & Radio Buttons';

		$content = '<b>Select Lists</b>

		The select list values text field should contain a comma-delimited list of values which will go into the select list. A select list is sometimes called a drop-down list or drop-menu. For example, if you input "first item,second item,third item" into this field, then you will have a drop-down list with three, mutually-exclusive choices in it.

		<b>Radio Button Groups</b>

		NOTE: Some users have reported that they cannot see the radio button group popup window. Please note that if you have a popup blocker in place, you may need to disable it temporarily to view this window.

		The radio button group window is used to input radio buttons for a particular button group. You must save your form (with a radio button field) before adding options to your radio group.';

		break;

	case 'img_thumb':
		$helpTitle = 'Embedded Image';

		$content = 'The embedded image is, quite literally, embedded within the content block, along with the content block text. It is very common to make this image a thumbnail image.

		If you need to allow viewers to see a larger version of the image, it is recommended that you use the Popup/Large Image option.';

		break;

	case 'img_large':

		$helpTitle = 'Popup/Large Image';
		$content = 'The idea of the popup image option is so allow an embedded/thumbnail image to link to a popup window which would contain a larger version of the image. The larger version of the image is contained in its own popup window, which is automatically centered vertically and horizontally on the user\'s screen.';
		break;

	case 'img_anchor':

		$helpTitle = 'Image Anchor Position';
		$content = 'Anchoring an image means that it will be fixed at the left or right side of this content block, causing the surrounding text to wrap naturally around it.

		If the image achor position is not specified, then the image will be subject to the default layout for the image, as determined by the client web browser.';

		break;

	case 'img_link':

		$helpTitle = 'Linking an image to a URL or Pop-up';
		$content = 'Linking an image to a URL means that if the image is clicked, a new page will open with the designated URL. By default, the URL will be opened in a blank window, not within the website template.

		<b>IMPORTANT: </b> If an embedded image is specified, but a popup/secondary image is NOT specified, then the embedded image will link to the external URL. However, if BOTH embedded and popup images are specified, then the popup image will link to the external URL, and the embedded image will link to the popup image.

		In other words, what the embedded image links to depends on:
		- the presence or absense of a popup image
		- the presence or absense of an image link

		<b>Creating small popup windows</b>

		Instead of linking to a full-sized window, you have the option of opening a smaller, popup window (a "Javascript Popup" as they are sometimes known). To do this, you can use the following code in the "Image Link" textbox:

		javascript:launchCentered(\'{HTTP URL}\',\'{width}\',\'{height}\',\'{options}\');

		... where width and height are integers which indicate the desired width and height of the popup window, and the options are one of several possible display options for the popup window, like "scrollbars" to allow scrollbars in the popup window, or "resizable" to allow the popup window to be resized (options are separated by commas).

		For example, you might use the following image link:

		<img src=' . DOC_ROOT . 'images/help/js_link.gif>

		This would open the CNN homepage in a popup windows 500 pixels wide by 400 pixels high, which would have scrollbars enabled in it. Please note that you MUST use only single quotes (\') within the Image Link code. Using double-quotes may produce an error on your page!

		If you use the launchCentered javascript command, then your popup window will be automatically centered on the user\'s web browser. If you do not want it to be centered, then you may use the "launch" javascript command instead, but leave out the width and height parameters, for example:

		javascript:launch(\'{HTTP URL}\',\'{options}\');

		You may use additional Javascript commands, too, if you have some knowledge of programming. Please see the "shared.js" file to add more functions. Please note that to access any function from the image link code, you MUST use the "javascript:" prefix. Thus, using simply:

		myFunction(...)

		... will NOT work! You must use:

		javascript:myFunction(...)

		... to correctly access the function from shared.js (please note that this is for advanced users only, and is not generally recommended).';

		break;

	case 'groups':

		$helpTitle = 'User Groups';
		$content = 'A \'Group\' defines a category or class of users who share similar access permissions. For example, a company might have a Marketing group and a Sales group, and users belonging to each of these respective groups may have access to different restricted areas of the website.

		By defining permissions in a group-context, you can upgrade the access permissions of your users very easily. For example, if you want all members of your Sales group to have access to a section of your website that they previously didn\'t have access to, you can do that very easily by changing the permissions of the entire group. Then, all users in the group instantly have their permissions upgraded as well.

		After installation, EasySite adds one group, the Admin group, which has access to the content-management tools. You should be very careful about who you add to the Admin group, since these users will have full access to the content-management tools, and will be able to add/edit/delete other users.';

		break;

	case 'permissions':

		$helpTitle = 'Group Permissions';

		$content = 'The permissions for a group determine which parts of the website logged-in members will have access to. ANY item that you select from this list of checkboxes will instantly become a restricted area, which means that users must be logged-in before they can access the item.

		<b>Skins</b>

		If a user has access to the settings and styles tools, then they will automatically have access to these parts of the skin tool, as well as add/edit/delete functionality in the skin tool.

		However, if the user does not have access to either the settings or styles tool, then they will only be able to load the skins which have been shared with them. They will not be able to add their own skins, or edit any existing skins. This is useful if you wish to define a custom set of skins for your user community, but you wish to restrict access to the skins that you have defined (for example, to enforce a specific design for the user websites).';
/*
// section-restrictions have been temporarily disabled in this version of EasySite

For form fields & sections, and also page sections, the access is restricted on a sectional-basis rather than a page-basis. In such cases, the user will still have access to the overall page or form (unless, of course, the page is specified as a restricted area), but they will NOT be able to see the restricted section until they are logged in. The restricted section will simply be invisible to them.
*/

		break;

	case 'expires':

		$helpTitle = 'Login Expiration Enabled?';
		$content = 'If this option is checked, then the expiration date will be applied to this user\'s login. This means that after the expiration date has passed, the user will not be able to login. The user profile will still remain in the database, in case you would like to re-enable it by setting a later expiration date, or unchecking the expiration option.';

		break;

	case 'exp_date':

		$helpTitle = 'Expiration Date for Login';
		$content = 'This is the date that the login is set to expire. After this date, this user will be unable to login to your site if the expiration date is enabled. After expiration, the user information will remain in the system, in case you (the administrator) would like to set a later expiration date, to allow continued access for this user.';

		break;

	case 'submission_report':

		$helpTitle = 'Generation Submission Reports';
		$content = 'You can specify this option to store form submissions into the database and the view them.';

		break;
		
    case 'report_name':
    
		$helpTitle = 'Report Name';
        $content = 'The report name is only used to identify the report in the report system, the menu manager, and the form manager. In addition, this name is used as the HTML title for your report, when run.

        It is not actually used in the body of the report.';
        
        break;

    case 'report_data_source':
    
		$helpTitle = 'Report Data Source';
        $content = 'Report data is based on the submission of Forms. For example, when a user submits a feedback form, the feedback is immediately available to any reports based on the feedback form.

        In the Form Manager, there is a "generate submission reports" option which MUST be checked for the form data to be saved internally.

        Please be aware that if a form is removed in the Form Manager, the associated report data is also removed! You may, however, rename and re-organize a form without affecting the submission data. Field renaming, however, also affects any corresponding reports, since the report fields correspond to the form field labels.';
        
        break;
        
    case 'report_sorting_grouping':
    
		$helpTitle = 'Sorting and Grouping';
        $content = 'Sorting and Grouping your report data makes it more readable and understandable, and is highly recommended, especially for large sets of submission data.

        When a field is "grouped" it means that all data which has the same value for the specified field appears together in the report - you can think of this as a "block" of like-data. For example, you might create a report of "All customers in Europe, grouped by country." Such a report would show the customers in blocks, each block corresponding to a different European country. Using this method, it would be very simple to identify all of your customers in France, or all customers in Italy, and so on.

        Changing your form labels could affect your report groupings, since the groups are identified according to the field label. Thus, you should be mindful that if the form changes, you may need to update the reports which draw data from the form.

        It is also possible to build multi-level reports, with many grouping levels. This is generally not recommended unless absolutely needed, since it slows down the report processing. For example, you could create a report of "All customers in Europe, grouped by country, and then sub-grouped by City." Thus, for each country, there would be additional "sub-groups" for the cities in each country.

        If the "Group by this field" option is not checked, then the form is sorted by the specified field, but no grouping levels are added.';
        
        break;
        
        
    case 'report_sort_type':
    
		$helpTitle = 'Sorting and Grouping';
        $content = 'Ascending Alphabetic = A to Z

        Decending Alphabetic = Z to A

        Ascending Numeric = 0 to 9

        Decending Numeric = 9 to 0';
        
        break;
        
    case 'report_indented_levels':
    
		$helpTitle = 'Indented Levels';
        $content = 'This options is only applicable if one or more groups have been added to your report. The "Indent" value determines the amount, in pixels, of left-hand indentation of the group.

        This makes your report more readable because each group can have a clear indentation associated with it.';
        
        break;
        
    case 'report_calculations':
    
		$helpTitle = 'Report Calculations';
        $content = 'For any level of grouping that is added to your report, you may specify summary information for the group, too.

        For example, suppose that you have a report of all customers in Europe, grouped by country. You may wish to know the count of these customers. For that, you can use the {Count} field in your layout.

        Or, suppose that you have a report of employee incomes, grouped by department. For each department, you can quickly determine the sum of these incomes by using the {Sum} field in your layout, or the average departmental income, using {Average}';
        
        break;
        
    case 'report_filters':
    
		$helpTitle = 'Report Filters';
        $content = 'If you have a large set of submission data for a form, you may choose to only view a subset of this data in your report. 

        For example, suppose that you have a report of all employees in your company. You may choose to only view employees belonging to a particular department by using a filter like this:

        Department = Finance (assuming that a Department field exists in your form)

        Or, you may only wish to view employees who were hired after a certain date. In this case, you would need to use the MySQL date format, with the ">" operator, like this:

        Date Hired > 2002-01-06

        Additional filter conditions are added using the boolean "AND" operator. Thus, if you have both of these filter conditions present in your report:

        Department = Finance
        Date Hired > 2002-01-06

        ... then the report will show all employes in the Finance department who were hired after January 6, 2002.';
        
        break;
        
    case 'report_clickable_fields':
    
		$helpTitle = 'Clickable Fields';
        $content = 'You may add fields to your report quickly by simply clicking on them in the right-side column. Or, you can type the field values (with { and } symbols) into the text area.

        These fields draw directly from the form that the report is based on, so changing a field label in the Form Manager directly impacts the report layout. In other words, be careful when you change the form that a report is based upon!';
        
        break;
        
    case 'report_header':
    
		$helpTitle = 'Report Header';
        $content = 'This is a block of HTML which should appear at the top of the entire report.';
        
        break;
        
    case 'report_footer':
    
		$helpTitle = 'Report Footer';
        $content = 'This is a block of HTML which should appear at the bottom of the entire report.';
        
        break;
        
    case 'report_pagination':
    
		$helpTitle = 'Report Pagination';
        $content = 'If you have a large amount of report data, it makes sense to split it up into multiple pages. The pagination option will do this automatically.';
        
        break;
        
    case 'report_rows_per_page':
    
		$helpTitle = 'Rows per Page';
        $content = 'This value indicates the number of records (in the report body) which should appear on each page of the report. The pagination option must be activated to see this option.';
        
        break;
        
    case 'report_navbar_links':
    
		$helpTitle = 'Navigation Bar Links';
        $content = 'This option sets the number of links which appear in the navigation bar. For example, if the value "5" is set, then your navigation bar will appear as:

        << 1 2 3 4 5 >>

        The << and >> links take you to next or previous pages in your report.';
        
        break;
        
    case 'page_keys':
    
		$helpTitle = 'Page & Form Keys';
        $content = 'Setting a page/form key allows you to reference the page more easily in other parts of your website. For example, if you set the home page\'s "Page Key" value to "homepage", you may use:

        http://www.yourwebsite.com/index.php?page_id=homepage

        The system will look up the page id value based on the page key. This is considered an alternative to the regular page url, which looks more like this:

        http://www.yourwebsite.com/index.php?page_id=1

        ... where "1" is the numeric page id. Using the numeric method is less desirable, since the page can be deleted and re-added, in which case the page id changes. The page key, however, can remain the same.

        All page keys which you set MUST be unique for this feature to work. The page key is entirely optional.';
        
        break;
        
    case 'new_line_behavior':
    
		$helpTitle = 'New Line Behavior';
        $content = 'This setting determines how new lines (and line breaks) should be handled. For example, you can specify that new lines are converted to the HTML &lt;BR&gt; tag, which means that you can use a more natural method of typing text, like this:

        My First Line
        My Second Line

        This would be converted to:

        My First Line &lt;br /&gt;
        My Second Line

        Important: If you wish to embed Javascript directly into a page section, then you must choose the "ignore new lines" option. Otherwise, &lt;br /&gt; tags will be inserted where new lines appear in the Javascript code, leading to Javascript errors.';
        
        break;
        
    case 'report_search_forms':
    
		$helpTitle = 'Report Search ';
        $content = 'You may use any form as a "search form" for any report, even if the report is not based on the report. Use the "report filter overrides" option to "match" the form fields to the report fields.';
        
        break;
        
    case 'full_textarea':
    
    	$helpTitle = 'Full Width for Textareas';
    	
    	$content = 'If this settings set to yes, then the full width of the body is used for textarea fields, and the label is placed above the textarea, instead of to the left of the textarea.
 
		For example, if "Full Width for Textareas" is set to "no", then the field will be displayed like this:
		 
		Label: [&nbsp;&nbsp;&nbsp;multi-line textarea&nbsp;&nbsp;&nbsp;]
		 
		If "Full Width for Textareas" is set to "yes", then the field will be displayed like this:
		 
		Label:
		[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mult-line textarea here&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]';
    	
    	break;
    	
    case 'share_resources':
    	
    	$helpTitle = 'Share Resources';
    	$content = 'If you have multiple user "sites" created with EasySite, you may share resources created by the parent site with sub-sites.

		For example, suppose that the main EasySite system (the "default" site) is used for a particular company called ACME Widgets. Suppose also that ACME Widgets has 5 departmental websites, ?site=finance, ?site=marketing, ?site=public_relations, and so on. It might be desirable to have a common set of forms, created by the default site administrator, to be shared with the sub-sites, so that the sub-sites do not have to re-create these resources on their own.

		When a shared resources is updated, these updates are reflected in all of the sub-sites as well. This top-down approach ensures that the resources can be updated quickly and easily from a single location.

		In addition to sharing the resource, you may also specify the particular permissions that the sub-site user has access to. For example, you may wish that they can use a the shared resource, but not edit the resource.';
    	
    	break;
    	
    case 'email_confirmation':
    
    	$helpTitle = 'E-Mail Confirmation';
    	
    	$content = 'When a form is submitted, you have the option to send a confirmation e-mail to the person who submitted the form. To do this, you must specify which of the form fields should be used for the user\'s e-mail. You should also specify the subject and content of the confirmation e-mail, using values from the form submission.
 
		For example, if the field title that contains the user\'s name is "Enter your name", then {Enter your name} should be referenced in the e-mail content or subject template below. The system will make the appropriate substitute before sending the confirmation e-mail.
		 
		Additional variables that can be specified include:
		 
		{form_title} - Refers to the form title
		{form_description} - Refers to the form description
		{site} - Refers to the website name (as defined in global settings)';
    	
    	break;
    	
    case 'auto_backups':
    
    	$helpTitle = 'Cronjob Auto-Backups';
    	
    	$content = 'You may configure auto-backup based on selective backup.';
    	
    	break;

    // continue with all other help file cases
}

include_once( '../init_bottom.php' );

$t->assign( 'title', 'Help with ' . $helpTitle );
$t->assign( 'helpTitle', $helpTitle );
$t->assign( 'content', $content );
$t->display( 'manage/help.tpl' );
?>
