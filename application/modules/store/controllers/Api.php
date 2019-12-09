<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends API_Controller  {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("store/store_model","mStoreModel");
        $this->load->library('session');

        $lang = Security::decrypt($this->input->get_request_header('Lang', DEFAULT_LANG));
        Translate::changeSessionLang($lang);

    }

    public function rate(){
        

        $mac_address = $this->input->post("mac_adr");
        $rate = intval($this->input->post("rate"));
        $guest_id = intval($this->input->post("guest_id"));
        $review = $this->input->post("review");
        $pseudo = $this->input->post("pseudo");
        $store_id = intval($this->input->post("store_id"));
        
        
        
         $params = array(
            "mac_adr"       =>$mac_address,
            "store_id"       =>$store_id,
            "rate"          =>$rate,
            "guest_id"      =>$guest_id,
            'review'       =>$review,
            'pseudo'       =>$pseudo
           );
         

       
       $data =  $this->mStoreModel->rate($params);

       echo json_encode($data);
       
    }
    

     public function getStores(){
        

        
        $limit = intval($this->input->post("limit"));
        $page = intval($this->input->post("page"));
        $order_by = intval($this->input->post("order_by"));
        //a proximite
        $latitude = doubleval($this->input->post("latitude"));
        $longitude = doubleval($this->input->post("longitude"));
        $status = intval($this->input->post("status"));

        $store_id = intval($this->input->post("store_id"));
        $user_id = intval($this->input->post("user_id"));
        $category_id = intval($this->input->post("category_id"));
        $place_id = intval($this->input->post("place_id"));
        $search = $this->input->post("search");

        $radius = $this->input->post("radius");

        $mac_adr = $this->input->post("mac_adr");
        
        
        $store_ids = Security::decrypt($this->input->post("store_ids"));
       
       $params = array(
            "user_id"  =>$user_id,
            "limit"       =>$limit,
            "page"          =>$page,
            "category_id"   =>$category_id,
            "place_id"   =>$place_id,
            "latitude"   => $latitude,
            "longitude"  => $longitude,
            "store_id"  => $store_id,
            "store_ids"  => $store_ids,
            "user_id"  => $user_id,
            "search"  => $search,
            "status"  => 1,
            "mac_adr"    => $mac_adr,
            "order_by"    => $order_by,
            
            "radius"    => $radius
           );
       
       $data =  $this->mStoreModel->getStores($params);
       
       
       
       if($data[Tags::SUCCESS]==1){

           $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>$data[Tags::COUNT]));
       }else{
           
           echo json_encode($data);
       }
      
    }
    
    
    
     public function updateStore(){
        

        $user_id = intval($this->input->post("user_id"));
        $name = $this->input->post("name");
        $name_ar = $this->input->post("name_ar");
        $address = $this->input->post("address");
        $latitude = $this->input->post("latitude");
        $longitude = $this->input->post("longitude");
        $type = $this->input->post("type");
        $images = $this->input->post("images");
        $detail = $this->input->post("detail");
        $detail_ar = $this->input->post("detail_ar");
        $store_id = $this->input->post("store_id");
       
        
       
       $params = array(
            "user_id"  =>$user_id,
           "name"   =>$name,
           "name_ar"   =>$name_ar,
           "address"=>$address,
           "latitude"=>$latitude,
           "longitude"=>$longitude,
           "type"=>$type,
           "images"=>$images,
           "detail"=>$detail,
           "detail_ar"=>$detail_ar,
           "store_id"=>$store_id,
           
       );
       
       $data =  $this->mStoreModel->updateStore($params);
       
       
       
       if($data[Tags::SUCCESS]==1){
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array());
       }else{
           echo json_encode($data);
       }
      
    }
    
    
    
    public function createStore(){
        

        $user_id = intval($this->input->post("user_id"));
        $name = Text::input($this->input->post("name"));
        $name_ar = Text::input($this->input->post("name_ar"));
        $address = Text::input($this->input->post("address"));
        $latitude = doubleval($this->input->post("latitude"));
        $longitude = doubleval($this->input->post("longitude"));
        $type = intval($this->input->post("type"));
        $images = Text::input($this->input->post("images"));
        $detail = Text::input($this->input->post("detail"));
        $detail_ar = Text::input($this->input->post("detail_ar"));
        
          $phone = Text::input($this->input->post("phone"));
        
        
       
       $params = array(
         "user_id"  =>$user_id,
           "name"   =>$name,
           "name_ar"   =>$name_ar,
           "address"=>$address,
           "latitude"=>$latitude,
           "longitude"=>$longitude,
           "type"=>$type,
           "images"=>$images,
           'phone'=>$phone,
           "detail"=>$detail,
           "detail_ar"=>$detail_ar,
           
       );
       
       $data =  $this->mStoreModel->createStore($params);
       
       
       
       if($data[Tags::SUCCESS]==1){
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array());
       }else{
           echo json_encode($data);
       }
      
    }
    
    
    
    public function getComments()
  {

     $mac_adr = $this->input->post("mac_adr");
     $mac_adr = $this->input->post("mac_adr");
     $limit = intval($this->input->post("limit"));
         $page = intval($this->input->post("page"));
         $store_id = intval($this->input->post("store_id"));

            $params = array(
            'mac_adr'   =>  $mac_adr,
            'limit'     =>  $limit,
            'page'      =>  $page,
            'store_id'  =>$store_id
          );

        $data =  $this->mStoreModel->getComments($params);

       echo json_encode($data,JSON_FORCE_OBJECT);
  }

    public function  removeStore()
    {


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = trim($this->input->post("user_id"));
        $store_id =  Security::decrypt($this->input->post("store_id"));


        $params = array(
            'user_id' => $user_id,
            'store_id' => $store_id,
        );

        $data =  $this->mStoreModel->removeStore($params);

        echo json_encode($data);

    }

    public function  saveStore()
    {

        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = trim($this->input->post("user_id"));
        $store_id =  Security::decrypt($this->input->post("store_id"));


        $params = array(
            'user_id' => $user_id,
            'store_id' => $store_id,
        );

        $data =  $this->mStoreModel->saveStore($params);

        echo json_encode($data);


    }





}