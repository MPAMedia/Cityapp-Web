<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Api extends API_Controller {

    public function __construct(){
        parent::__construct();

        //load model
        $this->load->model("setting/Setting_model","mSettingModel");
        $this->load->model("setting/Config_model","mConfigModel");
        $this->load->model("setting/Admin_model","mAdminModel");

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }


    public function app_initialization(){
        echo json_encode(array(Tags::SUCCESS=>1,"token"=>CRYPTO_KEY));
    }

    public function saveLogs(){

        $data = $this->input->post('data');

        $d = @file_get_contents('mobile_logs.html');
        $date = date("Y-m-d H:i:s",time());
        @file_put_contents('mobile_logs.html',$date.' : '.$data."<br>------<br>".$d);

    }


}

/* End of file SettingDB.php */