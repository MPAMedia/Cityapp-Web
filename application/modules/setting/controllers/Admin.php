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
        $this->load->model("setting/Setting_model","mSettingModel");
        $this->load->model("setting/Config_model","mConfigModel");
        $this->load->model("setting/Admin_model","mAdminModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");


    }

	public function index()
	{

	}

    public function app_init(){


        $data = $this->input->post("data");
        $data = base64_decode($data);
        $data = json_decode($data,JSON_OBJECT_AS_ARRAY);


        $login = $data['login'];
        $email = $data['email'];
        $password = $data['password'];
        $name = $data['name'];
        $timezone = $data['timezone'];

        $data = $this->mAdminModel->createDefaultAdmin($login,$password,$email,$name,$timezone);


        if($data==TRUE){
            echo json_encode(array(Tags::SUCCESS=>1));return;
        }else{
            echo json_encode(array(Tags::SUCCESS=>0));return;
        }


    }

    public function messagesToTranslate(){

        echo "You need navigate in the website to save all messages<br><br>";

        if(isset($_SESSION['toTranslate'])){
            foreach ($_SESSION['toTranslate'] as $key => $item) {
                echo $key.": $item<br>";
            }
        }

    }

    public function translate(){

        $uri = $this->uri->segment(3);

        if($this->mUserBrowser->isLogged()) {

            if($uri==""){

                $data['default_language'] = Translate::loadLanguageFromYmlToTranslate(
                    $this->input->get("language")
                );

                $this->load->view("backend/header",$data);
                $this->load->view("backend/application/translate");
                $this->load->view("backend/footer");

            }else if($uri=="android"){


            }

        }
    }

    public function currencies(){


        if($this->mUserBrowser->isLogged()) {

            /*
            *  CHECK USER PEMISSIONS
            */
            $authType = $this->mUserBrowser->getData("typeAuth");
            if($authType!="admin"){
                redirect(admin_url("error404"));
                return;
            }


            $params = file_get_contents("config/".PARAMS_FILE.".json");
            $params = json_decode($params,JSON_OBJECT_AS_ARRAY);
            $data['config'] = $params;

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/currency");
            $this->load->view("backend/footer");


        }
    }

    public function deeplinking(){


        if($this->mUserBrowser->isLogged()) {

            /*
            *  CHECK USER PEMISSIONS
            */
            $authType = $this->mUserBrowser->getData("typeAuth");
            if($authType!="admin"){
                redirect(admin_url("error404"));
                return;
            }

            $data = array();

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/deeplinking");
            $this->load->view("backend/footer");


        }
    }

    public function application(){

        if($this->mUserBrowser->isLogged()) {

            /*
             *  CHECK USER PEMISSIONS
             */
            $authType = $this->mUserBrowser->getData("typeAuth");

            if($authType!="admin" || ($this->mUserBrowser->getData("manager")==0 && DEMO==FALSE)){
                redirect(admin_url("error404"));
                return;
            }else{
                Checker::load();
            }

            $params = file_get_contents("config/".PARAMS_FILE.".json");
            $params = json_decode($params,JSON_OBJECT_AS_ARRAY);
            $data['config'] = $params;

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/config");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("user/login"));
        }
    }

    public function app_config_xml(){

        if($this->mUserBrowser->isLogged()) {

            /*
            *  CHECK USER PEMISSIONS
            */
            $authType = $this->mUserBrowser->getData("typeAuth");
            if($authType!="admin"){
                redirect(admin_url("error404"));
                return;
            }


            $this->load->view("backend/header");
            $this->load->view("backend/application/app_config_xml");
            $this->load->view("backend/footer");

        }
    }



}

/* End of file SettingDB.php */