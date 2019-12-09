<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Api extends API_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("category/category_model","mCategoryModel");
        $this->load->model("user/user_model", "mUserModel");
        $this->load->model("user/user_browser", "mUserBrowser");

    }

    public function getCategories(){

        $latitude = doubleval($this->input->post("latitude"));
        $longitude = doubleval($this->input->post("longitude"));

        $data = $this->mCategoryModel->getCategories(array(
            "latitude" => $latitude,
            "longitude" => $longitude,
        ));

        if($data[Tags::SUCCESS]==1){
            $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
            $data[Tags::RESULT] = Text::groupTranslate($data[Tags::RESULT]);

            foreach ($data[Tags::RESULT] as $key => $offer){

                $data[Tags::RESULT][$key]['image'] = _openDir($data[Tags::RESULT][$key]['image']);

            }

            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array());
        }else{
            echo json_encode($data);
        }

    }

}

/* End of file CategoryDB.php */