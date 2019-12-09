<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Admin extends MY_Controller   {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
    }

	public function index()
	{


	}


	public function shadowing(){

        if($this->mUserBrowser->isLogged() && $this->mUserBrowser->getData("typeAuth")=="admin"){
            $id = $this->input->get("id");

            if(defined("DEMO") and DEMO==TRUE){
                $id = 3;
            }

            $admin = $this->mUserModel->getUserData($id);

            if(isset($admin['typeAuth']) and $admin['typeAuth']=='admin' && $admin['manager']==1){
                redirect(admin_url());return;
            }

            $re = $this->mUserBrowser->shadowing_mode($id);
            if($re)
                redirect(admin_url());
        }else{
            redirect(admin_url("error404"));
        }

    }

    public function close_shadowing(){

        if($this->mUserBrowser->isLogged() && $this->mUserBrowser->isShadowing()){

            $re = $this->mUserBrowser->close_shadowing_mode();
            if($re)
                redirect(admin_url());
        }else{
            redirect(admin_url("error404"));
        }

    }


    public function logout()
    {

        if($this->mUserBrowser->isLogged()){

            $this->mUserBrowser->LogOut();
            redirect(admin_url("user/login"));

        }else{
            redirect(admin_url("user/login"));
        }

    }


    public function fpassword(){

        
        // print_r($this->router->routes);
        $this->load->view("backend/html/fpassword");

    }

    public function rpassword(){

        $this->load->view("backend/html/rpassword");

    }

    public function signup(){


        if(USER_REGISTRATION==FALSE){
            redirect(admin_url("login"));
            return;
        }




        if($this->mUserBrowser->isLogged()){
            redirect(admin_url(""));
        }else{
            $this->load->view("backend/html/signup");
        }




    }


    public function login(){

       // $this->doUpdate();

        if($this->mUserBrowser->isLogged()){
            redirect(admin_url(""));
        }else
            $this->load->view("backend/html/login");

    }


    public function users()
    {

        if($this->mUserBrowser->isLogged()){

            if( $this->mUserBrowser->getData("typeAuth") =="admin" ){

                $params = array(
                    "page"  => $this->input->get("page"),
                    "id"  => $this->input->get("id"),
                    "search"  => $this->input->get("search"),
                    'limit' => NO_OF_ITEMS_PER_PAGE,
                    "is_super"=> TRUE,
                    "user_id"  => $this->mUserBrowser->getData("id_user")
                );

                $data['data'] = $this->mUserModel->getUsers($params);

                $this->load->view("backend/header",$data);
                $this->load->view("backend/html/users");
                $this->load->view("backend/footer");

            }else{
                redirect(admin_url("page/error404"));
            }

        }else{
             redirect(admin_url("user/login"));
        }

    }


    public function edit()
    {
        if($this->mUserBrowser->isLogged()){

            $id = intval($this->input->get("id"));
            $data['dataUser'] = $this->mUserModel->userDetail($id);

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/edit");
            $this->load->view("backend/footer");


        }else{
            redirect(admin_url("user/login"));
        }
    }



    public function add()
    {
        if($this->mUserBrowser->isLogged()){

            $this->load->view("backend/header");
            $this->load->view("backend/html/add");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("user/login"));
        }
    }




}

/* End of file UserDB.php */