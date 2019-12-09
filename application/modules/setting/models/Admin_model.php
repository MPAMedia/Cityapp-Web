<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {


    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

    }


    public function loadDefaultConfig(){


        if(file_exists("config/params.json")){

            $params = file_get_contents("config/params.json");
            $params = json_decode($params,JSON_OBJECT_AS_ARRAY);

            foreach ($params as  $key => $value){
                if(is_array($value)){
                    if(!defined($key))
                        define($key,json_encode($value,JSON_FORCE_OBJECT));
                }else{
                    if(!defined($key))
                        define($key,$value);
                }

            }

        }

    }

    public function createDefaultAdmin($login,$password,$email,$name,$timezone="UTC"){

        $this->loadDefaultConfig();

        $c = $this->db->count_all_results("user");

        if($c == 0){

            $this->db->insert("user",array(
                "name"  => trim($name),
                "username"=> $login,
                "manager"=> 1,
                "email" => $email,
                "password"=> Security::cryptPassword($password),
                "status"=> 1,
                "confirmed"=> 1,
                "typeAuth"=> "admin",
            ));

            $user_id = $this->db->insert_id();

            $package =array(
                "user_id"   =>$user_id,
                "timezone"  => "",
                "nbr_stores"    => -1,
                "nbr_events_monthly"    => -1,
                "nbr_campaign_monthly"    => -1,
                "nbr_offer_monthly"    => -1,
                "push_campaign_auto"    => true
            );
            $package['package'] = json_encode($package);
            $this->db->insert("setting",$package);

            $appLogo = _openDir(APP_LOGO);
            $imageUrl = "";
            if(!empty($appLogo)){
                $imageUrl = $appLogo['200_200']['url'];
            }

            //send mail verification
            $messageText = Text::textParser(array(
                "name" =>trim($name),
                "appName" =>trim(APP_NAME),
                "imageUrl"  => $imageUrl,
                "login"  => $login,
                "password"  => $password,
                "panelUrl"  => admin_url(""),
            ),"setupsuccess");

            $messageText = ($messageText);


            $mail = new Mailer();
            $mail->setDistination($email);
            $mail->setFrom(DEFAULT_EMAIL);
            $mail->setFrom_name(APP_NAME);
            $mail->setMessage($messageText);
            $mail->setReplay_to(DEFAULT_EMAIL);
            $mail->setReplay_to_name(APP_NAME);
            $mail->setType("html");
            $mail->setSubjet(Translate::sprint("Setup Successful!"));
            $mail->send();


            if(isset($password) and $password!=""){
                @$this->mConfigModel->save("UPS",base64_encode($password));
                @$this->mConfigModel->save("_APPURL",APPURL);
            }

            @$this->mConfigModel->save("TIME_ZONE",$timezone);


        }

        return TRUE;

    }

    public function getListUsers($params=array()){
      
        $errors = array();
        $data=array();
        
        extract($params);
        
        if(!isset($page)){
            $page=1;
            
        }
        
        if(!isset($limit)){
            $limit = 30;
            
        }
        
      
        $count = $this->db->count_all_results("user");
        
     
        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();
       
 
        
         $this->db->select("*");
         $this->db->from("user");
         $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());
         $users = $this->db->get();
         
         
        $users = $users->result();

       return array(Tags::SUCCESS=>1,"pagination"=>$pagination,  Tags::COUNT=>$count,  Tags::RESULT=>$users);
        
    }
    
    
    
}