<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */
class Admin extends MY_Controller
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

        if ($this->mUserBrowser->isLogged()) {

            /*
            *  CHECK USER PEMISSIONS
            */
            $authType = $this->mUserBrowser->getData("typeAuth");
            if ($authType != "admin") {
                redirect(admin_url("error404"));
                return;
            }


            $uri = $this->uri->segment(3);


            $data['data'] = $this->mCategoryModel->getByCategory();
            $this->load->view("backend/header", $data);

            $idc = intval($this->input->get("id"));
            $data2['dataToEdit'] = $this->mCategoryModel->getByCategory($idc);
            if (isset($data2['dataToEdit']['cats'][0])) {
                $data2['dataToEdit'] = $data2['dataToEdit']['cats'][0];
                $this->load->view("backend/html/edit", $data2);
            } else {
                redirect(admin_url("error404"));
            }
            $this->load->view("backend/footer");


        } else {
            redirect(admin_url("user/login"));
        }

    }


    public function categories()
    {

        if ($this->mUserBrowser->isLogged()) {

            /*
            *  CHECK USER PEMISSIONS
            */
            $authType = $this->mUserBrowser->getData("typeAuth");
            if ($authType != "admin") {
                redirect(admin_url("error404"));
                return;
            }


            $uri = $this->uri->segment(3);


            $data['data'] = $this->mCategoryModel->getByCategory();
            $this->load->view("backend/header", $data);

            $this->load->view("backend/html/add");

            $this->load->view("backend/footer");


        } else {
            redirect(admin_url("user/login"));
        }

    }

}

/* End of file CategoryDB.php */