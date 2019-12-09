<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offer_model extends CI_Model
{

    private $limit = 10;

    function __construct()
    {
        parent::__construct();
        define('MAX_CHARS_OFFERS_DESC', 2000);


        $this->load->model("setting/config_model",'mConfigModel');
        if (!defined('OFFERS_IN_DATE'))
            $this->mConfigModel->save('OFFERS_IN_DATE', false);

    }

    public function getDefaultCurrencyCode()
    {
        return DEFAULT_CURRENCY;
    }


    public function getOffersAnalytics($months = array(),$owner_id=0){

        $analytics = array();

        foreach ($months as $key => $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t");

            $start_month = date("Y-m-1", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1");

            $this->db->where("created_at >=", $start_month);
            $this->db->where("created_at <=", $last_month);


            if($owner_id>0)
                $this->db->where('user_id',$owner_id);

            $count = $this->db->count_all_results("offer");

            $index = date("m", strtotime($start_month));

            $analytics['months'][intval($index)] = $count;

        }

        if($owner_id>0)
            $this->db->where('user_id',$owner_id);

        $analytics['count'] = $this->db->count_all_results("offer");

        $analytics['count_label'] = "Total_offers";
        $analytics['color'] = "#009dff";
        $analytics['icon_tag'] = "<i class=\"mdi mdi-sale\"></i>";
        $analytics['label'] = "Offer";

        return $analytics;

    }




    public function markAsFeatured($params=array()){

        extract($params);


        if (!isset($type) and !isset($id) and !isset($featured))
            return array(Tags::SUCCESS => 0);


        $this->db->where("id_offer", $id);
        $this->db->update("offer", array(
            "featured" => intval($featured)
        ));

        return array(Tags::SUCCESS => 1);
    }

    public function switchTo($old_owner = 0, $new_owner = 0)
    {

        if ($new_owner > 0) {

            $this->db->where("id_user", $new_owner);
            $c = $this->db->count_all_results("user");
            if ($c > 0) {

                $this->db->where("user_id", $old_owner);
                $this->db->update("offer", array(
                    "user_id" => $new_owner
                ));

                return TRUE;
            }

        }

        return FALSE;
    }

    public function editOffersCurrency()
    {

        $this->db->select("content,id_offer");
        $offers = $this->db->get("offer");
        $offers = $offers->result_array();

        foreach ($offers as $value) {

            $content = $value['content'];

            if (!is_array($content))
                $content = json_decode($content, JSON_OBJECT_AS_ARRAY);

            print_r($content);

            if (isset($content['currency']['code'])) {

                $currencyObject = $this->getCurrencyByCode($content['currency']['code']);

                $content = json_encode(array(
                    "description" => $content['description'],
                    "price" => $content['price'],
                    "percent" => $content['percent'],
                    "currency" => $currencyObject
                ), JSON_FORCE_OBJECT);


                $this->db->where("id_offer", $value['id_offer']);
                $this->db->update("offer", array(
                    "content" => $content
                ));

            }

        }


    }

    public function getCurrencyByCode($code)
    {

        $currencies = json_decode(CURRENCIES, JSON_OBJECT_AS_ARRAY);

        if (isset($currencies[$code])) {
            return $currencies[$code];
        }

        return $this->getDefaultCurrency();
    }

    public function getDefaultCurrency()
    {

        $currencies = json_decode(CURRENCIES, JSON_OBJECT_AS_ARRAY);
        $d = DEFAULT_CURRENCY;
        foreach ($currencies as $key => $value) {
            if ($key == $d) {
                return $value;
            }
        }

        return;
    }


    public function changeStatus($params = array())
    {

        $errors = array();
        $data = array();
        extract($params);

        if (isset($offer_id) and $offer_id > 0) {

            $this->db->where("id_offer", intval($offer_id));
            $offer = $this->db->get("offer", 1);
            $offer = $offer->result();

            if (count($offer) > 0) {

                $status = $offer[0]->status;

                if ($status == 1) {
                    $this->db->where("id_offer", intval($offer_id));
                    $this->db->update("offer", array(
                        "status" => 0
                    ));
                } else {
                    $this->db->where("id_offer", intval($offer_id));
                    $this->db->update("offer", array(
                        "status" => 1
                    ));
                }

            }

        }

        return array(Tags::SUCCESS => 1);
    }

    public function getMyAllOffers($params = array())
    {

        $errors = array();
        $data = array();

        extract($params);

        if (isset($user_id) and $user_id > 0) {

            $this->db->where("status", 1);
            $this->db->where("user_id", intval($user_id));
            $this->db->order_by("id_offer", "DESC");
            $data = $this->db->get("offer");
            $data = $data->result_array();

            return array(Tags::SUCCESS => 1, Tags::RESULT => $data);
        }

        return array(Tags::SUCCESS => 0);
    }

    public function getOffers($params = array(), $whereArray = array(), $callback = NULL)
    {

        extract($params);
        $errors = array();
        $data = array();


        if (!isset($page)) {
            $page = 1;
        }

        if (!isset($limit)) {
            $limit = NO_OF_ITEMS_PER_PAGE;
        }

        if (!empty($whereArray))
            foreach ($whereArray as $key => $value) {
                $this->db->where($key, $value);
            }

        if ($callback != NULL)
            call_user_func($callback, $params);


        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('offer.name', $search);
            $this->db->or_like('store.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('offer.description', $search);
            $this->db->group_end();
        }




        if (isset($store_id) and $store_id > 0) {
            $data ['offer.store_id'] = intval($store_id);
        }

        if (isset($value_type) and $value_type == 'price') {
            $data ['offer.value_type'] = 'price';
        } else if (isset($value_type) and $value_type == 'percent') {
            $data ['offer.value_type'] = 'percent';
        }

        if (isset($value_type) and $value_type != 0) {
            $data ['offer.value_type'] = doubleval($value_type);
        }

        if (isset($offer_id) and $offer_id > 0) {
            $data ['offer.id_offer'] = intval($offer_id);
        }


        if (isset($date_end) and $date_end != "" and Text::validateDate($date_end)) {

            $date_end = MyDateUtils::convert($date_end, TIME_ZONE, "UTC", "Y-m-d");
            $this->db->where("offer.date_end >=", $date_end);
        }


        if (isset($user_id) and $user_id > 0) {

            $this->db->where("offer.user_id", intval($user_id));

        } else if (isset($is_super) AND $is_super) {

        } else if (isset($statusM) and !empty($statusM)){
            $this->db->where("offer.status", $statusM);
        }

        if(isset($status) and !empty($filterBy))
        {
            if($status == 0)
            {
                $this->db->where("offer.status", $status);
            }else if($status == 1){
                $current = date("Y-m-d H:i:s", time());
                //$current = MyDateUtils::convert($current, TIME_ZONE, "UTC", "Y-m-d");
                $this->db->where("offer.status", $status);
                if($filterBy == "Published")  { $this->db->where("offer.date_start > ",$current);}
                else if($filterBy == "Started") { $this->db->where("offer.date_start < ",$current);   $this->db->where("offer.date_end > ",$current); }
                else if($filterBy == "Finished") { $this->db->where("offer.date_end > ",$current); }
            }
        }


        //distance
        $calcul_distance = "";
        if (
            isset($longitude)
            AND
            isset($latitude)

        ) {

            $longitude = doubleval($longitude);
            $latitude = doubleval($latitude);


            $calcul_distance = " , IF( store.latitude = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(" . $latitude . ") )
                              * cos( radians( store.latitude ) )
                              * cos( radians( store.longitude ) - radians(" . $longitude . ") )
                              + sin ( radians(" . $latitude . ") )
                              * sin( radians( store.latitude ) )
                            )
                          ) ) ) as 'distance'  ";


        }


        $this->db->where($data);
        $this->db->join("store", "offer.store_id=store.id_store");

        $count = $this->db->count_all_results("offer");

        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();


        if ($count == 0)
            return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => array());


        if (!empty($whereArray))
            foreach ($whereArray as $key => $value) {
                $this->db->where($key, $value);
            }

        if ($callback != NULL)
            call_user_func($callback, $params);


        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('offer.name', $search);
            $this->db->or_like('store.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('offer.description', $search);
            $this->db->group_end();
        }


        if (isset($store_id) and $store_id > 0) {
            $data ['offer.store_id'] = intval($store_id);
        }

        if (isset($value_type) and $value_type == 'price') {
            $data ['offer.value_type'] = 'price';
        } else if (isset($value_type) and $value_type == 'percent') {
            $data ['offer.value_type'] = 'percent';
        }

        if (isset($value_type) and $value_type != 0) {
            $data ['offer.value_type'] = doubleval($value_type);
        }

        if (isset($offer_id) and $offer_id > 0) {
            $data ['offer.id_offer'] = intval($offer_id);
        }

        if (isset($date_end) and $date_end != "" and Text::validateDate($date_end)) {
            $date_end = MyDateUtils::convert($date_end, TIME_ZONE, "UTC", "Y-m-d");
            $this->db->where("offer.date_end >=", $date_end);
        }

        if (isset($user_id) and $user_id > 0) {

            $this->db->where("offer.user_id", intval($user_id));

        } else if (isset($is_super) AND $is_super) {

        } else if (isset($statusM) and !empty($statusM)){
            $this->db->where("offer.status", $statusM);
        }

        // filter offers by status
        if(isset($status) and !empty($filterBy))
        {
            if($status == 0)
            {
                $this->db->where("offer.status", $status);
            }else if($status == 1){
                $current = date("Y-m-d H:i:s", time());
                //$current = MyDateUtils::convert($current, TIME_ZONE, "UTC", "Y-m-d");
                $this->db->where("offer.status", $status);
                if($filterBy == "Published")  { $this->db->where("offer.date_start > ",$current);}
                else if($filterBy == "Started") { $this->db->where("offer.date_start < ",$current);   $this->db->where("offer.date_end > ",$current); }
                else if($filterBy == "Finished") { $this->db->where("offer.date_end < ",$current); }
            }
        }

        $this->db->join("store", "store.id_store=offer.store_id");

        $this->db->select("offer.*,store.latitude,store.longitude,store.name as 'store_name'" . $calcul_distance, FALSE);


        $this->db->where($data);
        $this->db->from("offer");
        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());

        if (!empty($order_by) and $order_by == "by_date_desc") {
            $this->db->order_by("offer.date_start", "DESC");
        } else {
            if ($calcul_distance == "") $this->db->order_by("offer.id_offer", "DESC");
            else  $this->db->order_by("distance", "ASC");

            //todo : temporary solution to fix result_array on boolean error
            //todo  : group by all fields on the query
            //$this->db->group_by("offer.id_offer");
        }


        if (isset($radius) and $radius > 0 && $calcul_distance != "")
            $this->db->having('distance <= ' . intval($radius), NULL, FALSE);

        $offers = $this->db->get();
        $offers = $offers->result_array();


        if (count($offers) < $limit) {
            $count = count($offers);
        }

        foreach ($offers as $key => $offer) {

            $offers[$key]['link'] = base_url("offer/id/" . $offer["id_offer"]);

            if (isset($offer['images'])) {

                $images = (array)json_decode($offer['images']);

                $offers[$key]['images'] = array();
                // $new_stores_results[$key]['image'] = $store['images'];
                foreach ($images AS $k => $v) {
                    $offers[$key]['images'][] = _openDir($v);
                }

            } else {
                $offers[$key]['images'] = array();
            }

            //if($offer['value_type'] == 'price'){
            $offers[$key]['currency'] = $this->mCurrencyModel->getCurrency($offer['currency']);
            // }
        }

        if ($calcul_distance != "" && isset ($order_by) && $order_by != -2 && $order_by != -3) {
            $offers = $this->re_order_featured_item($offers);
        }



        return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => $offers);
    }


    public function re_order_featured_item($data = array())
    {

        $new_data = array();

        foreach ($data as $key => $value) {
            if ($value['featured'] == 1) {
                $new_data[] = $data[$key];
                unset($data[$key]);
            }
        }


        foreach ($data as $value) {
            $new_data[] = $value;
        }

        return $new_data;
    }


    public function addOffer($params = array())
    {

        extract($params);


        $errors = array();
        $data = array();


        /*
         *  MANAGE OFFER IMAGES
         */
        if (isset($images) and !is_array($images))
            $images = json_decode($images, JSON_OBJECT_AS_ARRAY);

        if (!empty($images)) {
            $data["images"] = array();
            $i = 0;
            try {
                if (!empty($images)) {
                    foreach ($images as $value) {
                        $data["images"][$i] = $value;
                        $i++;
                    }
                    $data["images"] = json_encode($data["images"], JSON_FORCE_OBJECT);
                }
            } catch (Exception $e) {

            }

        }

        if (isset($data["images"]) and empty($data["images"])) {
            $errors['images'] = Translate::sprint("Please upload an image");
        }

        if (isset($store_id) and $store_id > 0) {
            $data['store_id'] = intval($store_id);
        } else {
            $errors['store_id'] = Translate::sprint(Messages::STORE_NOT_SPECIFIED);
        }


        if (isset($name) and $name != "") {
            $data['name'] = Text::input($name);
        } else {
            $errors['name'] = Translate::sprint(Messages::OFFER_NAME_MISSING);
        }

        if (isset($description) and $description != "") {
            $data['description'] = Text::inputWithoutStripTags($description);
        } else {
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }

        if (isset($price) and doubleval($price) > 0) {

            $data['offer_value'] = doubleval($price);
            $data['value_type'] = 'price';

        } else if (isset($percent) and (intval($percent) > 0 || intval($percent) < 0)) {
            $data['offer_value'] = doubleval($percent);
            $data['value_type'] = 'percent';
        } else {

            //Create an offer with a non specified value type : e.g promo , free offre ...etc
            $data['value_type'] = 'unspecified';
            //$errors['price'] = Translate::sprint(Messages::OFFER_VALUE_EMPTY);
        }

        if (isset($currency) and $currency != "" and preg_match('#([a-zA-Z])#', $currency)) {
            $data['currency'] = $currency;
        } else {
            $data['currency'] = 'USD';
        }


        if (isset($date_start) and Text::validateDate($date_start)) {
            //$date_start = MyDateUtils::convert($date_start, TIME_ZONE, "UTC", "Y-m-d");
            //$currentTime = date("H:i:s", time());
            $data['date_start'] = $date_start;//." ".$currentTime;
        } else {
            $errors['date_start'] = Translate::sprint(Messages::DATE_BEGIN_NOT_VALID);
        }

        if (isset($date_end) and Text::validateDate($date_end)) {
            //$date_end = MyDateUtils::convert($date_end, TIME_ZONE, "UTC", "Y-m-d H:i:s");
            $data['date_end'] = $date_end;
        } else {
            //$errors['date_end'] = Text::_print("Date of end is not valid!");
            $data['date_end'] = "";
        }

        if (isset($user_id) and $user_id > 0) {
            $data['user_id'] = $user_id;
        } else {
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }


        //Set the date created as current date
        $data['date_created'] = date("Y-m-d H:i:s", time());


        if (!isset($user_type) OR (isset($user_type) and $user_type == "manager")) {

            if (empty($errors) and $store_id > 0) {

                $this->db->where("user_id", $user_id);
                $this->db->where("id_store", $store_id);
                $this->db->where("status", 1);
                $store = $this->db->get("store", 1);
                $store = $store->result_array();
                if (count($store) == 0) {
                    $errors['store'] = Translate::sprint(Messages::USER_NOT_FOUND);;
                }

            }

        }


        if (empty($errors) and isset($user_id) and $user_id > 0) {

            $nbr_offers_monthly = UserSettingSubscribe::getUDBSetting($user_id, KS_NBR_OFFERS_MONTHLY);

            if ($nbr_offers_monthly > 0 || $nbr_offers_monthly == -1) {

                if (ENABLE_OFFER_AUTO == TRUE)
                    $data['status'] = 1;
                else
                    $data['status'] = 0;


                $date = date("Y-m-d H:i:s", time());
                $data['created_at'] = MyDateUtils::convert($date, TIME_ZONE, "UTC");


                $this->db->insert("offer",$data);

                if ($nbr_offers_monthly > 0) {
                    $nbr_offers_monthly--;
                    UserSettingSubscribe::refreshUSetting($user_id, KS_NBR_OFFERS_MONTHLY, $nbr_offers_monthly);
                }

                return array(Tags::SUCCESS => 1);

            } else {
                $errors["offers"] = Translate::sprint(Messages::EXCEEDED_MAX_NBR_STORES);
            }

        } else {
            $errors['store'] = Text::_print("Error!");
        }

        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);

    }


    public function editOffer($params = array())
    {

        extract($params);


        $errors = array();
        $data = array();


        /*
        *  MANAGE OFFER IMAGES
        */

        if (isset($images) and !is_array($images))
            $images = json_decode($images, JSON_OBJECT_AS_ARRAY);

        if (!empty($images)) {
            $data["images"] = array();
            $i = 0;
            try {
                if (!empty($images)) {
                    foreach ($images as $value) {
                        $data["images"][$i] = $value;
                        $i++;
                    }
                    $data["images"] = json_encode($data["images"], JSON_FORCE_OBJECT);
                }
            } catch (Exception $e) {

            }
        } else {
            $data["images"] = json_decode("", JSON_OBJECT_AS_ARRAY);
        }

        if (isset($data["images"]) and empty($data["images"])) {
            $errors['images'] = Translate::sprint("Please upload an image");
        }

        if (isset($name) and $name != "") {
            $data['name'] = Text::input($name);
        } else {
            //$errors['store'] = Text::_print("Store id is messing");
        }

        if (isset($store_id) and $store_id > 0) {
            $data['store_id'] = intval($store_id);
        } else {
            $errors['store'] = Translate::sprint("Please_select_store", "Please select store");
        }

        if (isset($offer_id) and $offer_id > 0) {
            $data['id_offer'] = intval($offer_id);
        } else {
            $errors['id_offer'] = Translate::sprint(Messages::OFFER_ID_MISSING);
        }

        if (isset($description) and $description != "") {
            $data['description'] = Text::inputWithoutStripTags($description);
        } else {
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }


        if (isset($price) and doubleval($price) > 0) {

            $data['offer_value'] = doubleval($price);
            $data['value_type'] = 'price';

            if (isset($currency) and $currency != "" and preg_match('#([a-zA-Z])#', $currency)) {
                $data['currency'] = $currency;
            } else {
                $data['currency'] = 'USD';
            }

        } else if (isset($percent) and (intval($percent) > 0 || intval($percent) < 0)) {
            $data['offer_value'] = doubleval($percent);
            $data['value_type'] = 'percent';
        } else {
            //Create an offer with a non specified value type : e.g promo , free offre ...etc
            $data['value_type'] = 'unspecified';
            //$errors['price'] = Translate::sprint(Messages::OFFER_VALUE_EMPTY);
        }

        if (isset($date_end) and Text::validateDate($date_end)) {
            $date_end = date('Y-m-d', strtotime($date_end));
            //$date_end = MyDateUtils::convert($date_end, TIME_ZONE, "UTC", "Y-m-d");
            $data['date_end'] = $date_end;
        } else {
            //$errors['date_end'] = Text::_print("Date of end is not valid!");
            $data['date_end'] = "";
        }

        if (isset($date_start) and Text::validateDate($date_start)) {
            $date_start = date('Y-m-d', strtotime($date_start));
            //$date_start = MyDateUtils::convert($date_start, TIME_ZONE, "UTC", "Y-m-d");
            //$currentTime = date("H:i:s", time());
            $data['date_start'] = $date_start;//." ".$currentTime;
        } else {
            $errors['date_start'] = Translate::sprint(Messages::DATE_BEGIN_NOT_VALID);
        }



        if (isset($user_id) and intval($user_id) > 0) {
            $data['user_id'] = $user_id;
        } else {
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }

        if (empty($errors) and $store_id > 0) {

            $this->db->where("user_id", $user_id);
            $this->db->where("id_store", $store_id);
            $this->db->where("status", 1);
            $c = $this->db->count_all_results("store");
            if ($c == 0) {
                $errors['store'] = Translate::sprint(Messages::STORE_ID_NOT_VALID);
            }

        }


        if (empty($errors) and isset($user_id) and $user_id > 0) {

            $date = date("Y-m-d H:i:s", time());
            $data['updated_at'] = MyDateUtils::convert($date, TIME_ZONE, "UTC");


            //$data['status'] = 1;
            $this->db->where("id_offer", $offer_id);
            $this->db->where("user_id", $user_id);
            $this->db->update("offer", $data);

            return array(Tags::SUCCESS => 1);

        } else {
            $errors['store'] = Text::_print("Error! ");
        }

        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);

    }


    public function deleteOffer($params = array())
    {

        extract($params);
        $errors = array();
        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");

        if (isset($offer_id) and $offer_id > 0 && $user_id > 0) {


            $this->db->where("id_offer", $offer_id);
            $offers = $this->db->get("offer");
            $offerToDelete = $offers->result();

            //Delete all images from this offers
            if (isset($offerToDelete[0]->images)) {
                $images = (array)json_decode($offerToDelete[0]->images);
                foreach ($images AS $k => $v) {
                    _removeDir($v);
                }
            }

            $this->db->where("id_offer", $offer_id);
            $this->db->delete("offer");

            return array(Tags::SUCCESS => 1);

        }

        return array(Tags::SUCCESS => 0);
    }


    function hiddenOfferOutOfDate()
    {
        $this->db->select("date_end,id_offer");
        $this->db->where("status", 1);
        $offers = $this->db->get("offer", 1);
        $offers = $offers->result_array();
        if (count($offers) > 0) {
            $currentDate = date("Y-m-d H:i:s", time());
            $currentDate = MyDateUtils::convert($currentDate, TIME_ZONE, "UTC", "Y-m-d H:i:s");
            foreach ($offers as $value) {
                if (strtotime($value["date_end"]) < strtotime($currentDate)) {
                    $this->db->where("id_offer", $value["id_offer"]);
                    $this->db->update("offer", array(
                        "status" => 0));
                }
            }
            return array(Tags::SUCCESS => 1);
        } else {
            return array(Tags::SUCCESS => 0);
        }
    }


    public function emigrateOfferContent16()
    {


        $offers = $this->db->get('offer');
        $offers = $offers->result();

        foreach ($offers as $offer) {
            $content = $offer->content;


            if (!is_array($content))
                $content = json_decode($content, JSON_OBJECT_AS_ARRAY);

            $update = array(
                'images' => '',
                'description' => '',
                'value_type' => '',
                'offer_value' => '',
                'currency' => 'USD',
            );

            if ($offer->image != '')
                $update['images'] = json_encode(array($offer->image => $offer->image));

            $update['description'] = $content['description'];

            if (!is_array($content['currency']))
                $content['currency'] = json_decode($content['currency'], JSON_OBJECT_AS_ARRAY);

            if (is_array($content['currency']))
                $update['currency'] = $content['currency']['code'];
            else
                $update['currency'] = $content['currency'];

            $update['description'] = $content['description'];

            if ($content['price'] > 0) {
                $update['value_type'] = 'price';
                $update['offer_value'] = doubleval($content['price']);
            } else if ($content['percent'] != 0) {
                $update['value_type'] = 'percent';
                $update['offer_value'] = doubleval($content['percent']);
            } else {
                $update['value_type'] = 'price';
                $update['offer_value'] = 0;
            }

            $this->db->where('id_offer', $offer->id_offer);
            $this->db->update('offer', $update);

        }


    }


    public function addFields16()
    {


        if (!$this->db->field_exists('images', 'offer')) {
            $fields = array(
                'images' => array('type' => 'TEXT', 'after' => 'image', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

        if (!$this->db->field_exists('description', 'offer')) {
            $fields = array(
                'description' => array('type' => 'TEXT', 'after' => 'images', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

        if (!$this->db->field_exists('value_type', 'offer')) {
            $fields = array(
                'value_type' => array('type' => 'VARCHAR(10)', 'after' => 'description', 'default' => 'percent'),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

        if (!$this->db->field_exists('offer_value', 'offer')) {
            $fields = array(
                'offer_value' => array('type' => 'DOUBLE', 'after' => 'value_type', 'default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

        if (!$this->db->field_exists('currency', 'offer')) {
            $fields = array(
                'currency' => array('type' => 'VARCHAR(30)', 'after' => 'offer_value', 'default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

    }


    public function updateFields()
    {


        if (!$this->db->field_exists('verified', 'offer')) {
            $fields = array(
                'verified' => array('type' => 'INT', 'after' => 'tags', 'default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }


        if (!$this->db->field_exists('created_at', 'offer')) {
            $fields = array(
                'created_at' => array('type' => 'DATETIME', 'after' => 'date_end', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }

        if (!$this->db->field_exists('updated_at', 'offer')) {
            $fields = array(
                'updated_at' => array('type' => 'DATETIME', 'after' => 'created_at', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('offer', $fields);
        }


    }


}