<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 12/9/2017
 * Time: 19:11
 */


class ModuleAuthorizations{
    //no authorized
    private static $data;
    private static $utype;
    private static $loadedRolesConfig = array();

    public static function getAllOptions(){
        return self::$data;
    }

    public static function set($mName,$auths=array()){

        if(!is_string($mName))
            $mName = strtolower(get_class($mName));
        else
            $mName = ($mName);

        if(ModulesChecker::isRegistred($mName)){
            self::$data[$mName]['options'] = $auths;
            self::$data[$mName]['enabled'] = FALSE;
        }

    }

    public static function isAuthorized($context,$option=""){

        $ci =& get_instance();

        if(!is_string($context))
            $mName = strtolower(get_class($context));
        else
            $mName = $context;


        if(!ModulesChecker::isEnabled($mName)){
            return FALSE;
        }


        $user =$ci->user->getUser();
        //$app = $user->app();


        if($user->type=="super")
            return TRUE;

        if(empty(self::$loadedRolesConfig))
            self::$loadedRolesConfig = $user->role()->config();

        if(isset(self::$loadedRolesConfig[$mName]['enabled'])){
            if(self::$loadedRolesConfig[$mName]['enabled']==NULL
                OR self::$loadedRolesConfig[$mName]['enabled']==""){
                self::$loadedRolesConfig[$mName]['enabled'] = FALSE;
            }

        }


        if(isset(self::$loadedRolesConfig[$mName]) and self::$loadedRolesConfig[$mName]['enabled']){

            if($option!=""){
                $options =  self::$loadedRolesConfig[$mName]['options'];

                if(isset($options[$option])==TRUE AND $options[$option]==TRUE){
                    return TRUE;
                }else
                    return FALSE;
            }

            return TRUE;
        }
        return FALSE;
    }


}
