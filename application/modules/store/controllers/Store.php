<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine
 * Date: {date}
 * Time: {time}
 */

class Store extends MY_Controller{

    public function __construct(){
        parent::__construct();

        define('ADD_STORE','add');
        define('EDIT_STORE','edit');
        define('DELETE_STORE','delete');
        define('VALIDATE_STORES','validate_stores');
        define('KS_NBR_STORES','nbr_stores');
        define('DISPLAY_STORES_FOR_ADMIN','displayStoresForAdmin');

        /////// register module ///////
        $data = array(
            "version_code"  =>2,
            "version_name"  => "1.0.1 Beta",
            "order"     =>2
        );

        FModuleLoader::register($this,$data);

        TemplateManager::registerMenu(
            'store',
            "store/menu",
            1
        );

        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("appcore/bundle","mBundle");

        //load helpers
        $this->load->helper('store/store');
        $this->load->helper('user/user');



    }

    public function onLoaded()
    {
        parent::onLoaded(); // TODO: Change the autogenerated stub


        //Setup User Config

        $this->registerModuleActions();

        UserSettingSubscribe::set('store',array(
            'field_name' => KS_NBR_STORES,
            'field_type' => UserSettingSubscribeTypes::INT,
            'field_default_value' => -1,
            'config_key' => 'NBR_STORES',
            'field_label' => 'Stores allowed Monthly',
            'field_comment' => '',
            'field_sub_label' => '( -1 Unlimited )',
        ));


    }

    public function onCommitted()
    {
        $this->load->helper('cms/charts');


        if($this->mUserBrowser->isLogged() && GroupAccess::isGranted('store')){

            SimpleChart::add('store','chart_v1_home',function ($months){

                if(GroupAccess::isGranted('user',USER_ADMIN)){
                    return $this->mStoreModel->getStoresAnalytics($months);
                }else{
                    return $this->mStoreModel->getStoresAnalytics($months,$this->mUserBrowser->getData('id_user'));
                }

            });
        }


        $this->generateViewHomePage();

    }

    private function generateViewHomePage(){

        CMS_Display::setHTML(
            "home_v1",
            "<div class=\"row\">"
        );

        CMS_Display::set(
            "home_v1",
            "store/backend/recently_added/recently_stores_added"
        );

        CMS_Display::set(
            "home_v1",
            "store/backend/recently_added/recently_reviews_added"
        );

        CMS_Display::setHTML(
            "home_v1",
            "</div>"
        );
    }

    private function registerModuleActions(){


        GroupAccess::registerActions("store",array(
            ADD_STORE,
            EDIT_STORE,
            DELETE_STORE,
            VALIDATE_STORES,
            DISPLAY_STORES_FOR_ADMIN
        ));

    }


    public function index(){
       // $this->mStoreModel->getReviews();
    }


    public function id(){

        $this->load->library('user_agent');

        $id = intval($this->uri->segment(3));

        if($id==0)
            redirect("?err=1");

        $platform =  $this->agent->platform();

        if(/*Checker::user_agent_exist($user_agent,"ios")*/ strtolower($platform)=="ios"){

            $link = site_url("store/id/$id");
            $link = str_replace('www.', '', $link);
            $link = str_replace('http://', 'nsapp://', $link);
            $link = str_replace('https://', 'nsapp://', $link);

            $this->session->set_userdata(array(
               "redirect_to" =>  $link
            ));

            redirect("");
        }

        redirect("");

    }

    public function onUpgrade()
    {
        parent::onUpgrade(); // TODO: Change the autogenerated stub

        $this->mStoreModel->updateFields();
    }

    public function onInstall()
    {
        parent::onInstall(); // TODO: Change the autogenerated stub

        $this->mStoreModel->updateFields();
    }



    public function clear(){

        if(!DEMO)
            return;


        $start = 10000000000;

        $this->db->where('id_store >=',$start);
        $this->db->update('store',array(
            'status' => 0
        ));

    }
}
