<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends API_Controller  {



    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("campaign/campaign_model","mCampaignModel");


        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("event/event_model","mEventModel");
        $this->load->model("store/store_model","mStoreModel");



    }


    public function markView(){

        $campaignId = Security::decrypt($this->input->post("cid"));

        $params = array(
            "campaignId"  => intval($campaignId),
        );

        echo json_encode($this->mCampaignModel->markView($params));

    }


    public function markReceive(){

        $campaignId = Security::decrypt($this->input->post("cid"));

        $params = array(
            "campaignId"  => intval($campaignId),
        );

        echo json_encode($this->mCampaignModel->markReceive($params));

    }

    



}