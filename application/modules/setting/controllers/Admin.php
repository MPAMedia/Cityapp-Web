<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Admin extends ADMIN_Controller {

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

        /*
        *  CHECK USER PEMISSIONS
        */

        if (!GroupAccess::isGranted('setting',MANAGE_CURRENCIES))
            redirect("error?page=permission");


        TemplateManager::set_settingActive('currencies');

        $data['currencies'] = $this->mCurrencyModel->getAllCurrencies();
        $data['config'] = $this->mConfigModel->getParams();

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/currency");
        $this->load->view("backend/footer");


    }

    public function deeplinking(){

        /*
        *  CHECK USER PEMISSIONS
        */

        if (!GroupAccess::isGranted('setting'))
            redirect("error?page=permission");


        TemplateManager::set_settingActive('deeplinking');


        $data = array();

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/deeplinking");
        $this->load->view("backend/footer");

    }

    public function application(){

        /*
        *  CHECK USER PEMISSIONS
        */

        if (!GroupAccess::isGranted('setting',CHANGE_APP_SETTING))
            redirect("error?page=permission");

        TemplateManager::set_settingActive('application');

        $data['config'] = $this->mConfigModel->getParams();

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/config");
        $this->load->view("backend/footer");


    }

    public function app_config_xml(){

        if (!GroupAccess::isGranted('setting'))
            redirect("error?page=permission");

        $this->load->view("backend/header");
        $this->load->view("backend/application/app_config_xml");
        $this->load->view("backend/footer");

    }



}

/* End of file SettingDB.php */