<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */
class Appcore extends MX_Controller
{

    public static $modulesExist = array();

    public function getRegistredModules()
    {
        return FModuleLoader::getRegistredModules();
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("fmoduleloader");
        //load model
        $this->load->model("AppcoreModel", "mAppCore");
        $this->load->model("ModulesModel", "mModules");


        //helpers
        $this->load->helper("moduleauthorizations");
        $this->load->helper("moduleschecker");
        $this->load->helper("modulesettings");
        $this->load->helper("modulemanager");
        $this->load->helper("actionsmanager");

        //$this->load();

        FModuleLoader::init();
    }

    public function load()
    {

        $this->load->helper("fmoduleloader");
        $this->loadModulesFromDir();

        //load core modules

        $modulesCore = FModuleLoader::loadCoreModules();
        foreach ($modulesCore as $module) {
            $this->load->module($module);
        }

        //load External modules
        //$modulesC = FModuleLoader::loadCoreModules();
        $modulesE = FModuleLoader::loadExternalModules();

        foreach ($modulesE as $module) {
            $this->load->module($module);
        }

        //install & upgrade if needed
        FModuleLoader::commit();


        //init module checker
        ModulesChecker::init();

    }

    public function currentActive($context)
    {

        $moduleName = strtolower(get_class($context));
        $uri = strtolower($this->uri->segment(1));
        if ($moduleName == $uri) {
            return TRUE;
        }
        return FALSE;
    }

    /*public function register($context, $options)
    {

        $moduleName = strtolower(get_class($context));


        if (!isset($options['order']) and $options['order'] != 0) {

            $keys = array_keys(self::$registredModules);
            $last_key = end($keys);
            $last_key = ($last_key + 1);


            $this->fetchModules();
            throw new Exception('You should register module (' . $moduleName . ') with order (Try to add this number <b>"' . $last_key . '"</b> as order )');
            exit();

        }

        if (isset(self::$registredModules[$options['order']]) and $options['order'] != 0) {

            $keys = array_keys(self::$registredModules);
            $last_key = end($keys);
            $last_key = ($last_key + 1);

            $this->fetchModules();
            throw new Exception('Module name: <b>"' . $moduleName . '"</b> already defined by same order ' . $options['order'] . '  (Try to add this number <b>"' . $last_key . '"</b> as order )');
            exit();

        }

        $opt = array();
        $opt['module_name'] = $moduleName;
        $opt['version_code'] = $options['version_code'];
        $opt['version_name'] = $options['version_name'];
        $opt['order'] = $options['order'];

        //refresh module in database
        $this->mAppCore->refreshModules($opt);

        //put it in array
        //check module is found with requirements
        if (isset($options['requirement'])) {
            foreach ($options['requirement'] as $value) {
                if (!$this->moduleIsExist($value)) {

                    throw new Exception("Module <b>\"" . $moduleName . "\"</b> needs \"<b>"
                        . $value . "\"</b> module");
                    exit();
                }
            }

        }

        //check is registred and registred
        if (!$this->isRegistred($moduleName)) {

            self::$registredModules[$opt['order']] = array();
            self::$registredModules[$opt['order']]['module_name'] = $moduleName;

            if (isset($options['menu']) AND $options['menu'] != NULL)
                self::$registredModules[$opt['order']]['menu'] = $options['menu'];

            if (isset($options['setting_menu']) AND $options['setting_menu'] != NULL)
                self::$registredModules[$opt['order']]['setting_menu'] = $options['setting_menu'];

            ksort(self::$registredModules);
        }

    }
*/
    public function isRegistred($moduleName)
    {

        foreach (FModuleLoader::getRegistredModules() as $key => $module) {
            if ($module['module_name'] == $moduleName) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function moduleIsExist($moduleName)
    {

        if (in_array($moduleName, self::$modulesExist)) {
            return TRUE;
        }

        return FALSE;
    }

    public function loadModulesFromDir()
    {

        $external = FModuleLoader::loadExternalModules();
        $core = FModuleLoader::loadCoreModules();

        foreach ($external as $value)
            self::$modulesExist[] = $value;

        foreach ($core as $value)
            self::$modulesExist[] = $value;

    }

    public function getSettingsMenu()
    {
        //sort items
        $views = "";

        //sort

        //loop
        /*
         *
         */

        foreach (FModuleLoader::getRegistredModules() as $key => $item) {
            if (isset($item['setting_menu']) and $item['setting_menu'] != NULL) {
                $views .= $this->load->view($item['module_name'] . '/' . $item['setting_menu'], NULL, FALSE);
            }

        }

        return $views;
    }


    public function getMenusWithView()
    {
        //sort items
        $views = "";

        //sort

        //loop
        foreach (FModuleLoader::getRegistredModules() as $key => $item) {
            if (isset($item['menu']) and $item['menu'] != NULL)
                $views .= $this->load->view($item['module_name'] . '/' . $item['menu'], NULL, FALSE);

            // $views .= $this->load->view($item['module_name'].'/sidebar',NULL,FALSE);
        }

        return $views;
    }

    private function fetchModules()
    {

        foreach (FModuleLoader::getRegistredModules() as $key => $value) {
            echo $key . '=>' . $value['module_name'] . "<br>";
        }
    }


    public function getModules()
    {
        return $this->mAppCore->getModules();
    }

    public function enableModule($moduleName, $app_id, $bol)
    {
        return $this->mAppCore->enableModule($moduleName, $app_id, $bol);
    }

    //enabled for specific app by SaaS
    public function isEnabledInApp($moduleName, $app_id)
    {

        return TRUE;
        if (!is_string($moduleName))
            $moduleName = strtolower(get_class($moduleName));

        //check is enabled or disabled global
        if ($this->isEnabled($moduleName)) {
            //check is enabled or disabled with specific app
            return $this->mAppCore->isEnabledByApp($moduleName, $app_id);
        }
        return FALSE;
    }


    //enabled by admin of app
    public function isEnabledInUserApp($moduleName, $app_id)
    {

        return TRUE;
        if (!is_string($moduleName))
            $moduleName = strtolower(get_class($moduleName));

        //check is enabled or disabled global
        if ($this->isEnabled($moduleName)) {
            //check is enabled or disabled with specific app
            if ($this->mAppCore->isEnabledByApp($moduleName, $app_id)) {
                return $this->mAppCore->isEnabledByUserApp($moduleName, $app_id);
            };
        }
        return FALSE;
    }

    //enabled for all apps by SaaS
    public function isEnabled($moduleName)
    {
        return $this->mAppCore->isEnabled($moduleName);
    }


    /*
     * CALL BACK REGISTER
     */
}

/* End of file AppcoreDB.php */