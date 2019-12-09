<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Admin extends ADMIN_Controller     {

    public function __construct(){
        parent::__construct();


        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("category/category_model","mCategoryModel");
    }

    public function index(){

    }


    public function reviews(){


        if (!GroupAccess::isGranted('store'))
            redirect("error?page=permission");


        $data['data'] = $this->mStoreModel->getReviews();
        $data['store'] =  $this->myStores(false);

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/reviews");
        $this->load->view("backend/footer");

    }

    public function edit(){

        if (!GroupAccess::isGranted('store',EDIT_STORE))
            redirect("error?page=permission");

        $data['dataStores'] = $this->myStores(false);


        if($data['dataStores'][Tags::SUCCESS]==0){
            redirect(admin_url("error404"));
        }

        $data['categories'] = $this->mCategoryModel->getCategories();

        if(GroupAccess::isGranted('gallery')
            && ModulesChecker::isRegistred("gallery"))
            $data['gallery'] = $this->mGalleryModel->getGallery(array(
                "limit"     => $this->mGalleryModel->maxfiles,
                "type"      => "store",
                "int_id"    => $data['dataStores'][Tags::RESULT][0]['id_store']
            ));


        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/edit");
        $this->load->view("backend/footer");


    }


    public function create(){

        if (!GroupAccess::isGranted('store',ADD_STORE))
            redirect("error?page=permission");

        $data['categories'] = $this->mCategoryModel->getCategories();

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/create");
        $this->load->view("backend/footer");

    }


    public function stores(){

        if (!GroupAccess::isGranted('store'))
            redirect("error?page=permission");

        $data['data'] = $this->myStores(true);

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/stores");
        $this->load->view("backend/footer");


    }


    public function all_stores(){

        if (!GroupAccess::isGranted('user',USER_ADMIN)  )
            redirect("error?page=permission");

        $id_store = intval($this->input->get("id"));
        $page = intval($this->input->get("page"));
        $status = intval($this->input->get("status"));
        $search = $this->input->get("search");
        $category_id = intval($this->input->get("category_id"));
        $owner_id = intval($this->input->get("owner_id"));
        $limit = NO_OF_STORE_ITEMS_PER_PAGE;

        $params = array(
            "limit"       =>$limit,
            "page"          =>$page,
            "search"  => $search,
            "owner_id"  => $owner_id,
            "status"  => -1,
            "category_id"  => $category_id,
            "order_by"=>-3
        );

        $data["data"] =  $this->mStoreModel->getStores($params );


        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/stores");
        $this->load->view("backend/footer");


    }


    private function myStores($defaultUser){

        $id_store = intval($this->input->get("id"));
        $page = intval($this->input->get("page"));
        $status = intval($this->input->get("status"));
        $search = $this->input->get("search");
        $category_id = intval($this->input->get("category_id"));
        $user_Id = intval($this->input->get("owner_id"));
        $limit = NO_OF_STORE_ITEMS_PER_PAGE;

        $params = array(
            "limit"       =>$limit,
            "page"          =>$page,
            "search"  => $search,
            //"user_id"  => $user_Id,
            "status"  => -1,
            "category_id"  => $category_id,
            "order_by"=>-3
        );

        if($defaultUser) $params['user_id'] = $this->mUserBrowser->getData("id_user");
        else if($user_Id >0) $params['user_id'] = $user_Id;


        if($id_store>0) $params['store_id'] = intval($id_store);

       // echo "<pre>"; print_r($params); die();


        return $this->mStoreModel->getStores($params );


    }



    public function verify()
    {

        if ($this->mUserBrowser->isLogged()) {

            if (!GroupAccess::isGranted('store',VALIDATE_STORES))
                redirect("error?page=permission");


            $id = intval($this->input->get('id'));
            $accept = intval($this->input->get('accept'));


            $this->db->where('id_store',$id);
            $this->db->update('store',array(
                'verified' => 1,
                'status'   => $accept,
            ));


        }

        redirect(admin_url('store/all_stores'));
    }


}

/* End of file StoreDB.php */