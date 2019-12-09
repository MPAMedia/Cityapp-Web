<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller implements ModuleLoader {

    public function __construct()
    {
        parent::__construct();
        $this->load->module('appcore');

    }


    public function onInstall()
    {
        // TODO: Implement onInstall() method.
    }

    public function cron()
    {
        // TODO: Implement cron() method.
    }

    public function onUpgrade()
    {
        // TODO: Implement onUpgrade() method.
    }

    public function onUninstall()
    {
        // TODO: Implement onUninstall() method.
    }

    public function onEnable()
    {
        // TODO: Implement onEnable() method.
    }

    public function onDisable()
    {
        // TODO: Implement onDisable() method.
    }

    public function onLoaded()
    {
        // TODO: Implement onLoaded() method.
    }

    public function onCommitted()
    {
        // TODO: Implement onLoaded() method.
    }

    public function register()
    {
        // TODO: Implement register() method.
    }
}

interface ModuleLoader{
    public function onInstall();
    public function onUpgrade();
    public function onUninstall();
    public function onLoaded(); //call after loading
    public function onEnable();
    public function onDisable();
    public function cron();
    public function register();
}


