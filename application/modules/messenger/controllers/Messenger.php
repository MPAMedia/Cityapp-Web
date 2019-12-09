<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Messenger extends MY_Controller {

    public function __construct(){
        parent::__construct();

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>6
        );
        $this->appcore->register($this,$data);

        //load model

        $this->load->model("messenger/messenger_model","mMessengerModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }



}

/* End of file MessengerDB.php */