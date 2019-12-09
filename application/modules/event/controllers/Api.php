<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event_webservice
 *
 * @author idriss
 */
class Api extends API_Controller  {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("event/event_model","mEventModel");

    }

    public function  removeEvent()
    {


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = trim($this->input->post("user_id"));
        $store_id =  Security::decrypt($this->input->post("event_id"));


        $params = array(
            'user_id' => $user_id,
            'event_id' => $store_id,
        );

        $data =  $this->mEventModel->removeEvent($params);

        echo json_encode($data);


    }

    public function  saveEvent()
    {


        /*///////////////////////////////////////////////////////////////
          * //////////////////////////////////////////////////////////////
          * ncrytation data developped by amine
          *//////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////

        $user_id = trim($this->input->post("user_id"));
        $store_id =  Security::decrypt($this->input->post("event_id"));


        $params = array(
            'user_id' => $user_id,
            'event_id' => $store_id,
        );

        $data =  $this->mEventModel->saveEvent($params);

        echo json_encode($data);


    }

    public function getEvents(){


         /*///////////////////////////////////////////////////////////////
        * //////////////////////////////////////////////////////////////
        * ncrytation data developped by amine
        *//////////////////////////////////////////////////////////////
         ///////////////////////////////////////////////////////////////

        $limit = intval($this->input->post("limit"));
        $page = intval($this->input->post("page"));
        $order_by = intval($this->input->post("order_by"));
        //a proximite
        $latitude = doubleval($this->input->post("latitude"));
        $longitude = doubleval($this->input->post("longitude"));
        $event_id = intval($this->input->post("event_id"));
        $search = $this->input->post("search");
        $mac_adr = $this->input->post("mac_adr");
        $event_ids = Security::decrypt($this->input->post("event_ids"));

         $radius = $this->input->post("radius");

         $params = array(
            "limit"       =>$limit,
            "page"          =>$page,
            "latitude"   => $latitude,
            "longitude"  => $longitude,
            "event_id"  => $event_id,
            "event_ids"  => $event_ids,
            "search"  => $search,
            "status"  => 1,
            "mac_adr"    => $mac_adr,
            "order_by"    => $order_by,
            "radius"    => $radius
           );
       
       $data =  $this->mEventModel->getEvents($params);


       
       if($data[Tags::SUCCESS]==1){

           $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
           echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>$data[Tags::COUNT]));
       }else{

           echo json_encode($data);
       }
      
    }




}
