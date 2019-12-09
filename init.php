<?php


    //default false
    define("SAFE_MODE", false);
    //the app version
    define("APP_VERSION", "1.5.1");
    ///////////////////////////////////
    define("_LOGS", 1);
    define("INDEX", "index.php");

    define("INIT_PLATFORM", "ns-android");//api item ID
    define("PROJECT_NAME", APP_VERSION . "," . INIT_PLATFORM);

    define("ENCODING", "UTF-8");

    if(!defined("CAMPAIGN_TYPES")){
        $var = array(
            "store","offer","event"
        );
        define("CAMPAIGN_TYPES",json_encode($var)) ;
    }


    //images upload
    define("MAX_IMAGE_UPLOAD", 1); //by MB
    define("MAX_NBR_IMAGES", 6);




