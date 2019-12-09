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
        $order_by = $this->input->post("order_by");
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
        $offer_value = $this->input->post("offer_value");
        $value_type = $this->input->post("value_type");
        $device_date = $this->input->post("date");
        $device_timzone = $this->input->post("timezone");



        $params = array(
            "user_id"  =>$user_id,
            "limit"       =>$limit,
            "page"          =>$page,
            "category_id"   =>$category_id,
            "latitude"   => $latitude,
            "longitude"  => $longitude,
            "store_id"  => $store_id,
            "offer_value"  => $offer_value,
            "value_type"  => $value_type,
            "offer_id"  => $offer_id,
            "search"  => $search,
            "statusM"  => 1,
            "mac_adr"    => $mac_adr,
            "lat"    => $lat,
            "lng"    => $lng,
            "order_by"    => $order_by,
            "radius"    => $radius,
            "device_date"    => $device_date,
            "device_timzone"    => $device_timzone,
        );




        $data =  $this->mOfferModel->getOffers($params,NULL,function($params){

            //HIDE Expired offers

            if( (!empty($params['device_date']) && $params['device_date'] != "") && (!empty($params['device_timzone']) && $params['device_timzone'] != "") )
            {
                $device_date = $params['device_date'];
                $device_timzone = $params['device_timzone'] ;
                $device_date_to_utc = MyDateUtils::convert($device_date, $device_timzone, "UTC", "Y-m-d H:i:s");
                $this->db->where("offer.date_end >=",$device_date_to_utc);

                //Display only the offers at the date specified by the store owner
                if(OFFERS_IN_DATE)
                {
                    $this->db->where("offer.date_start <=",$device_date_to_utc);
                }

            }
        });

        if($data[Tags::SUCCESS]==1){

            $data[Tags::RESULT] = Text::outputList($data[Tags::RESULT]);
            echo Json::convertToJson($data[Tags::RESULT],  Tags::RESULT,TRUE,array(Tags::COUNT=>$data[Tags::COUNT]));
        }else{

            echo json_encode($data);
        }

    }




}