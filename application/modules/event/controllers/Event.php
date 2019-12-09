<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Event extends MY_Controller {

    public function __construct(){
        parent::__construct();

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>4
        );
        $this->appcore->register($this,$data);

        //load model
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("event/event_model","mEventModel");

    }

    public function index(){

    }

    public function id(){
        $this->load->library('user_agent');

        $id = intval($this->uri->segment(3));

        if($id==0)
            redirect("?err=1");

        $platform =  $this->agent->platform();

        if(/*Checker::user_agent_exist($user_agent,"ios")*/ strtolower($platform)=="ios"){

            $link = site_url("event/id/$id");
            $link = str_replace('www.', '', $link);
            $link = str_replace('http://', 'nsapp://', $link);
            $link = str_replace('https://', 'nsapp://', $link);

            $this->session->set_userdata(array(
                "redirect_to" =>  $link
            ));

            redirect("");
        }

        redirect("");

    }

}

/* End of file EventDB.php */