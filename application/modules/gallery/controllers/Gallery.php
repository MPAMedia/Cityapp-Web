<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Gallery extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>9
        );
        $this->appcore->register($this,$data);


        $this->load->model("gallery_model","mGalleryModel");

    }



}

/* End of file UploaderDB.php */