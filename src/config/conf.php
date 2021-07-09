<?php
if (!defined('OWNER')) define('OWNER', 'dev.mvinhas@gmail.com');

require_once 'Connector.php';

require_once 'autoloader.php';

require_once 'vendor/autoload.php';

include 'utilities.php';

define('LIKE', "LIKE $1");
define('LIKE_START', "LIKE $1%");
define('LIKE_END', "LIKE %1$");
define('LIKE_SOMEWHERE', "LIKE %$1%");

$config_flags->debugmode = $__CONFIG['debugmode'] ? $__CONFIG['debugmode'] : 1;
$config_flags->sitename = $__CONFIG['sitename'] ? $__CONFIG['sitename'] : '(no name found)';
$config_flags->email = $__CONFIG['email'] ? $__CONFIG['email'] : '(no email found)';
$config_flags->siteversion = $__CONFIG['siteversion'] ? $__CONFIG['siteversion'] : '(no version found)';
$config_flags->siteauthor = $__CONFIG['siteauthor'] ? $__CONFIG['siteauthor'] : '(no author found)';
$config_flags->launchyear = $__CONFIG['launchyear'] ? $__CONFIG['launchyear'] : date('Y');

if ((int)$__CONFIG['debugmode'] === 1 || empty($__CONFIG)) {
    $time_start = microtime(true);
    ini_set("display_errors", "On");
    ini_set("error_prepend_string","<div class='debug-silent'>");
    ini_set("error_append_string","</div>");
    mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT); 
    error_reporting(E_ALL);   
}

$loader = new \Twig\Loader\FilesystemLoader('views');

$twig = new \Twig\Environment($loader);
