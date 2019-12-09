<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messenger_model extends CI_Model {

    const SENDER_TEXT_VIEW = 0;
    const SENDER_IMAGE_VIEW = -10;
    const RECEIVER_OBJECT_VIEW = -20;
    const RECEIVER_TEXT_VIEW = 1;

    const MESSAGE_OBJECT_IMAGE = -100;
    const MESSAGES_LIMIT = 30;

    public function delete_discussion($id){

        $this->db->where("id_discussion",$id);
        $this->db->delete("discussion");

        $this->db->where("discussion_id",$id);
        $this->db->delete("message");

        return TRUE;
    }

    public function deleteAllForUser($user_id){

        $user_id = intval($user_id);

        if($user_id>0){

            $this->db->where("sender_id",$user_id);
            $this->db->or_where("receiver_id",$user_id);
            $this->db->delete("discussion");

            $this->db->where("sender_id",$user_id);
            $this->db->or_where("receiver_id",$user_id);
            $this->db->delete("message");

            return TRUE;
        }

        return FALSE;
    }

    public function countMessagesNoSeen($user_id=0){


        if($user_id>0){

            $this->db->where("message.receiver_id",$user_id);
            $this->db->where("message.status",-1);
            $this->db->where("discussion.status >=",0);
            $this->db->join("discussion","discussion.id_discussion=message.discussion_id");
            $count = $this->db->count_all_results("message");

            return array(
                Tags::SUCCESS=>1,
                Tags::COUNT=>$count
            );
        }

        return array(Tags::SUCCESS=>0);
    }

    private function messageHasImage($message=array()){

        $json = json_decode($message['content'],JSON_OBJECT_AS_ARRAY);

        if(isset($json['images'])){

            if(!is_array($json['images'])){
                $images = json_decode($json['images'],JSON_OBJECT_AS_ARRAY);

                if(is_string($images))
                    $images = json_decode($json['images'],JSON_OBJECT_AS_ARRAY);
            }else{
                $images  = (array)$json['images'];
            }

            if(count($images)>0)
                return TRUE;
        }

        return FALSE;
    }

    private function getMessageImages($data=array()){

        $json = json_decode($data['content'],JSON_OBJECT_AS_ARRAY);

        if($json['images']!="")
            while (is_string($json['images'])){
                $json['images'] = json_decode($json['images'],JSON_OBJECT_AS_ARRAY);
                if(is_array($json['images'])){
                    break;
                }
            }

        if(isset($json['images'])){
            $images = $json['images'];
            $json['images'] =array();

            if(!is_string($images))
                foreach ($images AS $image){
                    $json['images'][] = _openDir($image);
                }

            $data['content'] = $json;
        }

        return $data;

    }

    private function prepareUserData($data=array(),$userId=0){

        if(!empty($data)){

            $this->load->model("user/user_model");

            foreach ($data AS $key => $value){

                if($userId==$value['sender_id']){
                    $senderId = $value['receiver_id'];

                }else{
                    $senderId = $value['sender_id'];
                }

                $userData =  $this->mUserModel->syncUser(array(
                    "user_id"  =>$senderId,
                    "recieverId"=>intval($userId),
                    "matchedId"=>$userId,
                ));

                $data[$key]['sender'] = json_encode($userData,JSON_FORCE_OBJECT);

            }

        }

        return $data;
    }

    public function getDiscussionId($user_uid1,$user_uid2){

        $this->db->where("( sender_id='$user_uid1' AND receiver_id='$user_uid2' ) OR 
        ( sender_id='$user_uid2' AND receiver_id='$user_uid1' ) ",null,FALSE);

        $this->db->where("status >=",0);

        $discussion = $this->db->get("discussion",1);
        $discussion = $discussion->result();

        if(count($discussion)>0){
            return $discussion[0]->id_discussion;
        }

        return 0;

    }

    public function loadDiscussion($params=array()){

        $errors = array();
        extract($params);


        if(!isset($limit))
            $limit = 16;

        if(!isset($page) ){
            $page = 0;
        }


        if(isset($sender_id) && $sender_id>0) {

            $this->load->model("appcore/bundle");
            if (isset($sender_id))
                $blockedIs = $this->bundle->getBlockedId($sender_id);
            else
                $blockedIs = array();


            if(!empty($blockedIs)){
                $this->db->where(" (receiver_id  NOT IN ".$this->bundle->inArrayClauseWhere($blockedIs). " 
                AND  sender_id  NOT IN
                ".$this->bundle->inArrayClauseWhere($blockedIs)." ) " ,NULL,FALSE);
            }


            $this->db->where("( sender_id='$sender_id' OR receiver_id='$sender_id' )",null,FALSE);
            $this->db->where("status >=",0);

            $this->db->from("discussion");
            $count = $this->db->count_all_results();


            $pagination = new Pagination();
            $pagination->setCount($count);
            $pagination->setCurrent_page($page);
            $pagination->setPer_page($limit);
            $pagination->calcul();



            if(!empty($blockedIs)){
                $this->db->where(" (receiver_id  NOT IN ".$this->bundle->inArrayClauseWhere($blockedIs). " 
                AND  sender_id  NOT IN
                ".$this->bundle->inArrayClauseWhere($blockedIs)." ) " ,NULL,FALSE);
            }



            $this->db->where("status >=",0);
            $this->db->where("( sender_id='$sender_id' OR receiver_id='$sender_id' )",null,FALSE);

            $this->db->order_by("created_at","DESC");
            $this->db->from("discussion");

            $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());
            $discussion = $this->db->get();
            $discussion = $discussion->result_array();

            if(count($discussion)>0){

                foreach ($discussion as $key => $value) {


                    //get last message
                    $this->db->where("discussion_id", $value['id_discussion']);
                    // $this->db->where("( status=-1 OR status=-2)", null, FALSE);
                    //$this->db->where("receiver_id",$sender_id);
                    $this->db->order_by("created_at", "DESC");
                    $messages = $this->db->get("message", 1);
                    $messages = $messages->result_array();

                    $this->db->where("discussion_id", $value['id_discussion']);
                    $this->db->where("status <=",0);
                    $this->db->where("receiver_id",$sender_id);
                    $nbrMessageNotSeen = $this->db->count_all_results("message");

                    //print_r($sender_id);

                    if (count($messages)>0) {

                        foreach ($messages as $mkey => $msg){
                            $messages[$mkey] = $this->getMessageImages($msg);
                        }

                        $discussion[$key]['messages'] = json_encode(array(Tags::SUCCESS => 1,
                            Tags::RESULT => $messages), JSON_FORCE_OBJECT);

                        $discussion[$key]['nbrMessageNotSeen'] = $nbrMessageNotSeen;

                    }else{

                        $this->db->where("discussion_id", $value['id_discussion']);
                        //$this->db->where("receiver_id",$sender_id);
                        //$this->db->order_by("status", "0");

                        $this->db->order_by("id_message", "DESC");
                        $message = $this->db->get("message", 1);

                        $messages = $message->result_array();

                        foreach ($messages as $mkey => $msg){
                            $messages[$mkey] = $this->getMessageImages($msg);
                        }

                        $discussion[$key]['messages'] = json_encode(array(Tags::SUCCESS => 1,
                            Tags::RESULT => $messages), JSON_FORCE_OBJECT);

                    }

                }


                $discussion  = $this->prepareUserData($discussion,$sender_id);

            }


            return array(Tags::SUCCESS=>1, Tags::COUNT=>$count, Tags::RESULT=>$discussion,"pagination"=>$pagination);

        }

        return array(Tags::SUCCESS=>1,Tags::RESULT=>array(),Tags::COUNT=>0);
    }


    public function loadMessages($params=array()){

        $errors = array();
        extract($params);

        //user_id
        //status

        if(!isset($page))
            $page = 1;

        if( (isset($discussion_id) OR isset($user_id))
            AND isset($status) && $status>=-1
            AND isset($receiver_id)){


            if($discussion_id>0)
                $this->db->where("discussion_id",$discussion_id);
            else{
                $this->db->where("
                ( sender_id=$user_id AND receiver_id=$receiver_id) 
                OR 
                 ( sender_id=$receiver_id AND receiver_id=$user_id)
                ",null,FALSE);
            }


            try{
                if(isset($lastMessageId) and $lastMessageId>0){
                    if($status>=0)
                        $this->db->where("id_message <",$lastMessageId);
                    else{
                        $this->db->where("id_message >",$lastMessageId);
                    }


                }

            }catch (Exception $e){

            }


            if($status!=0)
                $this->db->where("status",$status);

            $count = $this->db->count_all_results("message");

            $pagination = new Pagination();
            $pagination->setCount($count);
            $pagination->setCurrent_page($page);
            $pagination->setPer_page(30);
            $pagination->calcul();

            if($discussion_id>0)
                $this->db->where("discussion_id",$discussion_id);
            else
                $this->db->where("
                ( sender_id=$user_id AND receiver_id=$receiver_id) 
                OR 
                 ( sender_id=$receiver_id AND receiver_id=$user_id)
                ",null,FALSE);

            try{
                if(isset($lastMessageId) and $lastMessageId>0){
                    if($status>=0)
                        $this->db->where("id_message <",$lastMessageId);
                    else
                        $this->db->where("id_message >",$lastMessageId);
                }

            }catch (Exception $e){

            }

            if($status!=0)
                $this->db->where("status",$status);


            $this->db->order_by("id_message","DESC");

            $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());
            $mesages = $this->db->get("message");
            $mesages = $mesages->result_array();



            $lastMessageId = 0;
            if(count($mesages)>0){
                $lastMessageId = $mesages[0]['id_message'];
            }

            return array(Tags::SUCCESS=>1,"pagination"=>$pagination,"lastMessageId"=> $lastMessageId, Tags::COUNT=>$count,  Tags::RESULT=>$mesages);


        }else if(isset($receiver_id) and $receiver_id>0 and isset($status) and $status>=-2){


            $this->db->where("receiver_id",$receiver_id);

            if($status!=0)
                $this->db->where("status",$status);


            $this->db->order_by("id_message","DESC");
            $mesages = $this->db->get("message");
            $mesages = $mesages->result_array();

            return array(Tags::SUCCESS=>1,"pagination"=>NULL,  Tags::COUNT=>count($mesages),  Tags::RESULT=>$mesages);


        }


        return array(Tags::SUCCESS=>0);
    }



    public function sendMessage($params=array()){

        $errors = array();
        extract($params);


        if(isset($sender_id) && $sender_id>0
            && isset($receiver_id)
            && $receiver_id>0
            && isset($content)
            && $content!=""){


            $messages = $this->getMessageUser($sender_id,$receiver_id);

            $this->load->model("user/user_model");
            //create new discussion
            if(count($messages)==0){

                $date_sent  = MyDateUtils::convert(  date("Y-m-d H:i:s",time())  ,TIME_ZONE,"UTC");

                $discussion = array(
                    "sender_id"         => $sender_id,
                    "receiver_id"      => $receiver_id,
                    "created_at"        => $date_sent,
                    "status"            => 0
                );

                $this->db->insert("discussion",$discussion);
                $discussion_id = $this->db->insert_id();

            }else{

                $discussion_id = $messages[0]->discussion_id;
                $date_sent  = MyDateUtils::convert(  date("Y-m-d H:i:s",time())  ,TIME_ZONE,"UTC");

                $discussion = array(
                    "status"            => 1,
                    "created_at"        =>  $date_sent
                );

                $this->db->where("id_discussion",$messages[0]->discussion_id);
                $this->db->update("discussion",$discussion);

            }

            //store message with own discussion

            $date_sent  = MyDateUtils::convert(  date("Y-m-d H:i:s",time())  ,TIME_ZONE,"UTC");

            $message = array(
                "sender_id" => $sender_id,
                "receiver_id" => $receiver_id,
                "discussion_id" => $discussion_id,
                "content" => $content,
                "created_at" => $date_sent,
                "status" => -1
            );



            $this->db->insert("message",$message);
            $id = $this->db->insert_id();

            $this->db->where("id_message",$id);
            $this->db->order_by("created_at","ASC");
            $messages = $this->db->get('message');
            $messages = $messages->result_array();

            foreach ($messages as $mkey => $message){
                // $messages[$mkey] = $this->getMessageImages($message);

                //if($this->messageHasImage($message))
                // $messages[$mkey]['type'] = Self::MESSAGE_OBJECT_IMAGE;
                // else
                $messages[$mkey]['type'] = Self::SENDER_TEXT_VIEW;

                // print_r($messages[$mkey]);

                $messages[$mkey]['content'] = Text::output( $messages[$mkey]['content'] );
            }


            $messageContent = array(Tags::SUCCESS=>1,  Tags::RESULT=>$messages);

            if(isset($messageId))
                $messageContent['messageId'] = $messageId;


            if(CHAT_WITH_FIREBASE==TRUE){

                $this->load->model("user/user_model");
                $this->load->model("notification/notification_model");
                $fcm = $this->user_model->getFCM($receiver_id);

                if(!empty($fcm)){

                    $params = array(
                        "regIds" => $fcm['fcm'],
                        "body" => array(
                            "type"  => "notification",
                            "data"  => json_encode($messageContent,JSON_FORCE_OBJECT)
                        ),
                    );
                    $this->notification_model->send_notification($fcm['platform'],$params);
                }

            }


            return $messageContent;
        }





        return array(Tags::SUCCESS=>0);

    }


    public function markMessagesAsSeen($params=array())
    {
        $errors = array();
        extract($params);

        if(isset($user_id) and isset($discussionId) and $discussionId>0){

            $this->db->where("discussion_id",intval($discussionId));
            $this->db->where("receiver_id",intval($user_id));
            $this->db->where("status <=",0);

            $this->db->update("message",array(
                "status"    => 1
            ));

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }


    public function markMessagesAsLoaded($params=array())
    {

        extract($params);


        if(isset($user_id) and isset($discussionId) and $discussionId>0){

            $this->db->where("discussion_id",intval($discussionId));
            $this->db->where("receiver_id",intval($user_id));
            $this->db->where("status",-1);

            $this->db->update("message",array(
                "status"    => 0
            ));

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }

    public function inboxLoaded($params=array()){
        $errors = array();
        extract($params);

        $mIds = array();

        $allSeen = FALSE;
        if(isset($messagesIds) AND intval($messagesIds)==-1){
            $allSeen = TRUE;


        }else if($messagesIds!=""){

            $messagesIds = json_decode($messagesIds);
            if(!empty($messagesIds)){
                $mIds = $messagesIds;
            }
        }

        $dIds = array();
        if(isset($discussionIds) AND $discussionIds!=""){

            if(!is_array($discussionIds))
                $discussionIds = json_decode($discussionIds);
            if(!empty($discussionIds)){
                $dIds = $discussionIds;
            }
        }

        if(isset($user_id) and $user_id>0 and !empty($mIds)){

            $s = 0;
            if(isset($status) AND $status==-2){
                $s = -1;
            }

            foreach ($mIds AS $id){

                $this->db->where("receiver_id",$user_id);
                $this->db->where("id_message",$id);
                $this->db->update("message",array(
                    "status"=>$s
                ));

            }

            if(!empty($dIds)){

                foreach ($dIds AS $id){

                    $this->db->where("id_discussion",$id);
                    $this->db->update("discussion",array(
                        "status"=>0
                    ));

                }
            }


            return array(Tags::SUCCESS=>1);

        }else if($allSeen==TRUE){

            $this->db->where("receiver_id",$user_id);
            $this->db->update("message",array(
                "status"=>0
            ));

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);

    }

    public function loadInbox($params=array()){

        $errors = array();
        extract($params);

        //user_id
        //status

        if(isset($user_id) && $user_id>0){


            $this->db->where("receiver_id",$user_id);
            $this->db->where("status >=",0);

            $discussions = $this->db->get("discussion");
            $discussions = $discussions->result_array();

            $this->load->model("user/user_model");
            foreach ($discussions AS $key => $value){

                //get sender and receiver data
                $discussions[$key]['sender'] = json_encode($this->mUserModel->syncUser(
                    array(
                        "user_id"=>$value['sender_id'],
                        "matchedId"=>$user_id,
                    )
                ),JSON_FORCE_OBJECT);

                //get all messages for discussion
                $this->db->where("discussion_id",$value["id_discussion"]);
                $this->db->where("status",$status);
                $messages = $this->db->get("message");
                $messages = $messages->result_array();

                $discussions[$key]['messages'] = json_encode(array(Tags::RESULT=>$messages),JSON_FORCE_OBJECT);

            }

            return array(Tags::SUCCESS=>1,  Tags::RESULT=>$discussions,  Tags::COUNT=>count($discussions));

        }

        return array(Tags::SUCCESS=>0);
    }


    public function register($params=array()) {
        // include config

        $errors = array();
        extract($params);

        if(!isset($regId) and $regId==""){
            $errors['registerId'] = Translate::sprint(Messages::REGISTER_ID_ERRORS);
        }


        if(!isset($name) and $name==""){
            $errors['name'] = Translate::sprint(Messages::Name_ERROR);
        }

        if(!isset($email) and $email==""){
            $errors['email'] = "email errors";
        }

        if(empty($errors)){

            $this->db->where("email",$email);
            $count = $this->db->count_all_results("messengers");

            if($count==0){
                $this->db->insert("messengers",array(
                    "name"     => $name,
                    "email"     => $email,
                    "regId"     => $regId
                ));
            }else{

                $this->db->where("email",$email);
                $this->db->update("messengers",array(
                    "regId"     => $regId
                ));
            }


            $this->send_notification(array("regIds"=>$regId));
        }else{



            return array("success"=>0,"errors"=>$errors);
        }



    }

    private function getMessageUser($sender_id, $receiver_id)
    {

        $this->db->where(" ( (message.sender_id=$sender_id AND message.receiver_id=$receiver_id  ) OR  (message.sender_id=$receiver_id AND message.receiver_id=$sender_id  ) )",null,FALSE);

        $messages = $this->db->get("message",1);
        $messages = $messages->result();

        return $messages;

    }



    public function pushNotification($params=array()) {
        // include config

        $errors = array();
        extract($params);

        if(!isset($regIds) and $regIds=="" && !isset($data)){
            $errors['registerId'] =  Translate::sprint(Messages::REGISTER_ID_ERRORS);
        }




        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => $regIds,
            'data' => $this->getMessage($data),
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields,JSON_FORCE_OBJECT));

        // Execute post
        $result = curl_exec($ch);


        if ($result === FALSE) {

            return array("Curl failed"=>curl_error($ch));

        }

        return array(Tags::SUCCESS=>1,"result"=>$result);
    }

    private function getMessage($data){


        return array(
            "notification"  => $data
        );

    }


}
