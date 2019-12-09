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

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("campaign/campaign_model","mCampaignModel");

        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("event/event_model","mEventModel");
        $this->load->model("store/store_model","mStoreModel");

    }


    public function getCampaigns($params=array()){


        if (!GroupAccess::isGranted('campaign'))
            redirect("error?page=permission");

        $vToExtract = array_key_whitelist($params, [
            'type',
            'int_id',
            'page',
            'status',
            'owner'
        ]);
        extract($vToExtract,EXTR_SKIP);

        $params = array(
            "type"  => $type,
            "int_id"  => $int_id,
            "page"  => $page,
            "status"  => $status
        );


        if(!GroupAccess::isGranted('user',USER_ADMIN) || (isset($owner) and $owner==1)){
            $params['user_id'] =  intval($this->mUserBrowser->getData("id_user"));
        }

        return $this->mCampaignModel->getCampaigns($params);

    }

    public function campaigns(){

        if (!GroupAccess::isGranted('campaign'))
            redirect("error?page=permission");

        $data = array();

        $cid =intval($this->input->get("push"));

        if($cid>0 && GroupAccess::isGranted('user',USER_ADMIN)){
            $this->mCampaignModel->validateAndPushCampaign($cid);
            redirect(admin_url("campaign/campaigns"));
        }

        $data['campaigns'] =  $this->getCampaigns(array(
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

    }

    public function campaign()
    {

        $this->load->view("backend/header");
        $this->load->view("backend/campaign");
        $this->load->view("backend/footer");

    }





}

/* End of file CampaignDB.php */