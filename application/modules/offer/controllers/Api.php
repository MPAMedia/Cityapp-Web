<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends API_Controller  {


    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("offer/offer_model","mOfferModel");


    }


    public function getOffers(){


        $limit = intval($this->input->post("limit"));
        $page = intval($this->input->post("page"));
        $order_by = intval($this->input->post("order_by"));
        //a proximite
        $latitude = doubleval($this->input->post("latitude"));
        $longitude = doubleval($this->input->post("longitude"));

        $store_id = intval($this->input->post("store_id"));
        $user_id = intval($this->input->post("user_id"));
        $category_id = intval($this->input->post("category_id"));
        $store_id = intval($this->input->post("store_id"));
        $offer_id = intval($this->input->post("offer_id"));
        $search = $this->input->post("search");
        $lat = $this->input->post("lat");
        $lng = $this->input->post("lng");
        $mac_adr = $this->input->post("mac_adr");

        $radius = $this->input->post("radius");


        $params = array(
            "user_id"  =>$user_id,
            "limit"       =>$limit,
            "page"          =>$page,
            "category_id"   =>$category_id,
            "latitude"   => $latitude,
            "longitude"  => $longitude,
            "store_id"  => $store_id,
            "user_id"  => $user_id,
            "offer_id"  => $offer_id,
            "search"  => $search,
            "status"  => 1,
            "mac_adr"    => $mac_adr,
            "lat"    => $lat,
            "lng"    => $lng,
            "order_by"    => $order_by,
            "radius"    => $radius
        );

        $data =  $this->mOfferModel->getOffers($params);

        if($data[Tags::SUCCESS]==1){

            $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>$data[Tags::COUNT]));
        }else{

            echo json_encode($data);
        }

    }




}