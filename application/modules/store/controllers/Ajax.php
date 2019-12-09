<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends AJAX_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("store/store_model","mStoreModel");

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
                    $this->mStoreModel->markAsFeatured(array(
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

    public function delete()
    {
        //check if user have permission
        $this->checkDemoMode();

        $id_store = intval($this->input->post("id"));

        echo json_encode(
            $this->mStoreModel->delete($id_store)
        );return;
    }

    public function deleteReview()
    {
        //check if user have permission
        $this->checkDemoMode();
        $id = intval($this->input->post("id"));
        echo json_encode(
            $this->mStoreModel->deleteReview($id)
        );return;
    }

    public function edit()
    {

        $id_store = intval(($this->input->post("id")));
        $name = $this->input->post("name");
        $name_ar = $this->input->post("name_ar");
        $address = $this->input->post("address");
        $detail = $this->input->post("detail");
        $detail_ar = $this->input->post("detail_ar");
        $tel = $this->input->post("tel");
        $user = intval($this->input->post("id_user"));
        $category = intval($this->input->post("cat"));
        $place_id = intval($this->input->post("place_id"));
        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");
        $gallery = $this->input->post("gallery");
      

        $data = $this->mStoreModel->updateStore(array(
            "store_id"  => $id_store,
            "name"      => $name,
            "name_ar"      => $name_ar,
            "address"   => $address,
            "detail"    => $detail,
            "detail_ar"    => $detail_ar,
            "phone"     => $tel,
            "user_id"   => $this->mUserBrowser->getData("id_user"),
            "category"  => $category,
            "place_id" =>$place_id,
            "latitude"  => $lat,
            "longitude" => $lng,
            "images"    => $images,
            "gallery"    => $gallery,
            
            "typeAuth"  => $this->mUserBrowser->getData("typeAuth")

        ));

        echo json_encode($data);


    }

    public function createStore()
    {

        $name = $this->input->post("name");
        $name_ar = $this->input->post("name_ar");
        $address = $this->input->post("address");
        $detail = $this->input->post("detail");
        $detail_ar = $this->input->post("detail_ar");
        $tel = $this->input->post("tel");
        $user = Security::decrypt($this->input->post("id_user"));
        $category = intval($this->input->post("cat"));
        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");
        $gallery = $this->input->post("gallery");
        $place_id = $this->input->post("place_id");


        $data = $this->mStoreModel->createStore(array(
            "name"      => $name,
            "name_ar"      => $name_ar,

            "address"   => $address,
            "detail"    => $detail,
            "detail_ar"    => $detail_ar,
            
            "phone"     => $tel,
            "user_id"   => $this->mUserBrowser->getData("id_user"),
            "category"  => $category,
            "latitude"  => $lat,
            "longitude" => $lng,
            "images"    => $images,
            "gallery"    => $gallery,
            "place_id" =>$place_id,

            "typeAuth"  => $this->mUserBrowser->getData("typeAuth")
        ));

        echo json_encode($data);
    }


    public function status()
    {
        if($this->input->post("type") == "store") {
            $id = intval($this->input->post("id"));
            echo  $this->mStoreModel->storeAccess($id);return;
        }
    }






}