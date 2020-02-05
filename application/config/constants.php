<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('ADMIN_THEME', 'themes/admin/');
define('FRONT_THEME', 'themes/front/');
define('SITE_URL', 'http://localhost/SmartTrader/');
define('FROM_EMAIL', 'tjcloudtest@gmail.com');
define('ADMIN_EMAIL', 'tjcloudtest@gmail.com');
define('SMTP_AUTH_USER', 'tjcloudtest@gmail.com');
define('SMTP_USER_PASS', 'Cool.1993');

define('SITE_NAME', 'Smart Trader');

define('OK',200);
define('SERVER_ERROR',500);

define('ALLOWED_IMG','gif|jpg|png|jpeg');
define('ALLOWED_VIDEO','mov|MOV|wmv|mp4');
define('ALLOWED_AUDIO','mp3');



/* End of file constants.php */
/* Location: ./application/config/constants.php */