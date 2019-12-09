<?php

    require_once 'init.php';
    require_once '../init.php';


    $redirection_url = APPURL;

    if (Input::post("action") != "install") {
        jsonecho("Invalid action", 101);
    }

    $required_fields = [
        "key",
        "db_host",
        "db_name",
        "db_username",
        "admin_path",
    ];

    $required_fields[] = "user_username";
    $required_fields[] = "user_name";
    $required_fields[] = "user_email";
    $required_fields[] = "user_password";
    $required_fields[] = "user_timezone";
    $required_fields[] = "crypto_key";

    foreach ($required_fields as $f) {
        if (!Input::post($f)) {
            jsonecho("Missing data: " . $f, 102);
        }
    }

    //init variablesÒ
    $base_url = APPURL . "/index.php";
    $admin_url = APPURL . "/index.php/" . Input::post("admin_path");
    $license_key = Input::post("key");
    $email = Input::post("user_email");
    $username = Input::post("user_username");
    $password = Input::post("user_password");
    $name = Input::post("user_name");
    $crypto_key = Input::post("crypto_key");
    $timezone = Input::post("user_timezone");


    $api_endpoint = "https://api.droideve.com/api/api2";

    $post_data = array(
        "pid"       => $license_key,
        "ip"        => getIp(),
        "uri"       => APPURL,
        "email"     => $email,
        "item"      => PROJECT_NAME,
        "reqfile"   => 1,
        "data"      => base64_encode(json_encode(array("email" => $email, "username" => $username, "crypto_key" => $crypto_key)))
    );

    // Validate License Key
    $validation_url = $api_endpoint . "/lvalidate";

    try {
        $validation = runCURL($validation_url, $post_data);
        $validation = json_decode($validation);
    } catch (Exception $e) {
        jsonecho("The API server is down! message error: \"" . $e->getMessage() . "\"", 105);
    }


    if (!isset($validation->success)) {
        jsonecho("Couldn't validate your license key!. (Error:011)", 104);
    }

    if ($validation->success != 1) {
        if ($validation->success == 0) {
            jsonecho("Couldn't validate your license key! (Error:012)", 105);
        }else{
            jsonecho($validation->error, 105);
        }
    }


    $dataconfig = $validation->dataconfig;
    $dataconfig = base64_decode($dataconfig);



    $host = Input::post("db_host");
    $user = Input::post("db_username");
    $pass = Input::post("db_password");
    $dbname = Input::post("db_name");
    $admin_path = Input::post("admin_path");
    $crypto_key = Input::post("crypto_key");

    //setup files
    $dataConfig = parse($dataconfig, array(
        "HOSTNAME" => $host,
        "USERNAME" => $user,
        "PASSWORD" => $pass,
        "DATABASE" => $dbname,
        "BASE_URL" => APPURL,
        "ADMIN_PATH" => strtolower($admin_path),
        "SECURE_URL" => APPURL,
        "IMAGES_BASE_URL" => APPURL . '/uploads/images/',
        "CRYPTO_KEY" => $crypto_key,
    ));

    $dataConfig = "<?php \n\n" . $dataConfig;

    //check file if exist
    if (file_exists(ROOTPATH . "/config/config.php"))
        @unlink(ROOTPATH . "/config/config.php");

    //generate config file
    try {

        saveInFile(ROOTPATH . "/config/config.php", $dataConfig);

        if (!file_exists(ROOTPATH . "/config/config.php")) {
            jsonecho("Couldn't generate config file, please change config folder to 0777", 105);

            @chmod(ROOTPATH . "/config", 0777);
            saveInFile(ROOTPATH . "/config/config.php", $dataConfig);
        }

        //generate params files
        $settings = $validation->datasettings;
        $settings = base64_decode($settings);


        if ($settings!="") {

            $settings = parse($settings, array(
                "DASHBOARD_VERSION" => APP_VERSION,
            ));

            $params = json_decode($settings, JSON_OBJECT_AS_ARRAY);

            if(INIT_PLATFORM=="ns-ios")
                $params['IOS_PURCHASE_ID'] = $license_key;
            else
                $params['ANDROID_PURCHASE_ID'] = $license_key;

            $params['PURCHASE_ID'] = $license_key;
            $params['DEFAULT_EMAIL'] = $email;
            $jsonData = json_encode($params, JSON_FORCE_OBJECT);
            saveInFile(ROOTPATH . "/config/" . md5($crypto_key) . ".json", $jsonData);


        }else{
            jsonecho("Unable to get settings data (Error:016)", 105);
        }

    } catch (Exception $e) {
        jsonecho("Couldn't access to the config folder (Error:014)", 105);
    }


    $sql = $validation->datasql;
    $sql = base64_decode($sql);

    //setup database
    $pdo = setupDatabase($host, $user, $pass, $dbname, $sql);


    if ($pdo) {

        //THAT FOR UPDATE
        $data_init = array(
            "login" => $username,
            "email" => $email,
            "password" => $password,
            "name" => $name,
            "timezone" => $timezone,
            "crypto_key" => $crypto_key,
            "purchase_id" => $license_key,
        );



        //create new table
        $url_create_table = $base_url . "/setting/createTableConfig";
        $response_create_table = @runCURL($url_create_table,array("exe"=>1));

        //emigrate config file to database
        $url_emigrate = $base_url . "/setting/emigrateAlConfigValues";
        $response_emigrate = @runCURL($url_emigrate,array("exe"=>1));

        //app init & create super user
        $url_app_init = $base_url . "/setting/app_init";

        $response = runCURL($url_app_init,array(
            "spid" => base64_encode($license_key),
            "data" => base64_encode(json_encode($data_init, JSON_FORCE_OBJECT))
        ));


        $response_ = $response;
        $response = json_decode($response);

        if(!isset($response->success)){

            //@unlink(ROOTPATH . "/config/" . md5($crypto_key) . ".json");
            //@unlink(ROOTPATH . "/config/config.php");

            jsonecho("Couldn't create initial data (Error:015) Please check logs file  \"application/logs\" ".$response_, 105);
        }

        if ($response->success == 1) {

            //setup user group access
            $url_set_grp = $base_url . "/user/setupDefaultGroupAccess";
            @runCURL($url_set_grp,array("exe"=>1));

            jsonecho(userConnectionInfo($username, $password, "",$admin_url), 1, $admin_url);
        } else { //

            @unlink(ROOTPATH . "/config/" . md5($crypto_key) . ".json");
            @unlink(ROOTPATH . "/config/config.php");

            $messages = "Something wrong!<br>";
            $messages = "Caused by your server configuration<br>";
            $messages .= "Before contacting our support, please make sure the following requirement are done:<br>";
            $messages .= "1. Config folder has permission 0777 <br>";
            $messages .= "2. index.php file has permission 0644 <br>";
            $messages .= "3. Server support mod_rewrite (Please Enable it then restart http server)<br>";
            $messages .= "4. All privileges mysql user are guaranteed<br>";
            $messages .= "5. be sure that the option ONLY_FULL_GROUP_BY is disabled in your MySQL database<br>";
            $messages .= "Don't forget delete all tables from database \"" . $dbname . "\" and set it up<br>";

            jsonecho($messages, 105);
        }

    } else {

        //check file if exist
        if (file_exists(ROOTPATH . "/config/config.php"))
            @unlink(ROOTPATH . "/config/config.php");

        jsonecho("Connection with database isn't correct!, please make sure that All privileges mysql user are guaranteed", 105);
    }


    function userConnectionInfo($login, $password, $data,$url="")
    {

        $html = "";
        $html .= "<b><u>Admin url:</u></b> <a href='" . $url . "' target='_blank'>" . $url . "</a><br>";
        $html .= "<b><u>Login:</u></b> $login <br> ";
        $html .= "<b><u>Password:</u></b> $password<BR> <BR>";
        $html .= $data;

        return $html;
    }

    function parse($content = "", $args = array())
    {

        foreach ($args as $key => $value) {
            $content = preg_replace("#\{" . $key . "\}#", $value, $content);
        }
        return $content;

    }

    function getIp()
    {
        return $_SERVER['SERVER_ADDR'];
    }

