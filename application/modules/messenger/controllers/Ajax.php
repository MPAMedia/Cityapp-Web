<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();


        $this->load->model("messenger/messenger_model","mMessengerModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
    }

    public function getPagination($pagination,$username){

        $html = "";

        $data['pagination']  = $pagination;
        $data['username']    = $username;
        $html = $html.$this->load->view("backend/html/discussion_pagination_view",$data,TRUE);

        return $html;
    }

    public function getDiscussionsView($list){

        $html = "";
        if(empty($list))
            $html = "<div class='no-discussion'><div><i class='mdi mdi-inbox'></i></div>".Translate::sprint("No discussions")."</div>";

        foreach ($list as $value){
            $data['data']  =$value;
            $html = $html.$this->load->view("backend/html/discussion_view",$data,TRUE);
        }


        return $html;
    }

    public function getMessagesViews($list=array()){

        $myId = $this->mUserBrowser->getData("id_user");

        $senderUserData = NULL;
        $recieverUserData = NULL;

        $html = "";


        foreach ($list as $value){

            $data['object'] = $value;

            if($value['sender_id']==$myId){ //me

                $requestData = $this->mUserModel->syncUser(array(
                    "user_id" => $value['sender_id']
                ));

                if(isset($requestData[Tags::RESULT])){
                    $senderUserData = $requestData[Tags::RESULT];
                    $data['user'] = $senderUserData[0];
                    $html = $this->load->view("backend/html/messages_views/sender_view",$data,TRUE).$html;
                }

            }else if($value['sender_id']!=$myId){ //client reciepient

                $requestData = $this->mUserModel->syncUser(array(
                    "user_id" => $value['sender_id']
                ));

                if(isset($requestData[Tags::RESULT][0])){
                    $recieverUserData = $requestData[Tags::RESULT];
                    $data['user'] =  $recieverUserData[0];
                    $html = $this->load->view("backend/html/messages_views/receiver_view",$data,TRUE).$html;
                }

            }
        }



        return $html;

    }

    public function getMessages($data=array()){

        extract($data);

        if(isset($username) and Text::checkUsernameValidate($username)){

            $user_id = $this->mUserModel->getUserIDByUsername($username);

            $my_id = $this->mUserBrowser->getData("id_user");
            $dicussionId = $this->mMessengerModel->getDiscussionId($user_id,$my_id);


            if($user_id>0){

                return $this->mMessengerModel->loadMessages(array(
                    "user_id"      => $my_id,
                    "receiver_id"    => $user_id,
                    "discussion_id"     => $dicussionId,
                    "status"        => 0,
                    "page"          => $page,
                    "lastMessageId" => $lastMessageId,
                ));

            }

        }
        return array(Tags::SUCCESS=>0);
    }

    public function getNewMessages($data=array()){

        extract($data);

        if(isset($username) and Text::checkUsernameValidate($username)){

            $user_id = $this->mUserModel->getUserIDByUsername($username);
            $my_id = $this->mUserBrowser->getData("id_user");
            $dicussionId = $this->mMessengerModel->getDiscussionId($user_id,$my_id);

            if($user_id>0){

                return $this->mMessengerModel->loadMessages(array(
                    "user_id"      => $my_id,
                    "receiver_id"    => $user_id,
                    "discussion_id"     => $dicussionId,
                    "status"        => -1,
                    "page"          => 1,
                    "lastMessageId" => $lastMessageId,
                ));

            }

        }
        return array(Tags::SUCCESS=>0);
    }

    public function delete_discussion(){

        $typeAuth = $this->mUserBrowser->getData("typeAuth");
        $id = $this->input->post("id");

        if($typeAuth=="admin"){
           if($this->mMessengerModel->delete_discussion($id)){
               echo json_encode(array(Tags::SUCCESS=>1)); return;
           }
        }

        echo json_encode(array(Tags::SUCCESS=>0)); return;

    }

    public function countMessagesNoSeen(){

        $this->load->model("messenger/messenger_model","mMessengerModel");

        $my_id = $this->mUserBrowser->getData("id_user");
        return $this->mMessengerModel->countMessagesNoSeen($my_id);

    }

    public function loadNewMessages(){

        if($this->mUserBrowser->isLogged()){

            $list =  Modules::run("messenger/ajax/getNewMessages",array(
                "username"       => trim($this->input->post("username")),
                "lastMessageId"  => intval($this->input->post("lastMessageId")),
                "date"  => ($this->input->post("date")),
            ));


            //parse to message view
            if(isset($list[Tags::SUCCESS]) AND $list[Tags::SUCCESS]==1 && count($list[Tags::RESULT])>0){

                $data['messages_views'] = Modules::run("messenger/ajax/getMessagesViews",$list[Tags::RESULT]);

                $data['messages_pagination'] = $list["pagination"];
                $data['lastMessageId'] = $list["lastMessageId"];

                echo json_encode(array(Tags::SUCCESS=>1,Tags::RESULT=>$data));
                return;

            }else{

            }

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }

    public function loadMessages(){

        if($this->mUserBrowser->isLogged()){


            $list = Modules::run("messenger/ajax/getMessages",array(
                "username"       => trim($this->input->post("username")),
                "page"           => intval($this->input->post("page")),
                "lastMessageId"  => intval($this->input->post("lastMessageId")),
                "date"  => ($this->input->post("date")),
            ));


            //parse to message view
            if(isset($list[Tags::SUCCESS]) AND $list[Tags::SUCCESS]==1 && count($list[Tags::RESULT])>0){

                $data['messages_views'] = Modules::run("messenger/ajax/getMessagesViews",$list[Tags::RESULT]);
                $data['messages_pagination'] = $list["pagination"];



                echo json_encode(array(Tags::SUCCESS=>1,Tags::RESULT=>$data));
                return;

            }else{

            }

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }

    public function markMessagesAsSeen(){

        if($this->mUserBrowser->isLogged()){

            $this->load->model("Messenger/MessengerModel","mMessengerModel");
            $user_id = $this->mUserBrowser->getData("id_user");

            $data = $this->mMessengerModel->markMessagesAsSeen(array(
                "user_id"         => $user_id,
                "discussionId"  => $this->input->post("discussionId"),
            ));
            echo json_encode($data);
            return;
        }

        echo json_encode(array(Tags::SUCCESS=>0));

    }

    public function markMessagesAsLoaded(){

        if($this->mUserBrowser->isLogged()){

            $this->load->model("Messenger/MessengerModel","mMessengerModel");
            $user_id = $this->mUserBrowser->getData("id_user");

            $data = $this->mMessengerModel->markMessagesAsLoaded(array(
                "user_id"         => $user_id,
                "discussionId"  => $this->input->post("discussionId"),
            ));
            echo json_encode($data);
            return;
        }

        echo json_encode(array(Tags::SUCCESS=>0));

    }

    public function loadInbox(){


        $user_id = $this->mUserBrowser->getData("id_user");
        $username = urldecode($this->input->post("username"));
        $params = array(
            "sender_id"     => $user_id,
            "status"        => -1,
            "page"          => $this->input->post("page"),
            "limit"          =>6
        );

        $data = $this->mMessengerModel->loadDiscussion($params);

        $data = array(
            Tags::SUCCESS=>1,
            Tags::RESULT=> array(
                "discussions_view"   => Modules::run("messenger/ajax/getDiscussionsView",$data[Tags::RESULT]),
                "pagination_view"   =>  Modules::run("messenger/ajax/getPagination",$data["pagination"],$username),
                "pagination"   =>  $data["pagination"]
            )
        );


        echo json_encode($data,JSON_FORCE_OBJECT);
        return;
    }

    public function sendMessage(){

        if($this->mUserBrowser->isLogged()){

            $result = $this->sendMessageAJAX(array(
                "username"       => trim($this->input->post("username")),
                "content"           => ($this->input->post("content"))
            ));


            echo json_encode($result);
            return;

        }

        echo json_encode(array(Tags::SUCCESS=>0));
    }


    public function sendMessageAJAX($data=array()){

        extract($data);

        if(isset($username) and Text::checkUsernameValidate($username)){

            $recipient_id = $this->mUserModel->getUserIDByUsername($username);

            if(!isset($admin_id)){
                $my_id = $this->mUserBrowser->getData("id_user");
            }else
                $my_id = $admin_id;


            $dicussionId = $this->mMessengerModel->getDiscussionId($recipient_id,$my_id);

            $token = $this->input->post("token");
            $session_token = $this->mUserBrowser->getToken("sendMessageAJAX");

            if($recipient_id>0 && $token==$session_token){

                $result =  $this->mMessengerModel->sendMessage(array(
                    "sender_id"      => $my_id,
                    "receiver_id"    => $recipient_id,
                    "discussion_id"     => $dicussionId,
                    "content"           => Text::input($content)
                ));

                return array(Tags::SUCCESS=>1,Tags::RESULT=>array(
                    "message_view"  => $this->getMessagesViews($result[Tags::RESULT]),
                    "lastMessageId"  => $result[Tags::RESULT][0]['id_message'],
                ));



            }




        }
        return array(Tags::SUCCESS=>0);
    }



}