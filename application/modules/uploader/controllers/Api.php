<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Api extends API_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("uploader/uploader_model");
        $this->load->model("user/user_model");

    }

    //upload images



    public function uploadImage64(){



        $data = $this->uploader_model->uploadImage64($this->input->post('image'));

        $int_id = intval($this->input->post("int_id"));
        $type = ($this->input->post("type"));

        if($type=="user" and $int_id>0 AND isset($data["result"])){
            if(isset($data['result']['image'])){

                echo json_encode($this->mUserModel->updatePhotosProfile(array(
                    "image"  => $data['result']['image'],
                    "user_id"   => intval($int_id)
                )),JSON_FORCE_OBJECT);
                exit();
            }
        }
        echo json_encode($data);
    }


}

/* End of file UploaderDB.php */