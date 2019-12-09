<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */

class ADMIN_Controller extends MY_Controller   {

    public function __construct()
    {
        parent::__construct();
        $this->load->module('user');
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));exit();
        }

        GroupAccess::initGrant();
    }


    public function cron(){

    }

}
