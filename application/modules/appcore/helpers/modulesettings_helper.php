<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 12/9/2017
 * Time: 19:11
 */

class ModuleSettings{

    private static $loadSettings = array();

    public static function register($moduleName,$items=array(),$init=array()){

        $changes = FALSE;
        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $settings = self::getAppSetting();
        foreach ($items as $key => $value){
            if(!isset($settings[$moduleName][$value])){
                $changes = TRUE;

                if(isset($init[$value])){
                    $settings[$moduleName][$value] = $init[$value];
                }else
                    $settings[$moduleName][$value] = NULL;
            }
        }


        if($changes)
            self::saveSettings($settings);



    }

    private static function saveSettings($settings){

        $context =& get_instance();

        foreach ($settings as $key => $value){
            if(!$context->appcore->isRegistred($key)){
                unset($settings[$key]);
            }
        }

        $userData =  $context->session->user;
        if(!empty($userData)>0) {
            $app_id = intval($userData['app_id']);
        }else
            $app_id = 0;

        if ($context->db->table_exists("setting") ){
            $context->db->where("app_id",$app_id);
            $context->db->update("setting",array(
                "setting"   => json_encode($settings,JSON_FORCE_OBJECT)
            ));
        }

        self::$loadSettings = $settings;

    }

    public static function setSetting($moduleName, $key, $value){

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $settings = self::getAppSetting(TRUE);
        $settings[$moduleName][$key] = $value;

        self::saveSettings($settings);
    }



    public static function getSetting($moduleName,$key){

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $settings = self::getAppSetting();
        if(isset($settings[$moduleName][$key])){
           return $settings[$moduleName][$key];
        }

        return NULL;
    }

    public static function registerItem($moduleName,$value){

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $settings = self::getAppSetting();
        $changes = FALSE;
        if(!isset($settings[$moduleName][$value])){
            $changes = TRUE;
            $settings[$moduleName][$value] = NULL;
        }

        if($changes)
            self::saveSettings($settings);

    }


    public static function getAppSetting($reload=FALSE){


        if(empty(self::$loadSettings) || $reload==TRUE){

            $context =& get_instance();
            $userData =  $context->session->user;
            if(!empty($userData)>0) {
                $app_id = intval($userData['app_id']);
            }else
                $app_id = 0;


            if ($context->db->table_exists("setting") ){

                $context->db->where("app_id",$app_id);
                $s = $context->db->get("setting");
                $s = $s->result_array();

                if(count($s)>0){
                    self::$loadSettings =  json_decode($s[0]['setting'],JSON_OBJECT_AS_ARRAY);
                    return self::$loadSettings ;
                }
            }

            return self::$loadSettings ;


        }else
            return self::$loadSettings;



        return array();
    }

}
