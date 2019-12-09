<?php
require_once 'init.php';


$index_page = "";
if(function_exists("apache_get_modules") && in_array('mod_rewrite', apache_get_modules())){
    $index_page = "";
}else{
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();
    if(strpos($phpinfo, "mod_rewrite")){
        $index_page = "";
    }else{
        $index_page = "index.php/";
    }
}

$index_page = "index.php/";

