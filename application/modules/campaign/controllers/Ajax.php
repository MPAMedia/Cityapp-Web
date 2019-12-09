<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends AJAX_Controller  {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("campaign/campaign_model","mCampaignModel");


    }


    public function testPush(){

        //check if user have permission
        $this->checkDemoMode();

        $type = $this->input->post("type");
        $guest_ids = $this->input->post("guest_ids");

        $result = $this->mCampaignModel->testPush($type,$guest_ids);

        echo json_encode($result); return;

    }

    public function archiveCampaign(){

        //check if user have permission
        $this->checkDemoMode();

        if($this->mUserBrowser->isLogged()){

            $data = $this->mCampaignModel->archiveCampaign(
                array( "campaign_id" => $this->input->get("id"))
            );

            if($data[Tags::SUCCESS]==1){
                echo json_encode($data);
            }

        }else{
            echo json_encode(array(Tags::SUCCESS=>0));
        }

    }

    public function duplicateCampaign(){

        //check if user have permission
        $this->checkDemoMode();

        if($this->mUserBrowser->isLogged()){


            $data = $this->mCampaignModel->duplicateCampaign(
                array( "campaign_id" => $this->input->get("id"))
            );

            if($data[Tags::SUCCESS]==1){
                echo json_encode($data);
            }

        }else{
            echo json_encode(array(Tags::SUCCESS=>0));
        }

    }

    public function getCampaigns($params=array()){

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

        $authType = $this->mUserBrowser->getData("typeAuth");

        if($authType=="manager" || (isset($owner) and $owner==1)){
            $params['user_id'] =  intval($this->mUserBrowser->getData("id_user"));
        }


        return $this->mCampaignModel->getCampaigns($params);

    }

    public function getEstimation(){


        $type =  $this->input->post("type");
        $int_id =  $this->input->post("int_id");
        $user_id =  intval($this->mUserBrowser->getData("id_user"));

        $params = array(
            "user_id" => $user_id,
            "id" => $int_id,
            "type" => $type,
        );

        echo json_encode(
            $this->mCampaignModel->getEstimation($params)
        );return;

    }

    public function createCampaign(){


        $type =  $this->input->post("type");
        $int_id =  $this->input->post("int_id");
        $name =  $this->input->post("name");
        $t =  $this->input->post("t");
        $user_id =  intval($this->mUserBrowser->getData("id_user"));

        $params = array(
            "user_id" => $user_id,
            "name"       => $name,
            "int_id" => $int_id,
            "t" => $t,
            "type" => $type,
        );

        echo json_encode(
            $this->mCampaignModel->createCampaign($params)
        );return;

    }

    public  function send_notification ($message,$tokens)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' =>array("message" => $message)
        );
        $headers = array(
            'Authorization:key='.Keys::PUSH_NOTIFICATION_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;

    }


}