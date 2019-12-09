<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Admin extends MY_Controller {

    public function __construct(){
        parent::__construct();

        //load model
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("store/store_model","mStoreModel");

        $this->load->model("setting/config_model","mConfigModel");


    }

    public function home(){



        if($this->mUserBrowser->isLogged()){

            $data["stores2"] = $this->mStoreModel->recentlyAdd();
            $data["reviews2"] = $this->mStoreModel->getReviews(7);
            $data["analytics"] = $this->mUserModel->getAnalytics();

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/home");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("user/login"));
        }

    }

    public function error404(){
        $this->load->view("backend/header");
        $this->load->view("backend/error404");
        $this->load->view("backend/footer");
    }




}

/* End of file CmsDB.php */