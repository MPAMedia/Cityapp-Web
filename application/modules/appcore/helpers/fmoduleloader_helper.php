<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 12/6/2017
 * Time: 17:14
 */


class FModuleLoader{

    private static $init = FALSE;

    private static $registredModules = array();

    //to use in the callbacks
    private static $onUpgradeList = array();
    private static $onInstallList = array();
    private static $onLoadedlList = array();


    private static $module_exists = array();

    public static function getRegistredModules(){
        return self::$registredModules;
    }

    public static function init(){

        if(self::$init==TRUE)
            return;

        $_cxt = &get_instance();

        $external = FModuleLoader::loadExternalModules();
        $core = FModuleLoader::loadCoreModules();

        foreach ($external as $value)
            self::$module_exists[] = $value;

        foreach ($core as $value)
            self::$module_exists[] = $value;


        //load & register modules
        foreach (self::$module_exists as $module){
            //instance of module
            $_cxt->load->module($module);
        }

        //commit after loading modules
        FModuleLoader::commit();

        self::$init = TRUE;

    }

    public static function register($context, $options){


        $_cxt = &get_instance();

        if(!is_string($context))
            $moduleName = strtolower(get_class($context));
        else
            $moduleName = strtolower($context);


        if (!isset($options['order']) and $options['order'] != 0) {

            $keys = array_keys(self::$registredModules);
            $last_key = end($keys);
            $last_key = ($last_key + 1);


            self::fetchModules();
            echo 'You should register module (' . $moduleName . ') with order (Try to add this number <b>"' . $last_key . '"</b> as order )';
            exit();

        }

        if (isset(self::$registredModules[$options['order']]) and $options['order'] != 0 and $moduleName!=self::$registredModules[$options['order']]['module_name']) {

            $keys = array_keys(self::$registredModules);
            $last_key = end($keys);
            $last_key = ($last_key + 1);

            self::fetchModules();
            echo 'Module name: <b>"' . $moduleName . '"</b> already defined by same order ' . $options['order'] . '  (Try to add this number <b>"' . $last_key . '"</b> as order )';
            exit();

        }

        $opt = array();
        $opt['module_name'] = $moduleName;
        $opt['version_code'] = $options['version_code'];
        $opt['version_name'] = $options['version_name'];
        $opt['order'] = $options['order'];

        //refresh module in database

        $_cxt->load->model('appcore/appcoremodel');
        $_cxt->appcoremodel->refreshModules($opt);

        //put it in array
        //check module is found with requirements
        if (isset($options['requirement'])) {
            foreach ($options['requirement'] as $value) {
                if (!self::moduleIsExists($value)) {
                    echo "Module <b>\"" . $moduleName . "\"</b> needs \"<b>"
                        . $value . "\"</b> module";
                    exit();
                }
            }

        }

        //check is registred and registred
        if (!self::isRegistred($moduleName)) {

            self::$registredModules[$opt['order']] = array();
            self::$registredModules[$opt['order']]['module_name'] = $moduleName;

            if (isset($options['menu']) AND $options['menu'] != NULL)
                self::$registredModules[$opt['order']]['menu'] = $options['menu'];

            if (isset($options['setting_menu']) AND $options['setting_menu'] != NULL)
                self::$registredModules[$opt['order']]['setting_menu'] = $options['setting_menu'];

            ksort(self::$registredModules);
        }


    }


    private static function isRegistred($moduleName)
    {

        foreach (self::$registredModules as $key => $module) {
            if ($module['module_name'] == $moduleName) {
                return TRUE;
            }
        }

        return FALSE;
    }

    private static function moduleIsExists($moduleName)
    {

        if (in_array($moduleName, self::$module_exists)) {
            return TRUE;
        }

        return FALSE;
    }


    private static function fetchModules()
    {

        foreach (self::$registredModules as $key => $value) {
            echo $key . '=>' . $value['module_name'] . "<br>";
        }
    }

    public static function onUpgrade($module){
        self::$onUpgradeList[$module] = $module;
    }

    public static function onInstall($module){
        self::$onInstallList[$module] = $module;
    }

    public static function onLoaded($module){
        self::$onLoadedlList[$module] = $module;
    }

    public static function commit(){

        $context = &get_instance();

        foreach (self::$onLoadedlList as $module){
            $context->{$module}->onLoaded();
        }

        foreach (self::$onInstallList as $module){
            $context->{$module}->onInstall();
        }

        foreach (self::$onUpgradeList as $module){
            $context->{$module}->onUpgrade();
        }

        foreach (self::$onLoadedlList as $module){
            $context->{$module}->onCommitted();
        }

    }

    public static function modulesToInstall(){
       return self::$onInstallList;
    }

    public static function modulesToUpgrade(){
        return self::$onUpgradeList;
    }

    public static function loadExternalModules(){

        $path = Path::getPath(array("modules"));
        return self::loadFromDir($path);

    }
    public static function loadCoreModules(){

        $path = Path::getPath(array("application","modules"));
        return self::loadFromDir($path);

    }



    private static function loadFromDir($path){

        $data = array();

        if(is_dir($path))
        if ($handle = opendir($path) AND $path!="") {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {

                    if(!preg_match('#^\.#',$entry) and !preg_match('#^\_#',$entry) ){
                        $data[] = $entry;
                    }

                }
            }
        }


        return $data;

    }


}
