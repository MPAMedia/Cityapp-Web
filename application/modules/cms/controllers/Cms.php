<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Cms extends MY_Controller {

    public function __construct(){
        parent::__construct();


        /////// register module ///////
        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>1
        );
        $this->appcore->register($this,$data);


        //load model
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("store/store_model","mStoreModel");

        $this->load->model("setting/config_model","mConfigModel");
        $this->load->model("messenger/messenger_model","mMessengerModel");


    }



    public function error404(){
        $this->load->view("backend/header");
        $this->load->view("backend/error404");
        $this->load->view("backend/footer");
    }






}

/* End of file CmsDB.php */