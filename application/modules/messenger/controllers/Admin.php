<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Admin extends ADMIN_Controller {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("messenger/messenger_model","mMessengerModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }

	public function index()
	{

	}

    public function messages(){


        if (!GroupAccess::isGranted('messenger'))
            redirect("error?page=permission");


        $list = Modules::run("messenger/ajax/getMessages",array(
            "username"      => trim($this->input->get("username")),
            "page"          => 1,
            "lastMessageId" => 0
        ));

        //parse to message view
        if(isset($list[Tags::SUCCESS]) AND $list[Tags::SUCCESS]==1 && count($list[Tags::RESULT])>0){

            $data['messages_views']         = Modules::run("messenger/ajax/getMessagesViews",$list[Tags::RESULT]);
            $data['messages_pagination']    = $list["pagination"];
            $data['lastMessageId']          = $list["lastMessageId"];
            $data['messengerData']          = $list[Tags::RESULT];

        }else{
            $data['messages_views'] = "";
        }


        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/discussions");
        $this->load->view("backend/footer");


    }


}

/* End of file MessengerDB.php */