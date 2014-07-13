<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|   http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['base_url'] = 'http://localhost/';


/*
 |-------------------------------------------------------------------------
 | Editable with DB data
 |-------------------------------------------------------------------------
 */









/*
|--------------------------------------------------------------------------
| Default site themes for mobile and full version
|--------------------------------------------------------------------------
*/
$config['email_from']   = 'sneakquie@gmail.com';
$config['email_from_name']   = 'Dmitrii Salabai';

$config['user_url_type'] = 2;
$config['followings_number'] = 15;
$config['user_flag_antispam']   = 10;
$config['show_full_user_profile'] = true;
$config['user_show_posts']   = true;
$config['user_show_comments']   = true;
$config['user_show_main']   = true;
$config['user_show_followers']   = true;
$config['user_show_followings']   = true;
$config['last_seen_show_yesterday']   = true;
$config['last_seen_show_full']   = 1;
$config['user_registered_show_full']   = 1;
$config['user_registered_show_yesterday']   = true;
$config['show_signup_date']   = true;
$config['show_invited_users']   = true;
$config['allow_verified'] = true;

$config['show_born_date']   = false;
$config['show_email']   = false;
$config['show_last_login']   = false;
$config['allow_email']   = false;
$config['notify_comments']   = false;
$config['notify_comments_email']   = false;
$config['notify_answers']   = false;
$config['notify_answers_email']   = false;
$config['notify_messages']   = false;
$config['notify_messages_email']   = false;
$config['notify_follow_news']   = false;
$config['notify_cats_news']   = false;
$config['notify_likes']   = false;


$config['nocover_url']   = 'public/images/nocover.png';
$config['noavatar_url']   = 'public/images/noavatar.png';
$config['avatar_max_size']   = 40;
$config['cover_max_size']   = 8;
$config['show_js_required']   = true;

$config['login_social']   = true;
$config['login_social_small']   = true;
$config['login_email']   = false;
$config['login_email_username']   = false;
$config['login_block_attemps']   = 10;
$config['login_block_user']   = false;
$config['login_block_time']   = 1;
$config['login_captcha']   = false;
$config['login_recaptcha']   = false;
$config['login_captcha_width']    = 300;
$config['login_captcha_height']   = 60;

$config['recover_email']   = true;
$config['recover_email_username']   = true;
$config['recover_expire'] = 1;
$config['recover_captcha']   = true;
$config['recover_html_confirm']   = false;
$config['recover_recaptcha']   = false;
$config['recover_captcha_width']    = 300;
$config['recover_captcha_height']   = 60;

$config['signup_repeat_password']   = false;
$config['signup_default_rating']   = 10;
$config['signup_disabled']   = false;
$config['signup_invite']   = false;
$config['signup_invite_required'] = true;
$config['signup_confirm_email'] = true;
$config['signup_html_confirm'] = true;
$config['signup_show_confirm_page'] = false;
$config['signup_default_group'] = 1;
$config['signup_after_confirmation'] = 0;
$config['signup_social']    = true;
$config['signup_social_small']  = true;
$config['signup_terms']    = false;
$config['signup_terms_link']    = 'static/terms/';
$config['signup_services']  = array('facebook', 'vkontakte', 'twitter', 'googleplus', 'odnoklassniki');
$config['signup_allowed_services']  = array('facebook', 'vkontakte', 'twitter', 'steam', 'flickr', 'vimeo', 'youtube', 'googleplus', 'odnoklassniki', 'linkedin', 'tumblr');
$config['signup_captcha']   = false;
$config['signup_recaptcha'] = false;
$config['signup_captcha_width']    = 300;
$config['signup_captcha_height']   = 60;

$config['mobile_theme'] = 'mobile';
$config['theme']    = 'full';
$config['timezone'] = 'Europe/Warsaw';

$config['allow_change_timezone'] = true;
$config['allow_change_language_settings'] = true;
$config['allow_change_language_menu'] = true;
$config['allow_change_language_auto'] = false;
$config['allow_change_password'] = true;
$config['allow_messages'] = true;
$config['allow_social_profiles'] = true;
$config['allow_search'] = true;
$config['allow_about'] = true;
$config['allow_website'] = true;
$config['allow_speech_search'] = true;
$config['allow_change_email'] = true;
$config['allow_change_login'] = true;
$config['last_login_show'] = 1;
$config['allow_contact_email'] = 2;
$config['allow_flag_unlogged'] = true;
$config['allow_notes'] = true;
$config['flag_time'] = 1;

$config['profile_social_networks'] = array('facebook' => 'facebook.com/',
                                           'vkontakte' => 'vk.com/',
                                           'twitter' => 'twitter.com/',
                                           'steam' => 'steamcommunity.com/id/',
                                           'flickr' => 'flickr.com/',
                                           'vimeo' => 'vimeo.com/',
                                           'youtube' => 'youtube.com/user/',
                                           'googleplus' => 'plus.google.com/',
                                           'odnoklassniki' => 'odnoklassniki.ru/profile/',
                                           'tumblr' => 'tumblr.com/',);

$config['max_login_length'] = 10;
$config['old_password_required'] = true;

$config['site_name'] = ' — False Community';
$config['show_categories'] = true;
$config['show_confirm_message'] = true;
$config['recaptcha_public'] = '6LcRVvESAAAAAPqgLmlNfKLT_rRv9VtXY_G9UQRi';
$config['recaptcha_private'] = '6LcRVvESAAAAAIIZmMo9essf6eIv5RNFF9F7SRAi';
$config['recaptcha_theme'] = 'clean';


/*
|--------------------------------------------------------------------------
| Available language in system
|--------------------------------------------------------------------------
*/
$config['languages'] = array('en' => array('native' => 'English', 'is_rtl' => false,),
                             'de' => array('native' => 'Deutsch', 'is_rtl' => false,),
                             'ru' => array('native' => 'Русский', 'is_rtl' => false,),
                             'uk' => array('native' => 'Українська', 'is_rtl' => false,),
                             'pl' => array('native' => 'Polski', 'is_rtl' => false,),
                             'es' => array('native' => 'Español', 'is_rtl' => false,),
                             'it' => array('native' => 'Italiano', 'is_rtl' => false,),
                             'fr' => array('native' => 'Français', 'is_rtl' => false,),
                             'cn' => array('native' => '中国的', 'is_rtl' => false,),
                             'ar' => array('native' => 'العربية', 'is_rtl' => true,),);
$config['templates'] = array('full' => true,);
$config['language'] = 'list';
$config['site_language'] = 'ru';















/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'            Default - auto detects
| 'PATH_INFO'       Uses the PATH_INFO
| 'QUERY_STRING'    Uses the QUERY_STRING
| 'REQUEST_URI'     Uses the REQUEST_URI
| 'ORIG_PATH_INFO'  Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol'] = 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']      = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|   0 = Disables logging, Error logging TURNED OFF
|   1 = Error Messages (including PHP errors)
|   2 = Debug Messages
|   3 = Informational Messages
|   4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = ',4E/Z2irx.S>LKA\RmnG3V1f:lUImLMK';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'        = the name you want for the cookie
| 'sess_expiration'         = the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'    = Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'     = Whether to encrypt the cookie
| 'sess_use_database'       = Whether to save the session data to a database
| 'sess_table_name'         = The name of the session database table
| 'sess_match_ip'           = Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'    = Whether to match the User Agent when reading the session data
| 'sess_time_to_update'     = how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']     = 'ci_session';
$config['sess_expiration']      = 7200;
$config['sess_expire_on_close'] = FALSE;
$config['sess_encrypt_cookie']  = FALSE;
$config['sess_use_database']    = FALSE;
$config['sess_table_name']      = 'ci_sessions';
$config['sess_match_ip']        = FALSE;
$config['sess_match_useragent'] = TRUE;
$config['sess_time_to_update']  = 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
$config['cookie_prefix']    = "";
$config['cookie_domain']    = "";
$config['cookie_path']      = "/";
$config['cookie_secure']    = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';


/* End of file config.php */
/* Location: ./application/config/config.php */
