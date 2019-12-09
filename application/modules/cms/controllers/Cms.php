<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
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
            "order"     =>8
        );
        FModuleLoader::register($this,$data);


        TemplateManager::registerMenu(
            'cms',
            "cms/menu",
            7
        );


        //load model
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("store/store_model","mStoreModel");

        $this->load->model("setting/config_model","mConfigModel");
        $this->load->model("messenger/messenger_model","mMessengerModel");

        $this->load->helper('charts');


        //init hook
        CMS_Display::createHook("overview_chart_months");
        CMS_Display::createHook("overview_counter");
        CMS_Display::createHook("home_v1");

        //init charts
        SimpleChart::init('chart_v1_home');
        SimpleChart::init('chart_v2_home');
        SimpleChart::init('chart_v3_home');



    }


    public function error404(){

        if($this->mUserBrowser->isLogged()){
            $this->load->view("backend/header");
            $this->load->view("backend/error404");
            $this->load->view("backend/footer");
        }else{
            redirect(site_url("user/login"));
        }

    }






}

/* End of file CmsDB.php */