<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Offer extends MY_Controller {

    public function __construct(){
        parent::__construct();

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>3
        );
        $this->appcore->register($this,$data);

        //load model
        $this->load->model("offer/offer_model","mOfferModel");


    }

	public function index()
	{

	}

    public function dp()
    {
        redirect(site_url(""));
    }


    public function id(){
        $this->load->library('user_agent');

        $id = intval($this->uri->segment(3));

        if($id==0)
            redirect("?err=1");

        $platform =  $this->agent->platform();

        if(/*Checker::user_agent_exist($user_agent,"ios")*/ strtolower($platform)=="ios"){

            $link = site_url("offer/id/$id");
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

/* End of file OfferDB.php */