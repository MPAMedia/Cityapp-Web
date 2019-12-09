<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Admin extends MY_Controller {

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


        if($this->mUserBrowser->isLogged()){

            if(ENABLE_MESSAGES==TRUE){


                if( $this->mUserBrowser->getData("typeAuth") == "admin" OR (ALLOW_DASHBOARS_MESSENGER_TO_OWNERS==TRUE
                        && $this->mUserBrowser->getData("typeAuth") == "manager")){


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



                }else{
                    redirect(admin_url(""));
                }


            }else{
                redirect(admin_url(""));
            }
        }else{
            redirect(admin_url("user/login"));
        }

    }


}

/* End of file MessengerDB.php */