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



    public function create()
    {

        $user_id = $this->input->post("user_id");

        if(!GroupAccess::isGrantedUser($user_id,'store',ADD_STORE)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $detail = $this->input->post("detail");
        $tel = $this->input->post("tel");
        $category = intval($this->input->post("category_id"));
        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");

        $params = array(
            "name"      => $name,
            "address"   => $address,
            "detail"    => $detail,
            "phone"     => $tel,
            "user_id"   => $user_id,
            "category"  => $category,
            "latitude"  => $lat,
            "longitude" => $lng,
            "images"    => $images,
            //"typeAuth"  => $this->mUserBrowser->getData("typeAuth")
        );

        $data = $this->mStoreModel->createStore($params);

        echo json_encode($data);return;
    }


    public function delete()
    {

        $store_id = intval($this->input->post("store_id"));
        $user_id = intval($this->input->post("user_id"));

        if(!GroupAccess::isGrantedUser($user_id,'store',DELETE_STORE)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }


        echo json_encode(
            $this->mStoreModel->delete($store_id,$user_id), JSON_FORCE_OBJECT
        );return;
    }


    public function changeStatus(){

        $status = $this->input->post("status");
        $user_id = intval($this->input->post("user_id"));
        $store_id = intval($this->input->post("store_id"));

        $params = array(
            "status" => $status,
            "user_id" => $user_id,
            "store_id" => $store_id,
        );

        $data = $this->mStoreModel->changeStatus($params);

        echo json_encode($data,JSON_FORCE_OBJECT);return;

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
        $search = $this->input->post("search");

        $radius = $this->input->post("radius");

        $status = trim($this->input->post("status"));

        if($status==""){
            $status = 1;
        }else{
            $status = intval($status);
        }

        $mac_adr = $this->input->post("mac_adr");
        
        
        $store_ids = Security::decrypt($this->input->post("store_ids"));
       
       $params = array(
            "user_id"  =>$user_id,
            "limit"       =>$limit,
            "page"          =>$page,
            "category_id"   =>$category_id,
            "latitude"   => $latitude,
            "longitude"  => $longitude,
            "store_id"  => $store_id,
            "store_ids"  => $store_ids,
            "search"  => $search,
            "status"  => $status,
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
    
    
    
     public function edit(){

         $user_id = intval($this->input->post("user_id"));

         if(!GroupAccess::isGrantedUser($user_id,'store',EDIT_STORE)){
             echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                 "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
             )));
             exit();
         }

         $store_id = intval(($this->input->post("store_id")));
         $name = $this->input->post("name");
         $address = $this->input->post("address");
         $detail = $this->input->post("detail");
         $tel = $this->input->post("tel");

         $category = intval($this->input->post("category_id"));
         $lat = doubleval($this->input->post("lat"));
         $lng = doubleval($this->input->post("lng"));
         $images = $this->input->post("images");

       
       $params = array(
           "store_id"      => $store_id,
           "name"      => $name,
           "address"   => $address,
           "detail"    => $detail,
           "phone"     => $tel,
           "user_id"   => $user_id,
           "category"  => $category,
           "latitude"  => $lat,
           "longitude" => $lng,
           "images"    => $images,
       );
       
       $data =  $this->mStoreModel->updateStore($params);

       echo json_encode($data);return;
      
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