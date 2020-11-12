To enable search-engine friendly URLS, copy this "htaccess" file to the EasySite root folder, and rename it to .htaccess

You must be using the Apache web server with the mod_rewrite extension enabled to use this feature.

Please note that this...

# Replace with site url prefix
RewriteBase /

... should be replaced with your "docroot" setting from config.php, if different than "/". For example, if you have EasySite installed to a sub-folder called 'easysite' (i.e., your docroot is something like "/easysite/"), then this value in htaccess would be changed to:

# Replace with site url prefix
RewriteBase /easysite/