<?php

// -----------
// DB Settings
// -----------

define( 'DB_USER', 'root' );
define( 'DB_NAME', 'easysite' );
define( 'DB_HOST', 'localhost' );
define( 'DB_PASS', '' );
define( 'DB_TYPE', 'mysql' );
define( 'DB_PREFIX', 'es' );

define( 'DOC_ROOT', '/' );
define( 'EASYSITE_INSTALLED', '0' );
define( 'VERSION', '3.2.8' );
define( 'DEMO_MODE', '0' );

// -------------
// Mail settings
// -------------

// text|html
define( 'MAIL_FORMAT', 'text' );
//mail|sendmail|smtp
define( 'MAIL_TYPE', 'mail' );
define( 'SMTP_HOST', '' );
define( 'SMTP_PORT', '25' );
define( 'SMTP_AUTH', '0' );
define( 'SMTP_USER', '' );
define( 'SMTP_PASS', '' );
define( 'SM_PATH', '' );

define( 'DEFAULT_SITE', 'default' );

// set to true to permit backup of table structures during site backups
// this open should be kept to false if using EasySite in a multi-user environment
define( 'ALLOW_FULL_BACKUP', false );


// should not be 0
define( 'GUEST_GROUP', -100 );


// maximum browser session length, in seconds
define( 'MAX_SESSION_LENGTH', 86400 );

define( 'FULL_PATH', dirname(__FILE__) . '/' );

// directories for specialized sub-folders
// NOTE: You should NOT rename the corresponding directories in the 'templates' folder!

define ( 'ADMIN_DIR', 'manage' );
define ( 'TEMP_DIR', 'temp' );
define ( 'MODULES_DIR', 'modules' );

// directory paths for shared libraries & files

define ( 'SMARTY_DIR', FULL_PATH . 'libs/Smarty/' );
define ( 'TEMPLATE_DIR', FULL_PATH . 'templates/' );
define ( 'TEMPLATE_C_DIR', FULL_PATH . 'templates_c/' );
define ( 'CACHE_DIR', FULL_PATH . 'cache/' );
define ( 'INCLUDE_DIR', FULL_PATH . 'includes/' );
define ( 'ROOT_DIR', FULL_PATH );
define ( 'PEAR_DIR', FULL_PATH . 'libs/Pear/' );

// -----------------
// login error codes
// -----------------

define ( 'ACCESS_DENIED', 1 );
define ( 'LOGIN_NOT_FOUND', 2 );
define ( 'LOGIN_EXPIRED', 3 );

//ini_set( 'include_path', PEAR_DIR );

// --------------------
// database table names
// --------------------

define ( 'MENUITEMS_TABLE', DB_PREFIX . '_menu_items' );
define ( 'LAYERS_TABLE', DB_PREFIX . '_layers' );
define ( 'PAGES_TABLE', DB_PREFIX . '_pages' );
define ( 'SECTIONS_TABLE', DB_PREFIX . '_page_sections' );
define ( 'SETTINGS_TABLE', DB_PREFIX . '_settings' );
define ( 'SITES_TABLE', DB_PREFIX . '_sites' );
define ( 'TEMPSITES_TABLE', DB_PREFIX . '_temp_sites' );
define ( 'STYLES_TABLE', DB_PREFIX . '_styles' );
define ( 'USERS_TABLE', DB_PREFIX . '_users' );
define ( 'GROUPS_TABLE', DB_PREFIX . '_groups' );
define ( 'MENUS_TABLE', DB_PREFIX . '_menus' );
define ( 'FILES_TABLE', DB_PREFIX . '_files' );
define ( 'FORMREDIRECTS_TABLE', DB_PREFIX . '_form_redirects' );
define ( 'REPORTS_TABLE', DB_PREFIX . '_reports' );
define ( 'EMBEDDEDREPORTS_TABLE', DB_PREFIX . '_embedded_reports' );
define ( 'REPORTCONDITIONS_TABLE', DB_PREFIX . '_report_conditions' );
define ( 'REPORTGROUPS_TABLE', DB_PREFIX . '_report_groups' );
define ( 'REPORTFIELDS_TABLE', DB_PREFIX . '_report_fields' );
define ( 'FILTEROVERRIDES_TABLE', DB_PREFIX . '_filter_overrides' );
define ( 'FORMSECTIONS_TABLE', DB_PREFIX . '_form_sections' );
define ( 'FORMGROUPS_TABLE', DB_PREFIX . '_form_groups' );
define ( 'FORMSUBMISSIONS_TABLE', DB_PREFIX . '_form_submissions' );
define ( 'FORMS_TABLE', DB_PREFIX . '_forms' );
define ( 'PERMISSIONS_TABLE', DB_PREFIX . '_permissions' );
define ( 'SESSIONS_TABLE', DB_PREFIX . '_sessions' );
define ( 'SKINS_TABLE', DB_PREFIX . '_skins' );
define ( 'SHARES_TABLE', DB_PREFIX . '_shares' );
define ( 'LISTS_TABLE', DB_PREFIX . '_lists' );
define ( 'LISTITEMS_TABLE', DB_PREFIX . '_list_items' );
define ( 'SITECONFIGURATIONS_TABLE', DB_PREFIX . '_site_configurations' );
define ( 'MAILINGLISTS_TABLE', DB_PREFIX . '_mailing_lists' );
define ( 'MAILINGTO_TABLE', DB_PREFIX . '_mailing_to' );
define ( 'BACKUPS_TABLE', DB_PREFIX . '_backups' );
define ( 'AUTOBACKUPS_TABLE', DB_PREFIX . '_auto_backups' );

// tables shared among many modules

define ( 'MODULES_TABLE', DB_PREFIX . '_modules' );
define ( 'MODULECATEGORIES_TABLE', DB_PREFIX . '_module_categories' );
define ( 'MODULESETTINGS_TABLE', DB_PREFIX . '_module_settings' );
define ( 'MODULEOBJECTS_TABLE', DB_PREFIX . '_module_objects' );

// polling tables are defined here instead of in the polling mod
// because they are required during form submission of the poll

define( 'POLLS_TABLE', DB_PREFIX . '_polls' );
define( 'POLLRESULTS_TABLE', DB_PREFIX . '_poll_results' );


// share tool constants
define( 'ALL_TARGETS', -1 );

?>