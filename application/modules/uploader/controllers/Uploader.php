<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Uploader extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("uploader_model");
        $this->load->library('session');
    }



}

/* End of file UploaderDB.php */