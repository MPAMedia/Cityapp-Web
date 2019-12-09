<?php 

    /******************************************************PATHS CONFIGURATION*************************************/
    // you can change this to let files stays protecteds
    define("__ADMIN", "dashboard");
    // If deployed in a web server, change this according to your configuration
    // For Example. the domain name is www.someUrl.com, then if the php files are stored in
	// a folder named as "responsive" then the complete url would be
	// www.someUrl.com/responsive
    define("BASE_URL", "http://php-old.test/");
	// Folder directory for images uploaded from the desktop
    // Change Only the domain name and application folder  :  http://localhost/SmartGeoStore
    define("IMAGES_BASE_URL","http://php-old.test/uploads/images/");


    /******************************************************DATABASE CONFIGURATION *****************************/
    //Set your database Host name
    define("HOST_NAME", "localhost");
    // change the user access, CPanel have user roles, when writing and reading files
	// set it to allow the certain User to read/write
    define("DB_USERNAME", "root");
    // change this according to your account credentials
    define("DB_PASSWORD", "123");
    // if you wish you create your own name for   Database then change the word "db_geostore"
    define("DB_NAME", "mpa-city");

	define("CONF_VERSION", "1.5.1");

    /***************************** FROM JSON FILE *****************************/

    define("CRYPTO_KEY", "bbd9df7f4a59bcce72b5d4391229f3fb");
    define("PARAMS_FILE", md5(CRYPTO_KEY));
