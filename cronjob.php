<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 1/27/2018
 * Time: 12:17
 */


include "config/config.php";

$base_url = BASE_URL;

if(!preg_match('#(index.php)#',BASE_URL)){
    $base_url = BASE_URL.'/index.php';
}

echo file_get_contents($base_url."/setting/cron_exe");
