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

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("campaign/campaign_model","mCampaignModel");


        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("event/event_model","mEventModel");
        $this->load->model("store/store_model","mStoreModel");

        if($this->mUserBrowser->getData("typeAuth") == "manager" && ENABLE_CAMPAIGNS_FOR_OWNER==FALSE){
            redirect(admin_url("cms/error404"));
        }

    }

    public function campaigns(){


        if($this->mUserBrowser->isLogged()){

            $data = array();


            $authType = $this->mUserBrowser->getData("typeAuth");
            $cid =intval($this->input->get("push"));
            if($cid>0 && $authType=="admin"){

                $this->mCampaignModel->validateAndPushCampaign($cid);

                redirect(admin_url("campaign/campaigns"));
            }


            $data['campaigns'] =  Modules::run("campaign/ajax/getCampaigns",array(
                "page" => $this->input->get("page"),
                "status" => intval($this->input->get("status")),
                "owner" => intval($this->input->get("owner")),
            ));

            $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $data["myOffers"] = $this->mOfferModel->getMyAllOffers(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $data["myEvents"] = $this->mEventModel->getMyAllEvents(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/add");
            $this->load->view("backend/footer");


        }else{
            redirect(admin_url("user/login"));
        }

    }

    public function campaign()
    {

        if($this->mUserBrowser->isLogged()){

            $this->load->view("backend/header");
            $this->load->view("backend/campaign");
            $this->load->view("backend/footer");

        }else{
            redirect(admin_url("user/login"));
        }

    }





}

/* End of file CampaignDB.php */