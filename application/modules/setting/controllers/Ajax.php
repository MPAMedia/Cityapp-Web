<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Ajax extends AJAX_Controller {

    public function __construct(){
        parent::__construct();

        //load model
        $this->load->model("setting/Setting_model","mSettingModel");
        $this->load->model("setting/Config_model","mConfigModel");
        $this->load->model("setting/Update_model","mUpdateModel");
        $this->load->model("setting/Admin_model","mAdminModel");

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }

    public function sverify(){

        //check version update
        $response = $this->mUpdateModel->verifyPurchaseId();
        $response = json_decode($response,JSON_OBJECT_AS_ARRAY);

        if(isset($response[Tags::SUCCESS]) and $response[Tags::SUCCESS]==0){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERROR=>$response[Tags::ERROR]));return;
        }else if(isset($response[Tags::SUCCESS]) and $response[Tags::SUCCESS]==1){
            echo json_encode($response);return;
        }else{
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERROR=>"There is some error in api server side, please try later or report it to our support"));
            return;
        }

    }

    public function update(){


        //check if needs an update
        if($this->mUpdateModel->haveUpdate() == FALSE){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERROR=>"Your dashboard is already updated to the latest version (".APP_VERSION.")"));return;
        }



        //check version update
        $response = $this->mUpdateModel->checkMyLatestVersion();
        $response = json_decode($response,JSON_OBJECT_AS_ARRAY);



        if(isset($response[Tags::SUCCESS]) and $response[Tags::SUCCESS]==0){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERROR=>$response[Tags::ERROR]));return;
        }else if(isset($response[Tags::SUCCESS]) and $response[Tags::SUCCESS]==1){

            $this->mUpdateModel->save("PURCHASE_ID",$this->mUpdateModel->getPid());
            $result = $this->mUpdateModel->updateToLatest();

            echo json_encode($result);return;
        }else{
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERROR=>"There is some error in api server side, please try later or report it to our support"));
            return;
        }


    }


    public function addNewCurrency()
    {
        //check if user have permission
        $this->checkDemoMode();

        $params = array();
        echo  json_encode($this->mConfigModel->addNewCurrency($params));
    }

    public function editCurrency()
    {

        //check if user have permission
        $this->checkDemoMode();

        echo  json_encode($this->mConfigModel->editCurrency(array()));
    }

    public function deleteCurrency()
    {
        //check if user have permission
        $this->checkDemoMode();

        echo  json_encode($this->mConfigModel->deleteCurrency(array()));
    }

    public function saveAppConfig()
    {

        //check if user have permission
        $this->checkDemoMode();

        $authType = $this->mUserBrowser->getData("typeAuth");

        if($authType=="admin" && $this->mUserBrowser->getData("manager")==1){
            $params = array();
            echo  json_encode($this->mConfigModel->saveAppConfig($params));
        }

    }



    /*public function direction(){

        $ip = $this->input->ip_address();
        echo $ip;

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=casablanca&destination=rabat&key=AIzaSyCCwTkSwY3tYl9UsLuQEBbKlCHbJk6uwyQ";
        echo file_get_contents($url);

    }*/



}

/* End of file SettingDB.php */