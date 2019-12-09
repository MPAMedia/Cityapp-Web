<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */

class AJAX_Controller extends MY_Controller   {

    public function __construct()
    {
        parent::__construct();
        GroupAccess::initGrant();
    }

    public function checkDemoMode(){

        if(defined("DEMO") and DEMO==TRUE){


            $manager = $this->mUserBrowser->getData("manager");
            $authType = $this->mUserBrowser->getData("typeAuth");

            if($manager==1) {
                return;
            }else if($authType=="manager"){
                return;
            }else{
                echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                    "error"  => Translate::sprint(Messages::RESTRICT_PERMISSION_DEMO)
                )));
                exit();
            }
        }
    }



}
