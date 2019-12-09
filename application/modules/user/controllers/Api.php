<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends API_Controller   {


    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("messenger/messenger_model","mMessengerModel");
        $this->load->library('session');

    }

    public function registerToken()
    {

        $fcm_id =  $this->input->post("fcm_id");
        $sender_id =  $this->input->post("sender_id");
        $lat =  $this->input->post("lat");
        $lng =  $this->input->post("lng");
        $platform =  $this->input->post("platform");

        $result = $this->mUserModel->createNewGuest(array(
            "fcm_id"=>$fcm_id,
            "sender_id"=>$sender_id,
            "lat"=>$lat,
            "lng"=>$lng,
            "platform"=>$platform,
        ));

        echo json_encode($result,JSON_FORCE_OBJECT);
    }


    public function refreshPosition()
    {

        $guest_id =  intval($this->input->post("guest_id"));
        $lat =  $this->input->post("lat");
        $lng =  $this->input->post("lng");

        $result = $this->mUserModel->refreshPosition(array(
            "guest_id"=>$guest_id,
            "lat"=>$lat,
            "lng"=>$lng,
        ));

        echo json_encode($result,JSON_FORCE_OBJECT);
    }

    public function checkTokenIsValide($params=array()){
        
        $errors = array();
        $data = array();
        
        //extract — Importe les variables dans la table des symboles
        extract($params);
        
        if( 
                (isset($mac_adr) and Security::checkMacAddress($mac_adr)) 
                AND 
                (isset($token) and Security::checkToken($token))

           ){
            
            
            $this->db->where("_id",$token);
            $this->db->where("_device_id",$mac_adr);
            
            $c = $this->db->count_all_results('token');
            
            if($c==1){
                return TRUE;
            }

        }
        
        
        return FALSE;
    }

    
    public function generateToken(){
        
        

        $ip_adr = Security::decrypt($this->input->post("ip_adr"));
        $mac_adr = Security::decrypt($this->input->post("mac_adr"));
      
       $params = array(
           "ip_adr"  =>$ip_adr,
           "mac_adr"  =>$mac_adr
       );
       
       $data =  $this->mUserModel->generateToken($params);

        echo json_encode($data);
      
    }
 
    
    
    public function checkUser(){
        

        $user_id = intval($this->input->post("user_id"));
      
       $params = array(
            "user_id"  =>$user_id
       );
       
       $data =  $this->mUserModel->checkUser($params);

        echo json_encode($data);
      
    }
    
    
     
    public function uploadImage(){
       
       $this->load->model("upload_v1");
       /*
        * CHECK SECURITY
        */


        echo json_encode($this->upload_v1->uploadImage(@$_FILES['image']));
   }
   
   




   public function signIn(){


       /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////

       $login = Security::decrypt($this->input->post("login"));
       $mac_address = Security::decrypt($this->input->post("mac_address"));
       $password = Security::decrypt($this->input->post("password"));

       $lat = Security::decrypt($this->input->post("lat"));
       $lng = Security::decrypt($this->input->post("lng"));


       $guest_id = Security::decrypt($this->input->post("guest_id"));

       $params = array(
           "login"          => $login,
           "password"       => $password,
           "lat"       => $lat,
           "lng"       => $lng,
           "guest_id"       => $guest_id,
           "mac_address"    => $mac_address
       );
       
       $data = $this->mUserModel->signIn($params);

       if($data[Tags::SUCCESS]==1){

           
            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array());
       }else{
           echo json_encode($data);
       }
       
   }



    public function  changeUserStatus()
    {

        $user_id = intval($this->input->post("user_id"));
        $status =  intval(Security::decrypt($this->input->post("status")));
        $lat = doubleval($this->input->post("lat"));
        $lng =  doubleval($this->input->post("lng"));

        $params = array(
            'user_id' => $user_id,
            'status' => $status,
            'lat' => $lat,
            'lng' => $lng,
        );

        $data =  $this->mUserModel->changeUserStatus($params);


        echo json_encode($data);
    }

    public function checkUserConnection(){


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $email = Security::decrypt($this->input->post("email"));
        $userid = Security::decrypt($this->input->post("userid"));
        $username = Security::decrypt($this->input->post("username"));
        $senderId = Security::decrypt($this->input->post("senderid"));

        $params = array(
            "email"  =>$email,
            "userid"  => $userid,
            "username"  =>$username,
            "senderid"  => $senderId
        );

        $data =  $this->mUserModel->checkUserConnection($params);

        if($data[Tags::SUCCESS]==1 AND count($data[Tags::RESULT])>0 ){
            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>1));
        }else{
            echo json_encode($data);
        }


    }



    public function  getUsers()
    {


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $lat = trim($this->input->post("lat"));
        $lng =  Security::decrypt($this->input->post("lng"));
        $page =  Security::decrypt($this->input->post("page"));
        $limit =  Security::decrypt($this->input->post("limit"));
        $user_id =  Security::decrypt($this->input->post("user_id"));
        $uid =  Security::decrypt($this->input->post("uid"));

        $params = array(
            'lat' => $lat,
            'lng' => $lng,
            'limit' => $limit,
            'page' => $page,
            'user_id' => $user_id,
            'uid' => $uid, //<=== requested user
        );

        $data =  $this->mUserModel->getUsers($params);

        if($data[Tags::SUCCESS]==1){
            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>$data[Tags::COUNT]));
        }else{
            echo json_encode($data);
        }


    }

    
    public function  signUp()
   {


       /*///////////////////////////////////////////////////////////////
         * //////////////////////////////////////////////////////////////
         * ncrytation data developped by amine
         *//////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////

       $username = $this->input->post("username");
       $password =  Security::decrypt($this->input->post("password"));

       $first_name = $this->input->post("first_name");
       $last_name = $this->input->post("last_name");
       $email = $this->input->post("email");
       $phone = Text::input($this->input->post("phone"));
       //$name = Text::input($this->input->post("name"));
       $name = $this->input->post("name");
       $mac_addr = Security::decrypt($this->input->post("mac_address"));
       $token = ($this->input->post("token"));
       $auth_type = ($this->input->post("auth_type"));

       $lat = Security::decrypt($this->input->post("lat"));
       $lng = Security::decrypt($this->input->post("lng"));
       $image = Security::decrypt($this->input->post("image"));

       $guest_id = Security::decrypt($this->input->post("guest_id"));


       $params = array(
           'username' => $username,
           'password' => $password,
           'email' =>$email,
           'phone' =>$phone,
           'name' =>$name,
           'lat' =>$lat,
           'lng' =>$lng,
           'image' =>$image,
           'mac_address'=>$mac_addr,   
           'token'=>$token,
           "auth_type"  =>$auth_type,
           "guest_id"  =>$guest_id,
       );
       
        /*$data =  $this->mUserModel->signUp($params,array(
            "name"
       ));*/
  $data =  $this->mUserModel->signUp($params);

       //send message welcome
       if(MESSAGE_WELCOME!="" && isset($data[Tags::RESULT])){

           $this->load->model("Messenger/MessengerModel","mMessengerModel");

           $this->db->select("id_user");
           $this->db->order_by("id_user","ASC");
           $user = $this->db->get("user",1);
           $user = $user->result();

           $result =  $this->mMessengerModel->sendMessage(array(
               "sender_id"      => $user[0]->id_user,
               "receiver_id"    => $data[Tags::RESULT][0]['id_user'],
               "discussion_id"     => 0,
               "content"           => Text::input(MESSAGE_WELCOME)
           ));

       }

       
       if($data[Tags::SUCCESS]==1){
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,$data);
       }else{
           echo json_encode($data);
       }
      
       
   }

    public function  FacebookLogin()
   {

   
    
       $email = $this->input->post("email");
       $phone = Text::input($this->input->post("phone"));
       $name = Text::input($this->input->post("name"));
       $fb_id = Text::input($this->input->post("fb_id"));
       $mac_addr = Security::decrypt($this->input->post("mac_address"));
       $token = ($this->input->post("token"));
       $auth_type = ($this->input->post("auth_type"));

       $lat = Security::decrypt($this->input->post("lat"));
       $lng = Security::decrypt($this->input->post("lng"));
       $image = Security::decrypt($this->input->post("image"));

       $guest_id = Security::decrypt($this->input->post("guest_id"));


       $params = array(
           
           'email' =>$email,
           'phone' =>$phone,
           'name' =>$name,
           'fb_id' =>$fb_id,
           'lat' =>$lat,
           'lng' =>$lng,
           'image' =>$image,
           'mac_address'=>$mac_addr,   
           'token'=>$token,
           "auth_type"  =>$auth_type,
           "guest_id"  =>$guest_id,
       );
       
       $data =  $this->mUserModel->fbSignUp($params,array(
            "name"
       ));


       //send message welcome
       if(MESSAGE_WELCOME!="" && isset($data[Tags::RESULT])){

           $this->load->model("Messenger/MessengerModel","mMessengerModel");

           $this->db->select("id_user");
           $this->db->order_by("id_user","ASC");
           $user = $this->db->get("user",1);
           $user = $user->result();

           $result =  $this->mMessengerModel->sendMessage(array(
               "sender_id"      => $user[0]->id_user,
               "receiver_id"    => $data[Tags::RESULT][0]['id_user'],
               "discussion_id"     => 0,
               "content"           => Text::input(MESSAGE_WELCOME)
           ));

       }

       
       if($data[Tags::SUCCESS]==1){
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,$data);
       }else{
           echo json_encode($data);
       }
      
       
   }

   public function updateAccount()
   {

       /*///////////////////////////////////////////////////////////////
       * //////////////////////////////////////////////////////////////
       * ncrytation data developped by amine
       *//////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////

       $user_id = Security::decrypt($this->input->post("user_id"));
       $username = trim($this->input->post("username"));
       $password =  Security::decrypt($this->input->post("password"));
       $email =  $this->input->post("email");
       $name = $this->input->post("name");
       $first_name = $this->input->post("first_name");
       $last_name = $this->input->post("last_name");
       $phone = $this->input->post("phone");
       $city = $this->input->post("city_id");
       $mac_addr = Security::decrypt($this->input->post("mac_address"));
       $token = ($this->input->post("token"));

       $oldUsername = ($this->input->post("oldUsername"));
       $user_id = ($this->input->post("user_id"));
       $job = ($this->input->post("job"));


        $params = array(
            'user_id'=> $user_id,
            'username' => $username,
           'password' => $password,
           'email' =>$email,
           'name' =>$name,
           'first_name' =>$first_name,
           'last_name'=>$last_name,
           'oldUsername'=>$oldUsername,
           'phone'=>$phone,
           'city'=>$city,
           'user_id'=>$user_id,
           'job'=>$job,
           'mac_address'=>$mac_addr,
            'token'=>$token
        );
       
       $data =  $this->mUserModel->updateAccount($params);
       
       if($data[Tags::SUCCESS]==1){
           echo Json::convertToJson($data[Tags::RESULT],Tags::RESULT,TRUE,array());
       }else{
           echo json_encode($data);
       }
        

   }


    public function blockUser(){

        $state = $this->input->post('state');
        if($state=="true"){
            $state = TRUE;
        }else{
            $state = FALSE;
        }

        $params = array(
            "user_id"   => intval($this->input->post('user_id')),
            "blocked_id"   => intval($this->input->post('blocked_id')),
            "state"   => $state
        );

        $data = $this->mUserModel->blockUser($params);

        echo json_encode($data,JSON_FORCE_OBJECT);
        return;

    }
   
   
}
