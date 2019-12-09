<?php 
// Default timezone
ini_set('display_errors', 1);

if(function_exists("date_default_timezone_set")){
    date_default_timezone_set("UTC");
}


// Defaullt multibyte encoding
$mbstring = extension_loaded('mbstring') && function_exists('mb_get_info') ;
if($mbstring)
mb_internal_encoding("UTF-8"); 



// ROOTPATH
define("ROOTPATH", dirname(__FILE__)."/..");

// Check if SSL enabled
$ssl = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off" 
     ? true : false;
define("SSL_ENABLED", $ssl);

// Define APPURL
$app_url = (SSL_ENABLED ? "https" : "http")
         . "://"
         . $_SERVER["SERVER_NAME"]
         . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
         . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

$p = strrpos($app_url, "/update");
if ($p !== false) {
    $app_url = substr_replace($app_url, "", $p, strlen("/update"));
}

define("APPURL", $app_url);

require_once ROOTPATH."/update/app/helpers/common.helper.php";
require_once ROOTPATH."/update/app/helpers/password.helper.php";
require_once ROOTPATH."/update/app/helpers/db.helper.php";
require_once ROOTPATH."/update/app/vendor/RandomCompat/random.php";
require_once ROOTPATH."/update/app/core/Autoloader.php";

$loader = new Autoloader;
$loader->register();
$loader->addBaseDir(ROOTPATH.'/update/app/core');
$loader->addBaseDir(ROOTPATH.'/update/app/vendor');
