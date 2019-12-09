<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Admin extends MY_Controller     {

    public function __construct(){
        parent::__construct();


        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("category/category_model","mCategoryModel");
        $this->load->model("category/Place_model","mPlaceModel");

    }

    public function index(){
        echo "we are in admin";
    }


    public function reviews(){

        $data['data'] = $this->mStoreModel->getReviews();
        $data['store'] =  $this->myStores();

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/reviews");
        $this->load->view("backend/footer");

    }

    public function edit(){

        if($this->mUserBrowser->isLogged()){

            $data['dataStores'] = $this->myStores();

           $data['store'] =  $this->myStores();
          


            if($data['dataStores'][Tags::SUCCESS]==0){
                redirect(admin_url("error404"));
            }
        
            $data['categories'] = $this->mCategoryModel->getCategories();
            $data['places'] = $this->mPlaceModel->getPlaces();

            if(ModulesChecker::isRegistred("gallery"))
                $data['gallery'] = $this->mGalleryModel->getGallery(array(
                    "limit"     => $this->mGalleryModel->maxfiles,
                    "type"      => "store",
                    "int_id"    => $data['dataStores'][Tags::RESULT][0]['id_store']
                ));

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/edit");
            $this->load->view("backend/footer");

        }else
            redirect(admin_url("user/login"));


    }


    public function create(){

        if($this->mUserBrowser->isLogged()){

            $data['categories'] = $this->mCategoryModel->getCategories();
            $data['places'] = $this->mPlaceModel->getPlaces();

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/create");
            $this->load->view("backend/footer");

        }else
            redirect(admin_url("user/login"));

    }


    public function stores(){


        if($this->mUserBrowser->isLogged()){

            $data['data'] = $this->myStores();

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/stores");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("login"));
        }


    }


    private function myStores(){

        $id_store = intval($this->input->get("id"));
        $page = intval($this->input->get("page"));
        $status = intval($this->input->get("status"));
        $search = $this->input->get("search");
        $category_id = intval($this->input->get("category_id"));
        $user_Id = intval($this->input->get("owner_id"));
        $limit = NO_OF_STORE_ITEMS_PER_PAGE;

        $user_id = $this->mUserBrowser->getAdmin("id_user");
        $typeAuth =  $this->mUserBrowser->getAdmin("typeAuth");


        $params = array(
            "limit"       =>$limit,
            "page"          =>$page,
            "search"  => $search,
            "user_id"  => $user_Id,
            "status"  => -1,
            "category_id"  => $category_id,
            "order_by"=>-3
        );



        if($typeAuth == "manager" || $status==1 ) {
            $params['user_id'] = $user_id;
        }

        if($id_store>0)
            $params['store_id'] = intval($id_store);

        return $this->mStoreModel->getStores($params );


    }


}

/* End of file StoreDB.php */