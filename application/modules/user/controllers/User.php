<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class User extends MY_Controller  {

    public function __construct(){
        parent::__construct();

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>5
        );
        $this->appcore->register($this,$data);

        //load model
        //$this->load->module("appcore");

        $this->load->model("user_model","mUserModel");
        $this->load->model("user_browser","mUserBrowser");

    }

	public function index()
	{


	}

    public function userConfirm(){


        $token = $this->input->get("id");
        $uid = $this->mUserModel->mailVerification($token);

        if($uid>0){

            $user_data = $this->mUserModel->syncUser(
                array(
                    "user_id"=>$uid,
                )
            );

            $user_data = $user_data[Tags::RESULT];

            if(count($user_data)>0){
                $this->mUserBrowser->setID($user_data[0]['id_user']);
                $this->mUserBrowser->setUserData($user_data[0]);
            }

        }

        redirect(admin_url("user/login"));
    }


}

/* End of file UserDB.php */