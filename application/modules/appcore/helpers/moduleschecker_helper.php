<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 12/8/2017
 * Time: 21:52
 */


class ModulesChecker{

    public static function isRegistred($moduleName){

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $context = &get_instance();
        return $context->appcore->isRegistred($moduleName);
    }

    private static $loadedModules = NULL;

    public static function init(){

        if(self::$loadedModules==NULL){
            $context = &get_instance();
            $data = $context->db->get('modules');
            $data = $data->result_array();
            self::$loadedModules = $data;
        }

    }

    public static function isEnabled($moduleName){

        self::init();

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }
        if(self::$loadedModules!=NULL)
            foreach (self::$loadedModules as $module){
                if($moduleName==$module['module_name'] AND $module['_enabled']==1){
                    return TRUE;
                }
            }

        return FALSE;
    }


    /*
    public static function isEnabled($moduleName){


        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $moduleName = strtolower($moduleName);

        $context = &get_instance();

        $userData =  $context->session->user;
        if(!empty($userData)>0) {
            $app_id = intval($userData['app_id']);
        }else
            $app_id = 0;


        if($context->appcore->isRegistred($moduleName)){//module verification at the registration level by SuperAdmin

            if($context->appcore->isEnabled($moduleName)){ //module verification at the SuperAdmin level by SuperAdmin

                if($context->appcore->isEnabledInApp($moduleName,$app_id)){ //module verification at the specific app level by SuperAdmin

                    if($context->appcore->isEnabledInUserApp($moduleName,$app_id)){//module verification at the specific app level by admin of app
                        return TRUE;
                    }
                }
            }
        }


        return FALSE;
    }*/


    /*
    public static function isEnabledInApp($moduleName){

        if(!is_string($moduleName)){
            $moduleName = strtolower(get_class($moduleName));
        }

        $context = &get_instance();
        $userData =  (object)$context->session->user;
        if(!empty($userData))
            $app_id =  intval($userData->app_id);
        else
            $app_id = 0;

        if($context->appcore->isEnabled($moduleName)){
            if($context->appcore->isEnabledInApp($moduleName,$app_id)){
                if($context->appcore->isEnabledInUserApp($moduleName,$app_id)){
                    return TRUE;
                }
            }
        }

        return FALSE;
    }*/


}