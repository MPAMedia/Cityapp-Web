<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */
class Admin extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();

        //load model
        $this->load->model("category/category_model", "mCategoryModel");
        $this->load->model("user/user_model", "mUserModel");
        $this->load->model("user/user_browser", "mUserBrowser");


    }


    public function edit()
    {

        /*
        *  CHECK USER PEMISSIONS
        */

        if (!GroupAccess::isGranted('category', EDIT_CATEGORY))
            redirect("error?page=permission");


        $data['data'] = $this->mCategoryModel->getByCategory();
        $this->load->view("backend/header", $data);

        $idc = intval($this->input->get("id"));
        $data2['category'] = $this->mCategoryModel->getByCategory($idc);
        if (isset($data2['category']['cats'][0])) {
            $data2['category'] = $data2['category']['cats'][0];
            $this->load->view("backend/html/edit", $data2);
        } else {
            redirect(admin_url("error404?s"));
        }
        $this->load->view("backend/footer");


    }


    public function categories()
    {

        /*
        *  CHECK USER PEMISSIONS
        */

        if (!GroupAccess::isGranted('category', ADD_CATEGORY))
            redirect("error?page=permission");


        $data['data'] = $this->mCategoryModel->getByCategory();
        $this->load->view("backend/header", $data);
        $this->load->view("backend/html/add");
        $this->load->view("backend/footer");

    }

}

/* End of file CategoryDB.php */