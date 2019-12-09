<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */
class Ajax extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("category/category_model", "mCategoryModel");

    }

    public function addCategory()
    {

        //check if user have permission
        $this->checkDemoMode();

        $cat = trim($this->input->post("cat"));
        $cat_ar = trim($this->input->post("cat_ar"));
        $image = $this->input->post("image");

        echo json_encode($this->mCategoryModel->addCategory(array(
            "cat" => $cat,
            "cat_ar"=>$cat_ar,
            "image" => $image,
        )));
        return;

    }


    public function delete()
    {

        //check if user have permission
        $this->checkDemoMode();

        $id = intval($this->input->post("id"));

        echo json_encode($this->mCategoryModel->delete(
            $id
        ));
    }


    public function editCategory()
    {

        //check if user have permission
        $this->checkDemoMode();

        $cat = trim($this->input->post("cat"));
        $cat_ar = trim($this->input->post("cat_ar"));
        $cat_id = intval(trim($this->input->post("id")));
        $image = $this->input->post("image");

        echo json_encode($this->mCategoryModel->editCategory(
            array(
                "cat" => $cat,
                "cat_ar" => $cat_ar,
                "cat_id" => $cat_id,
                "image" => $image,
            )
        ));
    }


}

/* End of file CategoryDB.php */