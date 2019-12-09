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
                $this->mStoreModel->markAsFeatured(array(
                    "user_id"  => $user_id,
                    "id" => $id,
                    "featured" => $featured

                ))
            );
            return;

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }

    public function delete()
    {

        if(!GroupAccess::isGranted('store',DELETE_STORE)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        //check if user have permission
        $this->checkDemoMode();

        $id_store = intval($this->input->post("id"));

        if(!GroupAccess::isGranted('user',USER_ADMIN)){
            $user_id = $this->mUserBrowser->getData('id_user');
        }else{
            $user_id = 0;
        }

        echo json_encode(
            $this->mStoreModel->delete($id_store,$user_id)
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

        if(!GroupAccess::isGranted('store',EDIT_STORE)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        $id_store = intval(($this->input->post("id")));
        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $detail = $this->input->post("detail");
        $tel = $this->input->post("tel");
        $user = intval($this->input->post("id_user"));
        $category = intval($this->input->post("cat"));
        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");
        $gallery = $this->input->post("gallery");


        $data = $this->mStoreModel->updateStore(array(
            "store_id"  => $id_store,
            "name"      => $name,
            "address"   => $address,
            "detail"    => $detail,
            "tel"     => $tel,
            "user_id"   => $this->mUserBrowser->getData("id_user"),
            "category"  => $category,
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

        if(!GroupAccess::isGranted('store',ADD_STORE)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $detail = $this->input->post("detail");
        $tel = $this->input->post("tel");
        $user = Security::decrypt($this->input->post("id_user"));
        $category = intval($this->input->post("cat"));
        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");
        $gallery = $this->input->post("gallery");

        $data = $this->mStoreModel->createStore(array(
            "name"      => $name,
            "address"   => $address,
            "detail"    => $detail,
            "phone"     => $tel,
            "user_id"   => $this->mUserBrowser->getData("id_user"),
            "category"  => $category,
            "latitude"  => $lat,
            "longitude" => $lng,
            "images"    => $images,
            "gallery"    => $gallery,
            "typeAuth"  => $this->mUserBrowser->getData("typeAuth")
        ));

        echo json_encode($data);
    }


    public function status()
    {
        $this->checkDemoMode();

        if(!GroupAccess::isGranted('store',VALIDATE_STORES)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        if($this->input->post("type") == "store") {
            $id = intval($this->input->post("id"));
            echo  $this->mStoreModel->storeAccess($id);return;
        }
    }






}