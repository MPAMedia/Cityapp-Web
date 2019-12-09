<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaign_model extends CI_Model {

    private $limit = 15;
    private $types = array("offer","store","event");


    public function testPush($type="store",$ids=""){

        $object_exist = FALSE;
        $object_id = 0;

        $gids = array();

        if($ids!=""){
            if(preg_match("#,#",$ids)){
                $gids = explode(",",$ids);
            }else{
                $gids = array(intval($ids));
            }
        }



        if($type=="store"){

            //get object
            $this->db->order_by("id_store","DESC");
            $object = $this->db->get("store",1);
            $object = $object->result();

            if(count($object)>0){
                $object_exist = TRUE;
                $object_id = $object[0]->id_store;
            }

        }else if($type=="offer"){

            //get object
            $this->db->order_by("id_offer","DESC");
            $object = $this->db->get("offer",1);
            $object = $object->result();

            if(count($object)>0){
                $object_exist = TRUE;
                $object_id = $object[0]->id_offer;
            }

        }else if($type=="event"){

            //get object
            $this->db->order_by("id_event","DESC");
            $object = $this->db->get("event",1);
            $object = $object->result();

            if(count($object)>0){
                $object_exist = TRUE;
                $object_id = $object[0]->id_event;
            }

        }



        if($object_exist){

            //get guests
            $this->db->order_by("id","DESC");

            if(!empty($gids)){
                $this->db->where_in("id",$gids);
                $guests = $this->db->get("guest",count($gids));
            }else{
                $guests = $this->db->get("guest",10);
            }



            $guests = $guests->result_array();

            //check and create campaign
            $campaign = array(
                "type" => $type,
                "name" => "test push",
                "seen" => 0,
                "received" => 0,
                "estimation" => count($guests),
                "int_id" => $object_id,
                "user_id" => $this->mUserBrowser->getData("id_user"),
                "status" => 1,
                "date_created" => date("Y-m-d",time()),
            );

            $this->db->insert("campaign",$campaign);
            $insert_id  = $this->db->insert_id();

            $pushed = FALSE;

            foreach ($guests as $guest){
                $this->pushSingleCampaignToSingleUser(array(
                    "campaign_id"   =>$insert_id,
                    "fcm"           => $guest['fcm_id'],
                    "guest_id"           => $guest['id'],
                ));
                $pushed = TRUE;
            }


        }


        if($object_exist==FALSE){
            $msg = "There is no inserted item in the database";
        }elseif($pushed==TRUE){
            $msg = "DONE!";
        }else{
            $msg = "couldn't run the Test,There is no guests registered in the database, Please re-install the app in your Device !";
        }

        return array(Tags::SUCCESS=>1,Tags::RESULT=>$msg);
    }

    public function validateAndPushCampaign($id){

        if($id>0){

            $this->db->where("id",intval($id));
            $this->db->where("status",-1);
            $campaign = $this->db->get("campaign");
            $campaign = $campaign->result_array();

            if(count($campaign)>0){

                $campaign[0]['id_campaign']=$campaign[0]['id'];
                $this->pushCampaign($campaign[0]);

                $this->db->where("id",intval($id));
                $this->db->where("status",-1);
                $this->db->update("campaign",array(
                    "status"    =>1
                ));
            }

        }

    }

    public function getPendingCampaigns($params=array()){
        extract($params);

        $this->db->where("status",-1);
        $c=  $this->db->count_all_results("campaign");

        return $c;
    }

    public function markView($params=array()){

        extract($params);

        if(isset($campaignId) and $campaignId>0){

            $this->db->where("id",$campaignId);
            $campaign = $this->db->get("campaign",1);
            $campaign = $campaign->result_array();

            if(count($campaign)>0){
                $this->db->where("id",$campaignId);
                $t  = ($campaign[0]["seen"]+1);


                $dataToUpdate = array(
                    "seen"  =>  $t
                );
                if( $t== $campaign[0]["estimation"])
                    $dataToUpdate['status'] = 2;
                $this->db->update("campaign",$dataToUpdate);

                return array(Tags::SUCCESS=>1);
            }

        }


        return array(Tags::SUCCESS=>0,"s"=>$params);

    }

    public function markReceive($params=array()){

        extract($params);

        if(isset($campaignId) and $campaignId>0){

            $this->db->where("id",$campaignId);
            $campaign = $this->db->get("campaign",1);
            $campaign = $campaign->result_array();

            if(count($campaign)>0){

                $this->db->where("id",$campaignId);
                $t  = ($campaign[0]["seen"]+1);

                $dataToUpdate = array(
                    "received"  =>  $t
                );
                $this->db->update("campaign",$dataToUpdate);

                return array(Tags::SUCCESS=>1);
            }

        }


        return array(Tags::SUCCESS=>0,"s"=>$params);

    }

    public function getEstimation($params=array()){

        extract($params);

        $nbr = 0;


        if(isset($id))
            $id = $id;

        if(isset($user_id))
            $user_id = $user_id;

        if(isset($type))
            $type = $type;

        $lat = 0;
        $lng = 0;

        if($type=="store"){

            $this->db->select("latitude,longitude");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){
                $lat = $obj[0]->latitude;
                $lng = $obj[0]->longitude;
            }

        }else if($type=="event"){

            $this->db->select("lat,lng");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){

                $lat = $obj[0]->lat;
                $lng = $obj[0]->lng;
            }

        }else if($type=="offer"){

            $this->db->select("store_id");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){

                $store_id = $obj[0]->store_id;
                $this->db->select("latitude,longitude");
                $this->db->where("id_store",$store_id);
                $obj = $this->db->get("store",1);
                $obj = $obj->result();
                if(count($obj)>0){
                    $lat = $obj[0]->latitude;
                    $lng = $obj[0]->longitude;
                }

            }
        }

        if($lat!=0 && $lng!=0){

            $this->load->model("User/mUserModel");
            $data =  $this->mUserModel->getGuests(array(
                'lat' => $lat,
                'lng' => $lng,
                'limit' => LIMIT_PUSHED_GUESTS_PER_CAMPAIGN,
            ));

            if($data[Tags::SUCCESS]==1)
                foreach ($data[Tags::RESULT] as $p){
                    if( (RADUIS_TRAGET*1024) > $p['distance']){
                        $nbr++;
                    }else{
                        break;
                    }
                }
        }



        return array(Tags::SUCCESS=>1,Tags::RESULT=>$nbr);

    }

    public function getCampaigns($params=array())
    {

        extract($params);
        $errors = array();
        $data = array();


        if(!isset($page)){
            $page = 1;
        }

        if( isset($type) and in_array($type,$this->types)){
            $data ['type'] = $type;
        }else{
        }

        if( isset($user_id) and $user_id>0){
            $data ['user_id'] = intval($user_id);
        }

        if( isset($int_id) and $int_id>0){
            $data ['int_id'] = intval($int_id);
        }

        if( isset($campaign_id) and $campaign_id>0){
            $data ['id'] = intval($campaign_id);
        }


        if( isset($status) and ($status>=1 or $status<0)){
            $data ['status'] = intval($status);
        }else if($status==0){
            $data ['status >='] = -1;
        }




        $this->db->where($data);

        $this->db->from("campaign");
        $count = $this->db->count_all_results();






        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($this->limit);
        $pagination->calcul();


        $this->db->where($data);





        $this->db->from("campaign");
        $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());


        $this->db->group_by("id");
        $this->db->order_by("id","DESC");

        $stores = $this->db->get();
        $offers = $stores->result_array();




        return array(Tags::SUCCESS=>1,"pagination"=>$pagination,  Tags::COUNT=>$count,  Tags::RESULT=>$offers);
    }


    public function createCampaign($params=array()){

        extract($params);

        $errors = array();
        $data = array();


        if(isset($type) and in_array($type,$this->types)){
            $data['type'] = $type;
        }else{
            $errors['type'] = Translate::sprint(Messages::TYPE_NOT_VALID);;
        }


        if(isset($name) and $name!=""){
            $data['name'] = Text::input($name);
        }else{
            $errors['name'] = Translate::sprint(Messages::CAMPAIGN_NAME_IS_EMPTY);
        }

        if(isset($int_id) and $int_id>0){
            $data['int_id'] = intval($int_id);
        }else{
            $errors['int_id'] = Translate::sprint(Messages::SOMETHING_WRONG_12);
        }


        if( isset($user_id) and $user_id>0){
            $data['user_id'] = $user_id;
        }else{
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }

        if(empty($errors)){

            $this->db->where("user_id",$data['user_id']);
            $this->db->where("id_".$type,$data['int_id'] );
            $count = $this->db->count_all_results($type);

            if($count==0){
                $errors['int_id'] = Translate::sprint(Messages::SOMETHING_WRONG_13);
            }

        }

        if(isset($t) and $t>0){
            $data['estimation'] =intval($t);
        }


        $data['date_created'] = date("Y-m-d",time());




        if(empty($errors) and  isset($user_id) and $user_id>0){


            $this->db->where("user_id",$user_id);
            $user_setting = $this->db->get("setting");
            $user_setting = $user_setting->result();




            if(count($user_setting)>0){



                $user_setting = $user_setting[0];
                $nbr_campaign_monthly = $user_setting->nbr_campaign_monthly;
                $push_campaign_auto = $user_setting->push_campaign_auto;
                $push_campaign_auto =PUSH_COMPAIGN_AUTO;


                if($nbr_campaign_monthly>0 || $nbr_campaign_monthly==-1){



                    if($push_campaign_auto==TRUE)
                        $data['status'] = 1;
                    else
                        $data['status'] = -1;


                    $this->db->insert("campaign",$data);
                    $id = $this->db->insert_id();
                    $data['id_campaign'] = $id;

                    if($nbr_campaign_monthly>0){
                        $this->db->where("user_id",$user_id);
                        $this->db->update("setting",array(
                            "nbr_campaign_monthly" => ($nbr_campaign_monthly-1)
                        ));
                    }



                    //push campaign
                    if($push_campaign_auto==TRUE){
                        $this->pushCampaign($data);
                    }


                    return array(Tags::SUCCESS=>1);

                }else{
                    $errors["offers"] = Translate::sprint(Messages::EXCEEDED_MAX_NBR_CAMPAIGNS);
                }

            }

        }else{
            $errors['store'] = Translate::sprint(Messages::SOMETHING_WRONG_12);;
        }

        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);

    }


    public function archiveCampaign($params=array())
    {

        extract($params);
        $errors = array();
        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");

        if(isset($campaign_id) and $campaign_id>0 && $user_id>0){

            $this->db->where("user_id",$user_id);
            $this->db->where("id",$campaign_id);
            $this->db->update("campaign",array(
                "status"    => -2
            ));

            $this->db->where("campaign_id",$campaign_id);
            $this->db->delete("pending_campaigns");

            return array(Tags::SUCCESS=>1);

        }

        return array(Tags::SUCCESS=>0);
    }


    public function duplicateCampaign($params=array())
    {

        extract($params);
        $errors = array();
        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");

        if(isset($campaign_id) and $campaign_id>0 && $user_id>0){

            $this->db->where("user_id",$user_id);
            $this->db->where("id",$campaign_id);
            $campaign = $this->db->get("campaign",1);
            $campaign = $campaign->result_array();


            if(count($campaign)>0){



                $campaign[0]['t'] = $campaign[0]['estimation'];
                return $this->createCampaign($campaign[0]);
            }
        }

        return array(Tags::SUCCESS=>0);
    }



    public function getEstimatedGuests($params=array()){

        extract($params);

        $pending = array();


        if(isset($id))
            $id = $id;

        if(isset($user_id))
            $user_id = $user_id;

        if(isset($type))
            $type = $type;


        if(isset($id_campaign)){
            $id_campaign = $id_campaign;
        }

        $lat = 0;
        $lng = 0;

        if($type=="store"){

            $this->db->select("latitude,longitude");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){
                $lat = $obj[0]->latitude;
                $lng = $obj[0]->longitude;
            }

        }else if($type=="event"){

            $this->db->select("lat,lng");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){

                $lat = $obj[0]->lat;
                $lng = $obj[0]->lng;
            }

        }else if($type=="offer"){

            $this->db->select("store_id");
            $this->db->where("id_".$type,$id);
            $this->db->where("user_id",$user_id);
            $obj = $this->db->get($type,1);
            $obj = $obj->result();

            if(count($obj)>0){

                $store_id = $obj[0]->store_id;
                $this->db->select("latitude,longitude");
                $this->db->where("id_store",$store_id);
                $obj = $this->db->get("store",1);
                $obj = $obj->result();
                if(count($obj)>0){
                    $lat = $obj[0]->latitude;
                    $lng = $obj[0]->longitude;
                }

            }
        }

        if($lat!=0 && $lng!=0){

            $this->load->model("User/mUserModel");
            $data =  $this->mUserModel->getGuests(array(
                'lat' => $lat,
                'lng' => $lng,
                'limit' => LIMIT_PUSHED_GUESTS_PER_CAMPAIGN,
                'order' => 'date'
            ));

            if($data[Tags::SUCCESS]==1){
                $i = 0;
                foreach ($data[Tags::RESULT] as $p){
                    if( (RADUIS_TRAGET*1024) > $p['distance']){
                        $pending[$i]['fcm'] = $p['fcm_id'];
                        $pending[$i]['guest_id'] = $p['id'];
                        $pending[$i]['sender_id'] = $p['sender_id'];
                        $pending[$i]['campaign_id'] = $id_campaign;
                        $i++;
                    }else{
                        break;
                    }
                }
            }

        }





        return $pending;

    }


    private function pushCampaign($data){

        $fcms = $this->getEstimatedGuests(array(
            "id_campaign" => $data['id_campaign'],
            "type"         => $data['type'],
            "user_id"         => $data['user_id'],
            "id"         => $data['int_id'],
        ));

        if(PUSH_CAMPAIGNS_WITH_CRON==TRUE){
            //insert into table pending cronjon executed
            foreach ($fcms as $value){
                $value['date_created'] = date("Y-m-d",time());
                $this->db->insert("pending_campaigns",$value);
            }
        }else{


            //push right now
            foreach ($fcms as $guest){
                $resultJson = $this->pushSingleCampaignToSingleUser(array(
                    "campaign_id"   =>$data['id_campaign'],
                    "fcm"           => $guest['fcm'],
                    "guest_id"           => $guest['guest_id'],
                ));



                if($resultJson!=NULL){
                    $result = json_decode($resultJson,JSON_OBJECT_AS_ARRAY);
                    //delete invalid guest
                    if(isset($result["results"]) and isset($result["results"][0]["error"])
                        and $result["results"][0]["error"]=="NotRegistered") {
                        $this->db->where("id",$guest['guest_id']);
                        $this->db->delete("guest");
                    }
                }

            }

        }
    }


    public function pushPendingCampaigns(){

        $currentDate = date("Y-m-d H:i:s",time()) ;
        $currentDate = MyDateUtils::convert($currentDate,TIME_ZONE,"UTC","Y-m-d H:i:s");

        //$this->db->where('push_at <=',  $currentDate  );
        $this->db->where('failed',  0  );
        $pendings = $this->db->get("pending_campaigns",NBR_PUSHS_FOR_EVERY_TIME);
        $pendings = $pendings->result_array();

        $this->load->model("notification/notification_model");

        $logs = "";
        foreach ($pendings as $campaign){

            $resultJson = $this->pushSingleCampaignToSingleUser($campaign);
            $result = json_decode($resultJson,JSON_OBJECT_AS_ARRAY);


            if(isset($result["failure"]) and $result["failure"]==0){
                $this->db->where("id",$campaign['id']);
                $this->db->delete("pending_campaigns");
                $log =  "Guest ID: ".$campaign['guest_id']." => <b style='color:green'>Pushed</b> => ".$resultJson."<br>";
            }else{

                $this->db->where("id",$campaign['id']);
                $this->db->update("pending_campaigns",array(
                    "logs"  => json_encode($result),
                    "failed"  => 1
                ));
                $log = "Guest ID: ".$campaign['guest_id']." => <b style='color:red'>Not Pushed</b> => $resultJson<br>";


                //delete invalid guest
                if(isset($result["results"]) and isset($result["results"][0]["error"])
                    and $result["results"][0]["error"]=="NotRegistered") {
                    $this->db->where("id",$campaign['guest_id']);
                    $this->db->delete("guest");
                }

            }

            echo $log;
            $logs = $logs.$log;

        }

        echo "Pushed to ".count($pendings)." guests at ".$currentDate."<br>";

    }

    private function getFCM($geuest_id){

        $this->db->select("fcm_id,platform");
        $this->db->where("id",$geuest_id);
        $guest = $this->db->get("guest",1);
        $guest = $guest->result();

        foreach ($guest as $value){
            return $value;
            break;
        }

        return NULL;
    }

    private function pushSingleCampaignToSingleUser($campaign){

        $this->load->model("notification/notification_model");
        $campData = $this->getDataFromCampaign($campaign['campaign_id']);


        $platform = "";
        if($campData){

            $fcm = $this->getFCM($campaign['guest_id']);


            if($fcm!=NULL){

                $params = array(
                    "regIds" => $fcm->fcm_id,
                    "body" => array(
                        "type"  => "campaign",
                        "data"  =>$campData
                    ),
                );
                return $this->notification_model->send_notification($fcm->platform,$params);
            }

        }

        return ;
    }

    private function getDataFromCampaign($cid){

        $data = array(
            "title" => "",
            "body"  => "",
            "id"    => "",
            "image" => "",
            "type"  => ""
        );



        $this->db->where("id",$cid);
        $campaign = $this->db->get("campaign",1);
        $campaign = $campaign->result_array();

        if(count($campaign)==0)
            return FALSE;


        $type = $campaign[0]['type'];
        $int_id = $campaign[0]['int_id'];

        if($type=="store"){

            $this->db->where("id_store",$int_id);
            $this->db->where("status",1);
            $obj = $this->db->get("store",1);
            $obj = $obj->result_array();

            if(count($obj)>0){

                $data['title'] = Text::output($campaign[0]['name']);
                $data['sub-title'] = Text::output($obj[0]['name']);
                $data['id'] = $int_id;
                $data['type'] = $type;
                $data['image'] = $this->getFirstImage( $obj[0]['images']);

            }

        }else if($type=="event"){

            $this->db->where("id_event",$int_id);
            $this->db->where("status",1);
            $obj = $this->db->get("event",1);
            $obj = $obj->result_array();

            if(count($obj)>0){

                $data['title'] =  Text::output($campaign[0]['name']);
                $data['sub-title'] = Text::output($obj[0]['name']);
                $data['id'] = $int_id;
                $data['type'] = $type;
                $data['image'] = $this->getFirstImage( $obj[0]['images']);

            }

        }else if($type=="offer"){

            $this->db->where("id_offer",$int_id);
            $this->db->where("status",1);
            $offer = $this->db->get("offer",1);
            $offer = $offer->result_array();

            if(count($offer)>0){

                $str_id = $offer[0]['store_id'];

                $this->db->where("id_store",$str_id);
                $this->db->where("status",1);
                $obj = $this->db->get("store",1);
                $obj = $obj->result_array();

                if(count($obj)>0){

                    $data['title'] = Text::output($campaign[0]['name']);
                    $data['sub-title'] = Text::output($offer[0]['name']);
                    $data['id'] = $int_id;
                    $data['type'] = $type;

                    $content = json_decode($offer[0]["content"],JSON_OBJECT_AS_ARRAY);
                    $content['currency'] = DEFAULT_CURRENCY;
                    $content['attachment'] =  $this->getImage( $offer[0]['image']);
                    $content['store_name'] =  $obj[0]['name'];

                    $data['body'] = $content;
                    $data['image'] = $this->getFirstImage( $obj[0]['images']);

                }

            }


        }

        $data["cid"] = $cid;

        return $data;
    }


    private function getImage($dir){

        $images = _openDir($dir);
        if(isset($images['200_200']['url'])){
            return $images['200_200']['url'];
        }

        return "";
    }

    private function getFirstImage($images){

        $images  = json_decode($images,JSON_OBJECT_AS_ARRAY);

        if(isset($images[0])){
            $images = _openDir($images[0]);
            if(isset($images['200_200']['url'])){
                return $images['200_200']['url'];
            }
        }

        return "";
    }


}