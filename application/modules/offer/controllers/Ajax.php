<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends AJAX_Controller  {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
    }

    public function markAsFeatured(){

        //check if user have permission
        $this->checkDemoMode();

        if(!GroupAccess::isGranted('user',USER_ADMIN)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        if($this->mUserBrowser->isLogged()){

            $user_id = $this->mUserBrowser->getData("user_id");

            $id   = intval($this->input->post("id"));
            $featured   = intval($this->input->post("featured"));

            echo json_encode(
                $this->mOfferModel->markAsFeatured(array(
                    "user_id"  => $user_id,
                    "id" => $id,
                    "featured" => $featured

                ))
            );
            return;

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }

    public function delete(){

        if(!GroupAccess::isGranted('offer',DELETE_OFFER)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        if($this->mUserBrowser->isLogged()){

            $data = $this->mOfferModel->deleteOffer(
                array( "offer_id" => intval($this->input->get("id")))
            );

            echo json_encode($data);

        }else{
            echo json_encode(array(Tags::SUCCESS=>0));
        }

    }

    public function changeStatus(){

        if(!GroupAccess::isGranted('offer',VALIDATE_OFFERS)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        if($this->mUserBrowser->isLogged()){

            $data = $this->mOfferModel->changeStatus(
                array( "offer_id" => intval($this->input->get("id")))
            );

            echo json_encode($data);
            exit();

        }

    }



    public function add(){


        if(!GroupAccess::isGranted('offer',ADD_OFFER)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }


        $store_id = $this->input->post("store_id");
        $description =  $this->input->post("description",FALSE);
        $price =  $this->input->post("price");
        $percent =  $this->input->post("percent");
        $date_start =  $this->input->post("date_start");
        $date_end =  $this->input->post("date_end");
        $name =  $this->input->post("name",FALSE);
        $user_id =  intval($this->mUserBrowser->getData("id_user"));
        $authType =  ($this->mUserBrowser->getData("typeAuth"));
        $images =  $this->input->post("images");
        $currency =  $this->input->post("currency");


        $params = array(
            "store_id" => $store_id,
            "description" => $description,
            "price" => $price,
            "percent" => $percent,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "user_id" => $user_id,
            "user_type" => $authType,
            "name" => $name,
            "images" => $images,
            "currency"=> $currency,
            "typeAuth"  => $this->mUserBrowser->getData("typeAuth")
        );

        echo json_encode(
            $this->mOfferModel->addOffer($params)
        );return;

    }


    public function edit(){

        if(!GroupAccess::isGranted('offer',EDIT_OFFER)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        $store_id = $this->input->post("store_id");
        $offer_id = $this->input->post("offer_id");
        $description =  $this->input->post("description",FALSE);
        $price =  $this->input->post("price");
        $percent =  $this->input->post("percent");
        $name =  $this->input->post("name",FALSE);
        $user_id =  intval($this->mUserBrowser->getData("id_user"));
        $images =  $this->input->post("images");

        $currency =  $this->input->post("currency");
        $date_end =  $this->input->post("date_end");
        $date_start =  $this->input->post("date_start");

        $params = array(
            "store_id" => $store_id,
            "offer_id" => $offer_id,
            "description" => $description,
            "price" => $price,
            "date_end" => $date_end,
            "date_start" => $date_start,
            "percent" => $percent,
            "user_id" => $user_id,
            "images" => $images,
            "name" => $name,
            "currency"=> $currency
        );

        echo  json_encode(
            $this->mOfferModel->editOffer($params)
        );

    }




}