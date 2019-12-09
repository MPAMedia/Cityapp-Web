<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine
 * Date: {date}
 * Time: {time}
 */

class Store extends MY_Controller    {

    public function __construct(){
        parent::__construct();

        /////// register module ///////
        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>2
        );
        $this->appcore->register($this,$data);


        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("appcore/bundle","mBundle");
    }


    public function index(){
       // $this->mStoreModel->getReviews();
    }


    public function id(){

        $this->load->library('user_agent');

        $id = intval($this->uri->segment(3));

        if($id==0)
            redirect("?err=1");

        $platform =  $this->agent->platform();

        if(/*Checker::user_agent_exist($user_agent,"ios")*/ strtolower($platform)=="ios"){

            $link = site_url("store/id/$id");
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
