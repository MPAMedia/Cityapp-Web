<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Campaign extends MY_Controller {

    public function __construct(){
        parent::__construct();

        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>7
        );
        $this->appcore->register($this,$data);

        //load model
        $this->load->model("campaign/campaign_model","mCampaignModel");
        $this->load->model("notification/notification_model","mNotificationModel");



    }

    public function cronjob(){

        $this->load->model("campaign/campaign_model");
        $this->campaign_model->pushPendingCampaigns();
        echo "Cron executed!";

    }





}

/* End of file CampaignDB.php */