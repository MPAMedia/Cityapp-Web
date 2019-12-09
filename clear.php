<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 1/27/2018
 * Time: 12:17
 */

include "config/config.php";

echo file_get_contents(BASE_URL."/store/clear");
echo file_get_contents(BASE_URL."/event/clear");
echo file_get_contents(BASE_URL."/offer/clear");
