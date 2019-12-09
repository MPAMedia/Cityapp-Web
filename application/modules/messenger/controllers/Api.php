<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends API_Controller  {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("messenger/messenger_model","mMessengerModel");

    }

    public function loadDiscussion(){


        /*///////////////////////////////////////////////////////////////
         * //////////////////////////////////////////////////////////////
         * ncrytation data developped by amine
         *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $sender_id = intval(Security::decrypt($this->input->post("user_id")));
        $status = intval(Security::decrypt($this->input->post("status")));
        $page = intval(Security::decrypt($this->input->post("page")));

        $params = array(
            "sender_id"     =>$sender_id,
            "status"        =>$status,
            "page"          =>$page,
        );

        $data = $this->mMessengerModel->loadDiscussion($params);

        $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);


//        for($i=0;$i<400;$i++){
//            $data = $this->mMessengerModel->loadDiscussion($params);
//        }
        echo json_encode($data,JSON_FORCE_OBJECT);

    }




    public function inboxLoaded(){

        /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////



        $user_id = intval(Security::decrypt($this->input->post("user_id")));
        $messagesIds = Security::decrypt($this->input->post("messagesIds"));
        $status = Security::decrypt($this->input->post("status"));

        $this->load->model("Messenger/MessengerModel","mMessengerModel");

        $params = array(
            "user_id"  =>$user_id,
            "messagesIds"  =>$messagesIds,
            "status"=>$status
        );

        echo json_encode($this->mMessengerModel->inboxLoaded($params),JSON_FORCE_OBJECT);

    }

    public function sendMessage(){


        /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $sender_id = intval(Security::decrypt($this->input->post("sender_id")));
        $receiver_id = intval(Security::decrypt($this->input->post("receiver_id")));
        $status = intval(Security::decrypt($this->input->post("type")));
        $content = Security::decrypt($this->input->post("content"));
        $messageId = Security::decrypt($this->input->post("messageId"));


        $params = array(
            "sender_id"    =>$sender_id,
            "receiver_id"  =>$receiver_id,
            "content"      =>$content,
            "type"         =>$status,
            "messageId"    => $messageId
        );




        echo json_encode($this->mMessengerModel->sendMessage($params),JSON_FORCE_OBJECT);

    }


    public function markMessagesAsSeen(){


        /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = intval(Security::decrypt($this->input->post("user_id")));
        $discussionId = intval(Security::decrypt($this->input->post("discussionId")));

        $this->load->model("Messenger/MessengerModel","mMessengerModel");

        $params = array(
            "user_id"    =>$user_id,
            "discussionId"  =>$discussionId
        );


        echo json_encode($this->mMessengerModel->markMessagesAsSeen($params),JSON_FORCE_OBJECT);

    }


    public function markMessagesAsLoaded(){


        /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = intval(Security::decrypt($this->input->post("user_id")));
        $discussionId = intval(Security::decrypt($this->input->post("discussionId")));

        $params = array(
            "user_id"    =>$user_id,
            "discussionId"  =>$discussionId
        );


        echo json_encode($this->mMessengerModel->markMessagesAsLoaded($params),JSON_FORCE_OBJECT);

    }



    public function loadMessages(){


        /*///////////////////////////////////////////////////////////////
         * //////////////////////////////////////////////////////////////
         * ncrytation data developped by amine
         *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////



        $discussion_id = intval(Security::decrypt($this->input->post("discussion_id")));
        $user_id = intval(Security::decrypt($this->input->post("user_id")));
        $receiver_id = intval(Security::decrypt($this->input->post("receiver_id")));

        $page = intval(Security::decrypt($this->input->post("page")));
        $status = intval(Security::decrypt($this->input->post("status")));
        $date = Security::decrypt($this->input->post("date"));
        $lastMessageId =intval(Security::decrypt($this->input->post("last_id")));


        $params = array(
            "discussion_id"  =>$discussion_id,
            "status"  =>$status,
            "page"  =>$page,
            "user_id"  =>$user_id,
            "receiver_id"  =>$receiver_id,
            "date"  => $date,
            "lastMessageId" => $lastMessageId
        );


        $data = $this->mMessengerModel->loadMessages($params);

        if($data[Tags::SUCCESS]==1){

            //decode text
            $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
            $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);

            echo json_encode(array(Tags::SUCCESS=>1,Tags::COUNT=>$data[Tags::COUNT],Tags::RESULT=>$data[Tags::RESULT]),JSON_FORCE_OBJECT);

        }else{

            echo json_encode($data);
        }


    }

    public function loadInbox(){


        /*///////////////////////////////////////////////////////////////
         * //////////////////////////////////////////////////////////////
         * ncrytation data developped by amine
         *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = intval(Security::decrypt($this->input->post("user_id")));
        $status = intval(Security::decrypt($this->input->post("status")));

        $params = array(
            "user_id"  =>$user_id,
            "status"  =>$status
        );

        echo json_encode($this->mMessengerModel->loadInbox($params),JSON_FORCE_OBJECT);

    }


    public function register(){


        /*///////////////////////////////////////////////////////////////
           * //////////////////////////////////////////////////////////////
           * ncrytation data developped by amine
           *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $name = Security::decrypt($this->input->post("name"));
        $email = Security::decrypt($this->input->post("email"));
        $regId = Security::decrypt($this->input->post("regId"));


        $params = array(
            "name"  =>$name,
            "email"  =>$email,
            "regId"  =>$regId
        );


        echo json_encode($this->mMessengerModel->register($params));

    }

    public function sendNotification(){


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////


        $rId = Security::decrypt($this->input->post("registerId"));


        $params = array(
            "registerId"  =>$rId,
        );


        echo json_encode($this->mMessengerModel->send_notification($params));

    }

}