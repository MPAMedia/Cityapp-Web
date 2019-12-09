<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("notification/notification_model","mNotificationModel");
    }

    public function register(){
        
       
       $name = Security::decrypt($this->input->post("name"));
       $email = Security::decrypt($this->input->post("email"));
       $regId = Security::decrypt($this->input->post("regId"));

       
         $params = array(
             "name"  =>$name,
             "email"  =>$email,
             "regId"  =>$regId
             );
        
        
        return json_encode($this->mNotificationModel->register($params));
        
   }
   
   public function sendNotification(){
        
       
       $rId = Security::decrypt($this->input->post("registerId"));
       
         $params = array(
            "registerId"  =>$rId,
             );
        
        
        return json_encode($this->mNotificationModel->send_notification($params));
        
   }
    
    

}