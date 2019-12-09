<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Admin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("event/event_model","mEventModel");
        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }

    public function events(){

        if($this->mUserBrowser->isLogged()){

            $status = intval($this->input->get("status")) ;
            $typeAuth = intval($this->input->get("typeAuth")) ;
            $page = intval($this->input->get("page"));
            $search = $this->input->get("search");
            $limit = NO_OF_STORE_ITEMS_PER_PAGE;
            $store_id  = intval($this->input->get("store_id"));



            $params = array(
                "limit"   =>$limit,
                "page"    =>$page,
                "store_id" =>$store_id,
                "search"  => $search,
                "status"  => -1
            );
//
//            if($params['status']==0)
//                $params['status'] = -1;

            if($typeAuth=="manager" && $status==1)
                $params['user_id'] = $this->mUserBrowser->getData("id_user");

            $data['data'] = $this->mEventModel->getEvents($params);


            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/events");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("user/login"));
        }

    }
    
    public function create(){


        if($this->mUserBrowser->isLogged()){

            $data = array();

            $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $this->load->view("backend/header");
            $this->load->view("backend/html/create",$data);
            $this->load->view("backend/footer");


        }else{
            redirect(admin_url("user/login"));
        }


    }



    public function edit(){


        if($this->mUserBrowser->isLogged()){


            $user_id = $this->mUserBrowser->getData("id_user");
            $typeAuth = $this->mUserBrowser->getData("typeAuth");

            $event_id = $this->input->get("id");

            if($typeAuth!="manager"){
                $data['dataEvents'] = $this->mEventModel->getEvents(array(
                    "limit"     => 1,
                    "event_id"   => $event_id,
                ));
            }else{
                $data['dataEvents'] = $this->mEventModel->getEvents(array(
                    "limit"     => 1,
                    "user_id"   => $user_id,
                ));

            }

            $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/edit");
            $this->load->view("backend/footer");


        }else{
            redirect(__ADMIN."/login");
        }
    }





}

/* End of file EventDB.php */