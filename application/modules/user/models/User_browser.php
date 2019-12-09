<?php

class User_browser extends CI_Model{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function refreshData($user_id){

        $this->db->select('user.*,user.status as user_status, user_subscribe_setting.*');
        $this->db->where("id_user",$user_id);
        $this->db->join('user_subscribe_setting','user_subscribe_setting.user_id=user.id_user');
        $user = $this->db->get("user",1);
        $user_data = $user->result_array();

        if(count($user_data)==0)
            return FALSE;

        $this->load->model("appcore/bundle");
        $user_data = $this->bundle->prepareData($user_data);

        $this->mUserBrowser->setUserData($user_data[0]);
        $this->session->set_userdata(array(
            "savesession"=>array()
        ));


        return TRUE;
    }

    public function isAdmin(){

        if($this->isLogged()){
            $role_access = $this->getData('typeAuth');
            if($role_access=='admin')
                return TRUE;
        }

        return FALSE;
    }

    public function isCoAdmin(){

        if($this->isLogged()){
            $role_access = $this->getData('typeAuth');
            $manager_type = $this->getData('manager');
            if($role_access=='admin' AND $manager_type!=1)
                return TRUE;
        }

        return FALSE;
    }

    public function isOwner(){

        if($this->isLogged()){
            $role_access = $this->getData('typeAuth');
            if($role_access=='manager')
                return TRUE;
        }

        return FALSE;
    }

    public function isCustomer(){

        if($this->isLogged()){
            $role_access = $this->getData('typeAuth');
            if($role_access=='customer')
                return TRUE;
        }

        return FALSE;
    }

    public function isSuperAdmin(){

        if($this->isLogged()){
            $role_access = $this->getData('typeAuth');
            $manager_type = $this->getData('manager');
            if($role_access=='admin' AND $manager_type==1)
                return TRUE;
        }

        return FALSE;
    }


    public function setDefaultTimezone(){

        if($this->isLogged()){
            date_default_timezone_set(TIME_ZONE);
        }
    }


    public function isShadowing(){

        $data = $this->session->savesession;

        if(empty($data)){
            return FALSE;
        }

        return TRUE;
    }


    public function close_shadowing_mode(){

        if($this->isLogged()){

            $data = $this->session->savesession;

            if(!empty($data)){
                $this->setUserData($data);
                $this->session->set_userdata(array(
                    "savesession"=>array()
                ));


                return TRUE;
            }
        }

        return FALSE;
    }


    public function shadowing_mode($id){

        if($this->isLogged()){

            $data = $this->getAllAdminData();
            $this->session->set_userdata(array(
                "savesession"  => $data
            ));

            $admin = $this->mUserModel->getUserData($id);
            if(!empty($admin)){
                $this->setUserData($admin);
                return TRUE;
            }



        }

        return FALSE;
    }


    public function LogOut(){

        $this->setUserData(array());
        $this->setID(0);
        $this->session->sess_destroy();
    }
    
    
    public function isLogged(){



        if(!isset($this->session->user))
            return FALSE;


        $data = $this->session->user;
        if( !empty($data) && isset($data['id_user']) && $data['id_user']>0){


            return TRUE;
        }
        
        return FALSE;
    }
    
    
    public function setID($id=0){
        
        if($id>0){
            
            $this->session->set_userdata(array("__AID"=> Security::encrypt(  intval($id)  )));
        }else{
            $this->session->set_userdata(array("__AID"=>  0));
        }
        
    }
    
    
    public function getAdminIdFromCookie(){
        
     
       $id = $this->session->__AID;
       if($id!=""){
           return intval(Security::decrypt($id));
       }
       return ;
    }
    
    
    
    public function getAllAdminData($data=array()){

        if(isset($this->session->user)){
            return $this->session->user;
        }
        return array();
    }
    
    public function setUserData($data=array()){

        $this->session->set_userdata(array(
            "user"  => $data
        ));
    
    }
    
    public function setAdmin($index='',$value=""){


        if($index!="" AND $value!=""){
            $data = $this->session->user;
            $data[$index] = $value;
            $this->session->set_userdata(array(
                "user"  => $data
            ));
        }
        return TRUE;
    }
    
    
    public function getAdmin($index=''){
        
        if($this->isLogged()){
            if($index!="" AND isset($this->session->user[$index])
                AND $this->session->user[$index] AND $this->session->user[$index]!=""){
            
            
            
                return $this->session->user[$index];
            }else{
                $this->initSession();
                
                if($index!="" AND isset($this->session->user[$index]) AND $this->session->user[$index]!=""){
                    return $this->session->user[$index];
                }
                
            }
        }
        
        
        return ;
    }
    
    
    public function setData($index="",$data=array()){
  
        if($index!="" ){
            $this->session->user[$index] = $data;
            return TRUE;
        }
      
        return FALSE;
    }

    public function getUserData(){

       return $this->session->user;

    }


    public function isUser($type){
        return TRUE;
        $t = $this->getData("typeAuth");
        if($t==$type){
            return TRUE;
        }

        return FALSE;
    }
    
    public function getData($index=""){

        if(isset($this->session->user[$index]) AND !empty($this->session->user[$index])){
            return $this->session->user[$index];
        }else{
            return ;
        }
        
    }
    
    public function cleanToken($value){
        
        if($value!="" AND isset($_SESSION['token'][$value])){
            $_SESSION['token'][$value] = "";
            unset($_SESSION['token'][$value]);
        }   
        
    }


    public function setToken($value="0"){
        if($value!=""){
            $createToken = md5(rand(0, 9999).  Security::encrypt($value));

           
              $_SESSION['token'][$value] = $createToken;
              return $createToken;
        } 
        
        return ;
    }
    
    
    
    public function getToken($value="0"){
        if($value!="" AND isset($_SESSION['token'][$value])){
            return $_SESSION['token'][$value];
        }   
        return "0";
    }
    
    
    
    
    
}

