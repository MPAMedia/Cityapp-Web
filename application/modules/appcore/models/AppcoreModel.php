<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class AppcoreModel extends CI_Model
{

    private static $saas_modules;
    private static $app_modules;




    public function __construct()
    {
        parent::__construct();
    }


    public function refreshModules($module){

        $moduleName = $module['module_name'];



        $this->db->where("module_name",$moduleName);
        $modules = $this->db->get("modules");
        $modules = $modules->result();

        if(count($modules)==0){

            $m['version_code'] =$module['version_code'];
            $m['version_name'] =$module['version_name'];
            $m['module_name'] =$module['module_name'];
            $m['_order']        =$module['order'];
            $m['updated_at'] = date("Y-m-d",time());
            $m['created_at'] = date("Y-m-d",time());
            $m['_enabled'] = 0;

            $this->db->insert("modules",$m);

            //do install
            FModuleLoader::onInstall($moduleName);

        }else if(count($modules)==1){

            if($modules[0]->version_code<$module['version_code']){

                $this->db->where('module_name',$moduleName);
                $m['version_code'] =$module['version_code'];
                $m['version_name'] =$module['version_name'];
                $this->db->update("modules",$m);

                //do upgrade
                FModuleLoader::onInstall($moduleName);

            }else if($modules[0]->_order!=$module['order']){

                $this->db->where("module_name",$modules[0]->module_name);

                $m['_order'] = $module['order'];
                $m['updated_at'] = date("Y-m-d",time());

                $this->db->update("modules",$m);
            }

        }

        FModuleLoader::onLoaded($moduleName);

    }



    public function isEnabled($moduleName){


        if(empty(self::$saas_modules)){
            $result = $this->db->get("modules");
            self::$saas_modules = $result->result();
        }

        if(count(self::$saas_modules)>0){

            foreach (self::$saas_modules as $m){
                if($m->module_name == strtolower($moduleName)){
                    if($m->_enabled==1)
                        return TRUE;
                }
            }

        }

        return FALSE;
    }

    public function isEnabledByApp($moduleName,$app_id){

        if($app_id==0)
            return FALSE;

        if(empty(self::$app_modules)){
            $this->db->select("modules");
            $this->db->where("id",$app_id);
            $result = $this->db->get("app");
            self::$app_modules = $result->result();
        }

        if(count(self::$app_modules)>0){
            $modules = json_decode(self::$app_modules[0]->modules,JSON_OBJECT_AS_ARRAY);
            if(isset($modules[$moduleName]['root_enabled'])){
                return $modules[$moduleName]['root_enabled'];
            }
        }

        return FALSE;
    }

    public function isEnabledByUserApp($moduleName,$app_id){

        if($app_id==0)
            return FALSE;

        if(empty(self::$app_modules)){
            $this->db->select("modules");
            $this->db->where("id",$app_id);
            $result = $this->db->get("app");
            self::$app_modules = $result->result();
        }

        if(count(self::$app_modules)>0){
            $modules = json_decode(self::$app_modules[0]->modules,JSON_OBJECT_AS_ARRAY);
            if(isset($modules[$moduleName]['app_enabled'])){
                return $modules[$moduleName]['app_enabled'];
            }
        }

        return FALSE;
    }


    public function enableModule($moduleName,$app_id,$bol){

        $this->db->select("modules");
        $this->db->where("id",$app_id);
        $result = $this->db->get("app");
        $result = $result->result();

        if(count($result)>0){
            $modules = json_decode($result[0]->modules,JSON_OBJECT_AS_ARRAY);
            $modules[$moduleName] = $bol;
        }

        $this->db->where("id",$app_id);
        $this->db->update("app",array(
            "modules"   => json_encode($modules,JSON_FORCE_OBJECT)
        ));

    }

    public function getModules(){

        $modules = array();

        $result = $this->db->get("modules");
        $result = $result->result();

        foreach ($result as $value){
            $modules[$value->module_name]['root_enabled'] = boolval ($value->_enabled);
            $modules[$value->module_name]['app_enabled'] = boolval ($value->_enabled);
        }

        return $modules;
    }


    public function startupModules(){

        $this->db->update("modules",array(
            "_enabled"  =>1
        ));

    }



}