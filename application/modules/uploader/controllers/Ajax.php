<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Ajax extends AJAX_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("uploader/uploader_model");
        $this->load->model("user/user_model");
        $this->load->model("user/user_browser");

    }


    public function uploadImage(){

        $Upoader = new UploaderHelper($_FILES['addimage']);

        $r = $Upoader->start();

        if(empty($Upoader->getErrors())){

            $id = $r['image'];
            $type = $r['type'];

            $user_id = intval($this->user_browser->getData("id_user"));

            if($user_id==0){
                $user_id = 1;
            }

            $this->db->insert('image',array(
                "image"     =>  $id,
                "type"      =>  $type,
                "user_id"      =>  $user_id,
            ));
        }

        echo json_encode(array("errors"=>$Upoader->getErrors(),"results"=>$r));
        exit();

    }

}

/* End of file UploaderDB.php */