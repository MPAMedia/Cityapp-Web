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
    }


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
    }


}