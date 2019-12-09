<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */

class API_Controller extends MY_Controller   {

    public function __construct()
    {
        parent::__construct();

        $lang = Security::decrypt($this->input->get_request_header('Language', DEFAULT_LANG));
        Translate::changeSessionLang($lang);

        if(!$this->checkTokenIsValide()){
            echo json_encode(array(Tags::SUCCESS=>-1,Tags::ERRORS=>array("Err"=>Translate::sprint("You don't have permission to server try later!"))));
            die();
        }

        GroupAccess::initGrant();

    }


    private function checkTokenIsValide(){

        $headers = $this->input->request_headers();

        if(Checker::isValid($headers))
            return TRUE;
        else
            return FALSE;


    }



}
