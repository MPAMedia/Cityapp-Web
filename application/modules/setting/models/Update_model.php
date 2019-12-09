<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Update_model extends CI_Model
{

    const PIDINDEX  = "ANDROID_PURCHASE_ID";
    public $loadedFiles = "";


    public function __construct()
    {
        parent::__construct();

        $this->loadFile();
        define("SITEM",APP_VERSION.",ns-ios");

    }


    public function save($key,$value){

        $params = json_decode($this->loadedFiles,JSON_OBJECT_AS_ARRAY);
        $params[$key] = $value;

        $this->loadedFiles = json_encode($params);

        //save file
        if (file_exists("config/" . PARAMS_FILE . ".json")) {
            $myfile = fopen("config/" . PARAMS_FILE . ".json", "w") or die("Unable to open file!");
            fwrite($myfile, $this->loadedFiles);
            fclose($myfile);
        }else{
            //get default file
        }

    }

    public function saveSettingsFile($params){

        $this->loadedFiles = json_encode($params);

        //save file
        if (file_exists("config/" . PARAMS_FILE . ".json")) {
            $myfile = fopen("config/" . PARAMS_FILE . ".json", "w") or die("Unable to open file!");
            fwrite($myfile, $this->loadedFiles);
            fclose($myfile);
        }else{
            //get default file
        }

        return TRUE;
    }

    public function loadFile()
    {
        if($this->loadedFiles == ""){

            $this->loadedFiles = loadData("config/" . PARAMS_FILE . ".json");

        }
    }

    public function haveUpdate(){

        if($this->loadedFiles != ""){

            $params = json_decode($this->loadedFiles,JSON_OBJECT_AS_ARRAY);

            if(isset($params['_APP_VERSION'])){ //check version of config files

                $app_v_json = ($params['_APP_VERSION']);
                $app_v_php = (APP_VERSION);

                if($app_v_json==$app_v_php)
                    return FALSE;
            }

            return TRUE;

        }else{
            return TRUE;
        }

    }

    public function updateToLatest(){


        $loop = true;

        while ($loop){

            $params = json_decode($this->loadedFiles,JSON_OBJECT_AS_ARRAY);


            if(isset($params['_APP_VERSION'])){ //check version of config files

                $app_v_json = ($params['_APP_VERSION']);
                $app_v_php = (APP_VERSION);

                if($app_v_json==$app_v_php){
                    return array(Tags::SUCCESS=>1);
                }

            }


            if($this->checkVersion(APP_VERSION,"1.2")){ //upgrade to 1.2+

                if(!isset($params['_APP_VERSION']) OR (isset($params['_APP_VERSION'])
                        AND $this->checkVersion($params['_APP_VERSION'],"1.1") )   ){ // to 1.2.1


                    $params['_APP_VERSION'] = "1.2.1";

                    //update file config
                    $params = $this->upgradeFileConfigTo1_2($params);

                    //update database
                    $this->upgradeDatabaseTo1_2();



                }else if(APP_VERSION=="1.2.2"){


                    $params['_APP_VERSION'] = "1.2.2";

                    $this->upgradeDatabaseTo1_2_2();

                    $this->assignLoadedImagesToSuperUser();

                    $params = $this->upgradeFileConfigTo1_2_2($params);


                }


            }else  if($this->checkVersion(APP_VERSION,"1.3")) { //upgrade to 1.3+


                if(!isset($params['_APP_VERSION']) OR (isset($params['_APP_VERSION'])
                        AND $this->checkVersion($params['_APP_VERSION'],"1.1") )   ){ // to 1.2.1


                    $params['_APP_VERSION'] = "1.2.1";

                    //update file config
                    $params = $this->upgradeFileConfigTo1_2($params);

                    //update database
                    $this->upgradeDatabaseTo1_2();


                }else if($params['_APP_VERSION']=="1.2.1"){


                    $params['_APP_VERSION'] = "1.2.2";

                    $this->upgradeDatabaseTo1_2_2();

                    $this->assignLoadedImagesToSuperUser();

                    $params = $this->upgradeFileConfigTo1_2_2($params);


                } else if($params['_APP_VERSION']=="1.2.2"){

                    $params['_APP_VERSION'] = "1.3.1";

                    $this->upgradeDatabase_to_1_3_1();

                    $params = $this->upgradeFileConfigTo1_3_1($params);

                } else if($params['_APP_VERSION']=="1.3.1"){

                    $params['_APP_VERSION'] = "1.3.2";

                    $params = $this->upgradeFileConfigTo1_3_1($params);

                }


            }else  if($this->checkVersion(APP_VERSION,"1.4")) { //upgrade to 1.3+


                if(!isset($params['_APP_VERSION']) OR (isset($params['_APP_VERSION'])
                        AND $this->checkVersion($params['_APP_VERSION'],"1.1") )   ){ // to 1.2.1


                    $params['_APP_VERSION'] = "1.2.1";

                    //update file config
                    $params = $this->upgradeFileConfigTo1_2($params);

                    //update database
                    $this->upgradeDatabaseTo1_2();


                }else if($params['_APP_VERSION']=="1.2.1"){


                    $params['_APP_VERSION'] = "1.2.2";

                    $this->upgradeDatabaseTo1_2_2();

                    $this->assignLoadedImagesToSuperUser();

                    $params = $this->upgradeFileConfigTo1_2_2($params);



                } else if($params['_APP_VERSION']=="1.2.2"){



                    $params['_APP_VERSION'] = "1.3.1";

                    $this->upgradeDatabase_to_1_3_1();

                    $params = $this->upgradeFileConfigTo1_3_1($params);


                } else if($params['_APP_VERSION']=="1.3.1"){


                    $params['_APP_VERSION'] = "1.3.2";


                } else if($params['_APP_VERSION']=="1.3.2"){


                    $params['_APP_VERSION'] = "1.4.1";

                }else if($params['_APP_VERSION']=="1.4.1"){

                    $params['_APP_VERSION'] = "1.4.2";

                }

            }else  if($this->checkVersion(APP_VERSION,"1.5")) {


                if(!isset($params['_APP_VERSION']) OR (isset($params['_APP_VERSION'])
                        AND $this->checkVersion($params['_APP_VERSION'],"1.1") )   ){ // to 1.2.1


                    $params['_APP_VERSION'] = "1.2.1";

                    //update file config
                    $params = $this->upgradeFileConfigTo1_2($params);

                    //update database
                    $this->upgradeDatabaseTo1_2();


                }else if($params['_APP_VERSION']=="1.2.1"){


                    $params['_APP_VERSION'] = "1.2.2";

                    $this->upgradeDatabaseTo1_2_2();

                    $this->assignLoadedImagesToSuperUser();

                    $params = $this->upgradeFileConfigTo1_2_2($params);



                } else if($params['_APP_VERSION']=="1.2.2"){



                    $params['_APP_VERSION'] = "1.3.1";

                    $this->upgradeDatabase_to_1_3_1();

                    $params = $this->upgradeFileConfigTo1_3_1($params);


                } else if($params['_APP_VERSION']=="1.3.1"){


                    $params['_APP_VERSION'] = "1.3.2";


                } else if($params['_APP_VERSION']=="1.3.2"){


                    $params['_APP_VERSION'] = "1.4.1";

                }else if($params['_APP_VERSION']=="1.4.1"){


                    $params['_APP_VERSION'] = "1.4.2";

                }else if($params['_APP_VERSION']=="1.4.2"){

                    $params['_APP_VERSION'] = "1.5.1";

                }

            }



            $this->saveSettingsFile($params);

        }



    }

    public function checkVersion($version,$code,$type=""){

        for($i=1;$i<=10;$i++){
            if($version==$code.".".$i.$type){
                return TRUE;
            }
        }

        return FALSE;
    }

    private function upgradeFileConfigTo1_2_2($params)
    {


        if(!isset($params['USER_REGISTRATION'])){
            $params['USER_REGISTRATION'] = TRUE;
        }


        if(!isset($params['ENABLE_FRONT_END'])){
            $params['ENABLE_FRONT_END'] = TRUE;
        }

        return $params;
    }

    private function upgradeFileConfigTo1_2($params){


        if(!isset($params['ENABLE_MESSAGES'])){
            $params['ENABLE_MESSAGES'] = TRUE;
        }

        if(!isset($params['DASHBOARD_ANALYTICS'])){
            $params['DASHBOARD_ANALYTICS'] = "U-XXXXXXX";
        }

        if(!isset($params['DASHBOARD_COLOR'])){
            $params['DASHBOARD_COLOR'] = "#dd4b39";
        }

        if(!isset($params['CHAT_WITH_FIREBASE'])){
            $params['CHAT_WITH_FIREBASE'] = TRUE;
        }

        if(!isset($params['ALLOW_DASHBOARS_MESSENGER_TO_OWNERS'])){
            $params['ALLOW_DASHBOARS_MESSENGER_TO_OWNERS'] = FALSE;
        }

        if(!isset($params['NBR_RECENTLY_ADDED_STORES'])){
            $params['NBR_RECENTLY_ADDED_STORES'] = 5;
        }

        if(!isset($params['NBR_RECENTLY_REVIEW'])){
            $params['NBR_RECENTLY_REVIEW'] = 7;
        }


        if(!isset($params['IMAGES_LIMITATION'])){
            $params['IMAGES_LIMITATION'] = 6;
        }

        if(!isset($params['MESSAGE_WELCOME'])){
            $params['MESSAGE_WELCOME'] = "";
        }

        if(!isset($params[self::PIDINDEX])){
            $params[self::PIDINDEX] = "";
        }

        //upgrade currencies
        if(isset($params['CURRENCIES']) and !empty($params['CURRENCIES']))
            foreach ($params['CURRENCIES'] as $key => $value){

                if(isset($params['CURRENCIES'][$key]))
                    if(!is_array($params['CURRENCIES'][$key])){
                        $params['CURRENCIES'][$key] = array(
                            "code" => $key,
                            "symbol"=> "",
                            "name"=> $params['CURRENCIES'][$key],
                            "format"=> 1,
                            "rate"=> 1,
                        );
                    }

            }



        return $params;

    }

    public function upgradeDatabaseTo1_2(){



        $this->db->db_debug = FALSE; //disable debugging for queries

        $this->db->query("ALTER TABLE `discussion` DROP COLUMN `id_discussion`");
        $this->db->query("ALTER TABLE `discussion` ADD `id_discussion` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id_discussion`)");

        $this->db->query("ALTER TABLE `offer` ADD tags VARCHAR(200) NULL AFTER `date_created`");
        $this->db->query("ALTER TABLE `event` ADD tags VARCHAR(200) NULL AFTER `date_created`");
        $this->db->query("ALTER TABLE `store` ADD tags VARCHAR(200) NULL AFTER `date_created`");

        /*
         *  SUPPORT CHARACTERS
         */
        $this->db->query("SET NAMES 'utf8'");
        $this->db->query("SET CHARACTER SET utf8");

        $this->db->query("ALTER DATABASE ".DB_NAME." CHARACTER SET utf8 COLLATE utf8_general_ci;");

        $tables = $this->db->list_tables();

        foreach ($tables as $table)
            $this->db->query("ALTER ".$table." CHARACTER SET utf8 COLLATE utf8_general_ci;");

        //ALTER TABLE `event` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        //////////////////////////////////////////////////


        $this->db->where("typeAuth","admin");
        $this->db->order_by("id_user","ASC");
        $admin = $this->db->get("user");
        $admin = $admin->result();

        if(count($admin)>0){
            $this->db->where("id_user",$admin[0]->id_user);
            $this->db->update("user",array(
                "manager"   => 1
            ));
        }


    }

    public function upgradeDatabaseTo1_2_2(){

        $this->db->db_debug = FALSE; //disable debugging for queries
        $this->db->query("ALTER TABLE `image` ADD user_id INT(11) NULL AFTER `type`");
    }

    public function checkAndPutPID(){

        if(file_exists("config/".PARAMS_FILE.".json")){
            $path = "config/".PARAMS_FILE.".json";
            $params = file_get_contents(Path::getPath(array($path)));
        }else{
            $path = "config/params.json";
            $params = file_get_contents(Path::getPath(array($path)));
        }

        $params = json_decode($params,JSON_OBJECT_AS_ARRAY);

        if(isset($params['_APP_VERSION'])){ //check version of config files
            $app_v_json = ($params['_APP_VERSION']);
            $app_v_php = (APP_VERSION);

            if($app_v_json==$app_v_php)
                return array(Tags::SUCCESS=>1);
        }

        $id = trim($this->input->get("spid"));

        if($id=="")
            $id = trim($this->input->get("pid"));
        $id = base64_decode($id);
        if($id!=""){
            $params[self::PIDINDEX] = $id;
            //update file config
            @file_put_contents(Path::getPath(array($path)),json_encode($params,JSON_FORCE_OBJECT));
        }

    }

    private function assignLoadedImagesToSuperUser(){

        $data = loadAllImages();

        foreach ($data as $value){

            $this->db->where("image",$value);
            $c = $this->db->count_all_results("image");

            if($c==0){

                $user_id = intval($this->mUserBrowser->getData("id_user"));

                if($user_id==0){
                    $user_id = 1;
                }

                $this->db->insert('image',array(
                    "image"     =>  $value,
                    "type"      =>  "image/jpeg",
                    "user_id"      =>  $user_id,
                ));
            }
        }

    }

    private function upgradeDatabase_to_1_3_1(){

        $this->db->db_debug = FALSE; //disable debugging for queries
        $this->db->query("ALTER TABLE  `setting` ADD  `will_expired` DATETIME NOT NULL AFTER  `push_campaign_auto`");

        $this->db->query("ALTER TABLE `offer` ADD featured VARCHAR(200) NULL AFTER `date_created`");
        $this->db->query("ALTER TABLE `event` ADD featured VARCHAR(200) NULL AFTER `date_created`");
        $this->db->query("ALTER TABLE `store` ADD featured VARCHAR(200) NULL AFTER `date_created`");


        $this->db->query("ALTER TABLE `offer` MODIFY `date_start` DATETIME");
        $this->db->query("ALTER TABLE `offer` MODIFY `date_end` DATETIME");

        $this->db->query("ALTER TABLE `event` MODIFY `date_created` DATETIME");
        $this->db->query("ALTER TABLE `event` MODIFY `date_b` DATETIME");
        $this->db->query("ALTER TABLE `event` MODIFY `date_e` DATETIME");

        $this->db->query("ALTER TABLE  `campaign` ADD  `received` INT NOT NULL DEFAULT  '0' AFTER  `seen`;");

        $this->db->query("ALTER TABLE  `pending_campaigns` ADD  `guest_id` INT NOT NULL DEFAULT  '0' AFTER  `campaign_id`;");

        $this->db->query("ALTER TABLE `pending_campaigns` ADD logs VARCHAR(50) NULL AFTER `campaign_id`");
        $this->db->query("ALTER TABLE  `pending_campaigns` ADD  `failed` INT NOT NULL DEFAULT  '0' AFTER  `logs`;");

    }

    private function upgradeFileConfigTo1_3_1($params){



        if(!isset($params['SMTP_SERVER_ENABLED'])){
            $params['SMTP_SERVER_ENABLED'] = FALSE;
        }

        if(!isset($params['SMTP_HOST'])){
            $params['SMTP_HOST'] = "";
        }

        if(!isset($params['SMTP_PORT'])){
            $params['SMTP_PORT'] = 465;
        }


        if(!isset($params['SMTP_USER'])){
            $params['SMTP_USER'] = "";
        }


        if(!isset($params['SMTP_PASS'])){
            $params['SMTP_PASS'] = "";
        }


        if(!isset($params['ENABLE_CAMPAIGNS_FOR_OWNER'])){
            $params['ENABLE_CAMPAIGNS_FOR_OWNER'] = TRUE;
        }

        if(!isset($params['_APPURL'])){
            $params['_APPURL'] = APPURL;
        }


        return $params;
    }

    public function getPid(){

        $pidandroid = $this->input->post("pid_android");
        $pidios = $this->input->post("pid_ios");

        $pid = "";


        if($pidandroid!="")
            $pid = $pidandroid;

        if($pidios!="")
            $pid = $pidios;

        return $pid;
    }

    public function prepareDemagedSettingFile(){

        $pid = $this->input->get("myPID");

        if($pid!=""){

            $result = MyCurl::run("https://api.droideve.com/api/api2/pchecker",array(
                "item"                  => PROJECT_NAME,
                "pid"                   => $pid,
                "reqfile"               => 1,
                "update-settings"       => $this->loadedFiles
            ));

            $data = json_decode($result,JSON_OBJECT_AS_ARRAY);

            if(isset($data[Tags::SUCCESS]) and $data[Tags::SUCCESS]==1) {

                if(isset($data["datasettings"])){

                    $settings = base64_decode($data["datasettings"]);
                    $settings = $this->parse($settings,array(
                        "DASHBOARD_VERSION"   =>  APP_VERSION
                    ));

                    $settings = json_decode($settings,JSON_OBJECT_AS_ARRAY);
                    $settings["PURCHASE_ID"] = $pid;
                    $this->saveSettingsFile($settings);

                }

            }

        }

    }

    //check pid
    public function checkMyLatestVersion(){

        $pid = $this->getPid();

        $result = MyCurl::run("https://api.droideve.com/api/api2/pchecker",array(
            "item"                  => PROJECT_NAME,
            "pid"                   => $pid,
            "reqfile"               => 1,
            "update-settings"       => $this->loadedFiles
        ));


        $data = json_decode($result,JSON_OBJECT_AS_ARRAY);

        if(isset($data[Tags::SUCCESS]) and $data[Tags::SUCCESS]==1){


            if(isset($data["datasettings"])){

                $settings = base64_decode($data["datasettings"]);
                $settings = $this->parse($settings,array(
                    "DASHBOARD_VERSION"   =>  _APP_VERSION
                ));
                $settings = json_decode($settings,JSON_OBJECT_AS_ARRAY);
                $settings[self::PIDINDEX] = $pid;
                $this->saveSettingsFile($settings);

            }


            if(isset($data["dataconfig"])){

                $dataconfig = trim($data["dataconfig"]);

                if($dataconfig!=""){

                    $config = base64_decode($dataconfig);
                    $config = $this->parse($config,array(
                        "HOSTNAME" => HOST_NAME,
                        "USERNAME" => DB_USERNAME,
                        "PASSWORD" => DB_PASSWORD,
                        "DATABASE" => DB_NAME,
                        "BASE_URL" => BASE_URL,
                        "ADMIN_PATH" => __ADMIN,
                        "SECURE_URL" => BASE_URL,
                        "IMAGES_BASE_URL" => IMAGES_BASE_URL,
                        "CRYPTO_KEY" => CRYPTO_KEY,
                        "CONFIG_VERSION" => APP_VERSION,
                    ));


                    MyFile::saveFile("config/config.php","<?php \n\n" .$config);

                }

            }
        }



        return $result;
    }


    public function verifyPurchaseId(){

        $pid = $this->input->post("pid");

        $result = MyCurl::run("https://api.droideve.com/api/api2/linker",array(
            "item"                  => PROJECT_NAME,
            "sitem"                  => SITEM,
            "pid"                   => $pid,
            "reqfile"               => 1,
            "update-settings"       => $this->loadedFiles,
        ));

        $data = json_decode($result,JSON_OBJECT_AS_ARRAY);

        if(isset($data[Tags::SUCCESS]) and $data[Tags::SUCCESS]==1){

            $filekey = "";
            if(isset($data["datasettings"])){

                $settings = base64_decode($data["datasettings"]);
                $settings = json_decode($settings,JSON_OBJECT_AS_ARRAY);
                $filekey = $settings["FILE"];
                $this->saveSettingsFile($settings);

            }

            if(isset($data["linkerdata"]) AND $filekey!=""){
                $linkerdata = base64_decode($data["linkerdata"]);
                MyFile::saveFile("config/".$filekey.".json",$linkerdata);
            }


        }

        return $result;
    }



    function parse($content = "", $args = array())
    {

        foreach ($args as $key => $value) {
            $content = preg_replace("#\{" . $key . "\}#", $value, $content);
        }
        return $content;

    }



}