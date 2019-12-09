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

        if($this->mUserBrowser->isLogged()){

            $typeAuth = $this->mUserBrowser->getData("typeAuth");
            $user_id = $this->mUserBrowser->getData("user_id");

            $id   = intval($this->input->post("id"));
            $featured   = intval($this->input->post("featured"));

            if($typeAuth=="admin"){
                echo json_encode(
                    $this->mOfferModel->markAsFeatured(array(
                        "typeAuth" => $typeAuth,
                        "user_id"  => $user_id,
                        "id" => $id,
                        "featured" => $featured

                    ))
                );
                return;
            }

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }

    public function delete(){

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

        if($this->mUserBrowser->isLogged()){

            $typeAuth = $this->mUserBrowser->getData("typeAuth");

            if($typeAuth=="admin"){
                $data = $this->mOfferModel->changeStatus(
                    array( "offer_id" => intval($this->input->get("id")))
                );

                echo json_encode($data);
                exit();
            }

            echo json_encode(array(Tags::SUCCESS=>0));
            exit();

        }

    }



    public function add(){


        $store_id = $this->input->post("store_id");
        $description =  $this->input->post("description");
        $description_ar =  $this->input->post("description_ar");
        $price =  $this->input->post("price");
        $percent =  $this->input->post("percent");
        $date_start =  $this->input->post("date_start");
        $date_end =  $this->input->post("date_end");
        $name =  $this->input->post("name");
        $name_ar =  $this->input->post("name_ar");
        $user_id =  intval($this->mUserBrowser->getData("id_user"));
        $authType =  ($this->mUserBrowser->getData("typeAuth"));
        $image =  $this->input->post("image");
        $currency =  $this->input->post("currency");

        $params = array(
            "store_id" => $store_id,
            "description" => $description,
            "description_ar" => $description_ar,
            "price" => $price,
            "percent" => $percent,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "user_id" => $user_id,
            "user_type" => $authType,
            "name" => $name,
            "name_ar" => $name_ar,
            "image" => $image,
            "currency"=> $currency,
            "typeAuth"  => $this->mUserBrowser->getData("typeAuth")
        );


        echo json_encode(
            $this->mOfferModel->addOffer($params)
        );

    }


    public function edit(){


        $store_id = $this->input->post("store_id");
        $offer_id = $this->input->post("offer_id");
        $description =  $this->input->post("description");
        $description_ar =  $this->input->post("description_ar");
        $price =  $this->input->post("price");
        $percent =  $this->input->post("percent");
        $name =  $this->input->post("name");
        $name_ar =  $this->input->post("name_ar");
        $user_id =  intval($this->mUserBrowser->getData("id_user"));
        $image =  $this->input->post("image");

        $currency =  $this->input->post("currency");
        $date_end =  $this->input->post("date_end");

        $params = array(
            "store_id" => $store_id,
            "offer_id" => $offer_id,
            "description" => $description,
            "description_ar" => $description_ar,
            "price" => $price,
            "date_end" => $date_end,
            "percent" => $percent,
            "user_id" => $user_id,
            "image" => $image,
            "name" => $name,
            "name_ar" => $name_ar,
            "currency"=> $currency
        );

        echo  json_encode(
            $this->mOfferModel->editOffer($params)
        );

    }




}