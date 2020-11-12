<p class=subtitle>Special Operators</p>

<p class=normal><b>Counters & Last Update Info</b></p>

<p class=normal>Did you know that you can include special operators in EasySite? For example, &lt;!--numvisitors--&gt; will display the total number of visits to your website.

<p class=normal>Similarly, &lt;!--lastupdate--&gt; shows the last date that the content management tools were accessed (and thus, the last update date of your website, since updates occur via the content-management tools), and &lt;!--admin--&gt; provides the full document path to the Content Management tools (the value that would be assigned to the 'href' attribute of the anchor tag).</p>

<p class=normal>You can change the date format of the last update value in the "Edit Settings" section of the content management tools.</p>

<p class=normal>These codes should be directly inputted into the "Content" areas below, but NOT while using the special content editor. For example:</p>

<p class=normal>There have been &lt;!--numvisitors--&gt; to our website!</p>

<p class=normal><b>Login & Logout</b></p>

<p class=normal>If you have restricted pages of your site, the system will automatically prompt for the user's login information. Closing all instances of a user's web browser will log the user out of the system.</p>

<p class=normal>You can also put login and logout links anywhere on your website, too. This way the user can login before accessing a restricted area, and they can logout of the system, even while having some web browser instances open. To do this, simply link (for example, using the Menu Manager) to the following:</p>

<p class=normal>Login File: login.php</p>

<p class=normal>Logout File: logout.php</p>

<p class=normal>For example, to create a login link within a page section, you might use this:</p>

<p class=normal>"Click <a href=login.php>here</a> to Login"</p>

<p class=normal>or perhaps...</p>

<p class=normal>"Click <a href=logout.php>here</a> to Logout"</p>

<p class=normal>Alternately, in the menu tool, you can simply create a menu item which is a direct link to login.php or logout.php. In this case, you may need to input the full, unqualified URL path to login.php or logout.php (which, by default, are located in the 'pages' directory).</p.

<p class=normal>For example:</p>

<p class=normal>http://www.your_domain.com/path/to/pages/login.php</p>