<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }



    public function getStoresAnalytics($months = array(),$owner_id=0){

        $analytics = array();

        foreach ($months as $key => $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t");

            $start_month = date("Y-m-1", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1");

            $this->db->where("created_at >=", $start_month);
            $this->db->where("created_at <=", $last_month);


            if($owner_id>0)
                $this->db->where('user_id',$owner_id);

            $count = $this->db->count_all_results("store");

            $index = date("m", strtotime($start_month));

            $analytics['months'][intval($index)] = $count;

        }

        if($owner_id>0)
            $this->db->where('user_id',$owner_id);

        $analytics['count'] = $this->db->count_all_results("store");

        $analytics['count_label'] = "Total_stores";
        $analytics['color'] = "#dd4b39";
        $analytics['icon_tag'] = "<i class=\"mdi mdi-store\"></i>";
        $analytics['label'] = "Store";

        return $analytics;

    }


    public function markAsFeatured($params=array()){

        extract($params);

        if (isset($typeAuth) and $typeAuth != "admin")
            return array(Tags::SUCCESS => 0);

        if (!isset($type) and !isset($id) and !isset($featured))
            return array(Tags::SUCCESS => 0);

        $this->db->where("id_store", $id);
        $this->db->update("store", array(
            "featured" => intval($featured)
        ));

        return array(Tags::SUCCESS => 1);
    }

    public function delete($store_id,$user_id=0)
    {


        $data["user_id"] = $user_id;

        if ($store_id > 0) {
            $store_id = intval($store_id);
        } else {
            $errors["store"] = Translate::sprint(Messages::STORE_NOT_SPECIFIED);
        }


        if (empty($errors) AND isset($data)) {

            $this->db->where("id_store", $store_id);

            if($user_id>0)
                $this->db->where("user_id", intval($user_id));

            $storeToDelete = $this->db->get("store", 1);
            $storeToDelete = $storeToDelete->result();

            if (count($storeToDelete) == 0) {
                $errors["Authorization"] = Translate::sprint(Messages::USER_AUTORIZATION_ACCESS);
            } else {

                    //Delete all offers related to this store
                    $this->db->where("store_id", $store_id);
                    $this->db->delete("offer");
                    //Delete all events related to this store
                    $this->db->where("store_id", $store_id);
                    $this->db->delete("event");
                    //Delete store
                    $this->db->where("id_store", $store_id);
                    $this->db->delete("store");

                //Delete all images from this store
                if (isset($storeToDelete[0]->images)) {
                    $images = (array)json_decode($storeToDelete[0]->images);
                    foreach ($images AS $k => $v) {
                        @_removeDir($v);
                    }
                }
                return array(Tags::SUCCESS => 1, "url" => admin_url("store/stores"));
            }
        }


        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }


    public function deleteReview($id_review)
    {

        if ($this->mUserBrowser->isLogged()) {
            $data["user_id"] = $this->mUserBrowser->getAdmin("id_user");
            if (isset($id_review) AND $id_review > 0) {
                $data["id_rate"] = $id_review;
            } else {
                $errors["review"] = Translate::sprint(Messages::REVIEW_NOT_SPECIFIED);
            }


            if (empty($errors) AND isset($data)) {
                $this->db->where("id_rate", $data["id_rate"]);

                $count = $this->db->count_all_results("rate");
                if ($count == 0) {
                    $errors["Authorization"] = Translate::sprint(Messages::USER_AUTORIZATION_ACCESS);
                } else {

                    $this->db->where("id_rate", $data["id_rate"]);
                    $this->db->delete("rate");

                    return array(Tags::SUCCESS => 1, "url" => admin_url("store/reviews"));
                }
            }

        } else {
            $errors["Authentification"] = Translate::sprint(Messages::USER_MISS_AUTHENTIFICATION);

        }


        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }

    public function changeStatus($params=array()){

        $errors = array();
        $data = array();


        if(isset($params['user_id']) and $params['user_id']>0){
            $data['user_id'] = intval($params['user_id']);
        }else{
            $errors[] = Translate::sprint("User is not valid!");
        }

        if(isset($params['store_id']) and $params['store_id']>0){
            $data['id_store'] = intval($params['store_id']);
        }else{
            $errors[] = Translate::sprint("Store is not valid!");
        }


        if(empty($errors) and !empty($data)){
            $data['verified'] = 1;
            $this->db->where($data);
            $c = $this->db->count_all_results('store');
            if($c == 1){

                $this->db->where($data);
                $this->db->update('store',array(
                    'status' => intval($params['status'])
                ));

                return array(Tags::SUCCESS=>1);
            }else{
                $errors[] = Translate::sprint("You are not an owner of this business or this business does not verified yet");
            }
        }


        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
    }

    public function storeAccess($id)
    {

        $this->db->select("status");
        $this->db->from("store");
        $this->db->where("id_store", $id);
        $statusUser = $this->db->get()->row()->status;

        if (intval($statusUser) == 0) {
            $data['status'] = 1;
        } else if (intval($statusUser) == 1) {
            $data['status'] = 0;
        } else {
            $errors["status"] = Translate::sprint(Messages::STATUS_NOT_FOUND);
        }

        if (isset($data) AND empty($errors)) {

            if ($statusUser == 1) {

                //Disable all offers related to this store
                $this->db->where("store_id", $id);
                $this->db->update("offer", $data);
                //Disable all events related to this store
                $this->db->where("store_id", $id);
                $this->db->update("event", $data);
                //Disable store
                $this->db->where("id_store", $id);
                $this->db->update("store", $data);

            } else if ($statusUser == 0) {
                //Enable stores
                $this->db->where("id_store", $id);
                $this->db->update("store", $data);

            }


            return json_encode(array("success" => 1, "url" => admin_url("myStores")));

        } else {
            return json_encode(array("success" => 0, "errors" => $errors));
        }


    }

    public function getReviews($limit = NO_OF_ITEMS_PER_PAGE)
    {


        $id_store = intval(($this->input->get("id")));
        $page = intval($this->input->get("page"));

        if ($id_store > 0) {
            $this->db->where("store_id", $id_store);
        } else {

            if(!GroupAccess::isGranted('user',USER_ADMIN)){
                $stores_id = $this->getOwnStores();
                if (!empty($stores_id))
                    $this->db->where_in("store_id", $stores_id);
                else
                    $this->db->where_in("store_id", array(0));
            }

        }

        $count = $this->db->count_all_results("rate");

         //echo $this->db->last_query(); die();




        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();


        if ($id_store > 0) {
            $this->db->where("store_id", $id_store);
        } else {

            if(!GroupAccess::isGranted('user',USER_ADMIN)){
                $stores_id = $this->getOwnStores();
                if (!empty($stores_id))
                    $this->db->where_in("store_id", $stores_id);
                else
                    $this->db->where_in("store_id", array(0));
            }
        }


        $this->db->select("rate.*,store.name as nameStr");
        $this->db->from("rate");
        $this->db->join("store", "store.id_store = rate.store_id");

        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());
        $this->db->order_by("id_rate", "desc");
        $data = $this->db->get();

        $pagination->links(array(), admin_url("reviews"));


        return array(Tags::SUCCESS => 1, "reviews" => $data->result(), "pagination" => $pagination);
    }

    public function switchTo($old_owner = 0, $new_owner = 0)
    {

        if ($new_owner > 0) {

            $this->db->where("id_user", $new_owner);
            $c = $this->db->count_all_results("user");
            if ($c > 0) {

                $this->db->where("user_id", $old_owner);
                $this->db->update("store", array(
                    "user_id" => $new_owner
                ));

                return TRUE;
            }

        }

        return FALSE;
    }

    public function getCatName($id)
    {

        $this->db->select("name");
        $this->db->where("id_category", $id);
        $store = $this->db->get("category", 1);
        $store = $store->result_array();

        if (count($store) > 0) {
            return $store[0]['name'];
        }

        return "";
    }

    public function getStoreName($id)
    {

        $this->db->select("name");
        $this->db->where("id_store", $id);
        $store = $this->db->get("store", 1);
        $store = $store->result_array();

        if (count($store) > 0) {
            return $store[0]['name'];
        }

        return "";
    }


    public function getMyAllStores($params = array())
    {
        $errors = array();
        $data = array();

        extract($params);

        if (isset($user_id) and $user_id > 0) {

            $this->db->where("status", 1);
            $this->db->where("user_id", intval($user_id));
            $this->db->order_by("id_store", "DESC");
            $data = $this->db->get("store");
            $data = $data->result_array();

            return array(Tags::SUCCESS => 1, Tags::RESULT => $data);
        }

        return array(Tags::SUCCESS => 0);
    }


    public function rate($params = array())
    {
        $errors = array();
        $data = array();

        extract($params);

        if (isset($mac_adr) AND Security::checkMacAddress($mac_adr)) {
            $data['mac_user'] = trim($mac_adr);
        } else {
            // $errors['mac_user'] = INVALID_MAC_ADDRESS ;
        }

        if (isset($rate) AND $rate > 0 AND $rate <= 5) {
            $data['rate'] = $rate;
        } else {
            $errors['rate'] = "Invalid rate";
        }


        if (isset($guest_id) AND $guest_id > 0) {
            $data['guest_id'] = $guest_id;
        } else {
            $errors['guest_id'] = "Invalid guest id";
        }


        if (isset($pseudo) AND $pseudo != null) {
            $data['pseudo'] = $pseudo;
        }

        if (isset($review) AND $review != null) {
            $data['review'] = $review;
        }


        if (isset($store_id) AND $store_id > 0) {
            $data['store_id'] = $store_id;
        } else {
            $errors['store_id'] = STORE_ID;
        }


        if (empty($errors)) {

            $data['date_created'] = date("Y-m-d H:s", time());
            $data['date_created'] = MyDateUtils::convert($data['date_created'], TIME_ZONE, "UTC");

            $this->db->where("store_id", $store_id);
            $this->db->where("guest_id", $guest_id);
            $count = $this->db->count_all_results("rate");

            if ($count == 0) {
                $this->db->insert("rate", $data);
                return array(Tags::SUCCESS => 1);
            }


        }

        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }


    private function calculVotes($datas = array())
    {
        $new_data_results = array();

        foreach ($datas as $key => $data) {


            $new_data_results[$key] = $data;
            $new_data_results[$key]['voted'] = FALSE;

            if (TRUE) {


                //$this->db->where("mac_user",$mac_adr);
                $this->db->where("store_id", $data['id_store']);
                $count = $this->db->count_all_results("rate");


                if ($count > 0) {

                    $new_data_results[$key]['voted'] = TRUE;
                }

                $new_data_results[$key]['nbr_votes'] = 0;
                $new_data_results[$key]['votes'] = 0;


                //calcul votes
                $votes = $this->db->query("SELECT SUM(rate) AS votes, COUNT(*) as nbr_votes FROM rate WHERE store_id=" . $data['id_store']);
                foreach ($votes->result() AS $value) {
                    $new_data_results[$key]['nbr_votes'] = $value->nbr_votes;

                    try {


                        if ($value->votes > 0 and $value->nbr_votes > 0)
                            $new_data_results[$key]['votes'] = (doubleval($value->votes / $value->nbr_votes));
                        else
                            $new_data_results[$key]['votes'] = 0;

                    } catch (Exception $ex) {
                        $new_data_results[$key]['votes'] = 0;
                    }


                    if (!$new_data_results[$key]['votes']) {
                        $new_data_results[$key]['votes'] = 0;
                    }


                }

            }

        }


        return $new_data_results;

    }


    public function getStores($params = array(), $whereArray = array(), $callback = NULL)
    {

        //params login password mac_address
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        if (!isset($page))
            $page = 1;

        if (isset($page) and $page == 0) {
            $page = 1;
        }

        if (isset($limit) AND $limit == 0) {
            $limit = 20;
        } else if ($limit > 0) {

        } else if ($limit == -1) {
            $limit = 100000000;
        }

        if (!empty($whereArray))
            foreach ($whereArray as $key => $value) {
                $this->db->where($key, $value);
            }

        if ($callback != NULL)
            call_user_func($callback, $params);


        if (isset($status) and $status >= 0) {
            $this->db->where("store.status", $status);
        }

        if (isset($category_id) and $category_id > 0) {
            $this->db->where("store.category_id", $category_id);
        }

        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('store.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('store.detail', $search);
            $this->db->group_end();
        }

        if(isset($owner_id) and $owner_id > 0)
        {
            $this->db->where("store.user_id", $owner_id);
        }




        if (isset($user_id) and $user_id > 0) {
            $this->db->where("store.user_id", $user_id);
        }

        if (isset($store_ids) && $store_ids != "") {

            if (preg_match("#^([0-9,]+)$#", $store_ids)) {
                $new_ids = explode(",", $store_ids);
                $this->db->where_in("store.id_store", $new_ids);
            }

        }


        if (isset($store_id) and $store_id > 0) {
            $this->db->where("store.id_store", $store_id);
        }


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


        $this->db->join("category", "category.id_category=store.category_id");
        $count = $this->db->count_all_results("store");

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


        if (isset($status) and $status >= 0) {
            $this->db->where("store.status", $status);
        }

        if (isset($category_id) and $category_id > 0) {
            $this->db->where("store.category_id", $category_id);
        }


        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('store.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('store.detail', $search);
            $this->db->group_end();
        }

        if(isset($owner_id) and $owner_id > 0)
        {
            $this->db->where("store.user_id", $owner_id);
        }

        if (isset($user_id) and $user_id > 0) {
            $this->db->where("store.user_id", $user_id);
        }

        if (isset($store_ids) && $store_ids != "") {

            if (preg_match("#^([0-9,]+)$#", $store_ids)) {
                $new_ids = explode(",", $store_ids);

                $this->db->where_in("store.id_store", $new_ids);
            }

        }


        if (isset($store_id) and $store_id > 0) {
            $this->db->where("store.id_store", $store_id);
        }


        //get most rated
        if ($calcul_distance != "" && isset ($order_by) && $order_by != -2 AND $order_by != -3) {

            $this->db->order_by("distance", "ASC");

        }
        if (isset ($order_by) AND $order_by == -2) {

            $this->db->join("rate", "rate.store_id=store.id_store", "left");

            $this->db->select("(sum(rate.rate)/count(rate.id_rate) ) as 'sumRating',"
                . " count(rate.id_rate) as nbrRates ,category.name as category_name,store.*" . $calcul_distance, FALSE);

        } else if (isset ($order_by) AND $order_by == -3) {//get recent stores get by date

            $this->db->select("category.name as category_name,store.*" . $calcul_distance, FALSE);

        } else {
            //$this->db->join("rating","rating.store_id=store.id_store","left");
            $this->db->select("category.name as category_name,store.*" . $calcul_distance, FALSE);

        }


        if ($calcul_distance != "" && isset ($order_by) && $order_by != -2 && $order_by != -3) {
            $this->db->order_by("distance", "ASC");
        } else if (isset ($order_by) AND $order_by == -2) {

            $this->db->order_by("sumRating", "DESC");

        } else if (isset ($order_by) AND $order_by == -3) {
            $this->db->order_by("store.id_store", "DESC");
        } else {
            $this->db->order_by("store.id_store", "RANDOM");
        }


        if (isset($radius) and $radius > 0 && $calcul_distance != "")
            $this->db->having('distance <= ' . intval($radius), NULL, FALSE);


        $this->db->from("store");
        $this->db->join("category", "category.id_category=store.category_id");

        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());

        //todo : temporary solution to fix result_array on boolean error
        //todo  : group by all fields on the query
        //$this->db->group_by("store.id_store");
        $stores = $this->db->get();
        $stores = $stores->result_array();


        $new_stores_results = array();
        foreach ($stores as $key => $store) {

            $userData = $this->mUserModel->syncUser(array(
                "user_id" => $store['user_id']
            ));

            $new_stores_results[$key] = $store;
            $new_stores_results[$key]['user'] = $userData;

            $new_stores_results[$key]['detail'] = html_entity_decode(
                $new_stores_results[$key]['detail'],
                ENT_QUOTES,
                ENCODING
            );

            if (isset($store['images'])) {
                $images = (array)json_decode($store['images']);
                $new_stores_results[$key]['images'] = array();
                // $new_stores_results[$key]['image'] = $store['images'];
                foreach ($images AS $k => $v) {
                    $new_stores_results[$key]['images'][] = _openDir($v);
                }
            } else {
                $new_stores_results[$key]['images'] = array();
            }


        }


        //get offers
        $this->load->model("offer/offer_model", "mOfferModel");
        foreach ($new_stores_results as $key => $store) {


            $this->db->where("type", "store");
            $this->db->where("int_id", $store['id_store']);
            $glr = $this->db->count_all_results("gallery");
            $new_stores_results[$key]['gallery'] = $this->db->where("type", "store")->where("int_id", $store['id_store'])
                ->count_all_results("gallery");

            $new_stores_results[$key]['nbrOffers'] =  $this->db->where("status",1)->where("store_id",$store['id_store'])->where("status",1)
                ->count_all_results("offer");

            $this->db->where("store_id",$store['id_store']);
            $this->db->where("status",1);
            $this->db->order_by("id_offer","DESC");
            $offer = $this->db->get("offer",1);
            $offer = $offer->result();

            if(count($offer)>0 ){

                if((isset($offer[0]->value_type)  and $offer[0]->value_type == "percent" ) and  isset($offer[0]->offer_value))
                    $new_stores_results[$key]['lastOffer'] = $offer[0]->offer_value." %";
                else if((isset($offer[0]->value_type)  and $offer[0]->value_type == "price") and isset($offer[0]->offer_value)){
                    $new_stores_results[$key]['lastOffer'] = Currency::parseCurrencyFormat($offer[0]->offer_value,$offer[0]->currency);
                }

            }


            $new_stores_results[$key]['link'] = base_url("store/id/" . $store["id_store"]);

        }


        if (count($new_stores_results) < $limit) {
            $count = count($new_stores_results);
        }

        $new_stores_results = $this->calculVotes($new_stores_results);

        if ($calcul_distance != "" && isset ($order_by) && $order_by != -2 && $order_by != -3) {
            $new_stores_results = $this->re_order_featured_item($new_stores_results);
        }

        return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => $new_stores_results);

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


    public function updateStore($params = array())
    {


        //params login password mac_address
        $errors = array();
        $data = array();

        extract($params);


        if (!isset($images))
            $images = array();
        else
            $images = json_decode($images);

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

        if (isset($name) AND $name != "") {
            $data["name"] = $name;
        } else {
            $errors['name'] = Translate::sprint(Messages::STORE_NAME_EMPTY);
        }


        if (isset($address) AND $address != "") {
            $data["address"] = $address;
        } else {
            $errors['address'] = Translate::sprint(Messages::STORE_ADDRESS_EMPTY);
        }


        if (isset($user_id)) {
            $data["user_id"] = intval($user_id);
        } else {
            $errors["user"] = Translate::sprint(Messages::USER_NOT_LOGGED_IN);
        }


        if (isset($detail) AND $detail != "") {
            $data["detail"] = Text::inputWithoutStripTags($detail);
        }

        if (isset($tel) AND $tel != "") {
            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $tel)) {
                $data["telephone"] = $tel;
            } else {
                $errors['tel'] = Translate::sprint(Messages::STORE_PHONE_INVALID);
            }


        }


        if (!isset($latitude) && !isset($longitude)) {
            $errors['location'] = Translate::sprint(Messages::STORE_LOCATION_NOT_FOUND);
        } else {
            $data["latitude"] = $latitude;
            $data["longitude"] = $longitude;
        }

        if (isset($category) AND $category > 0) {
            $data["category_id"] = $category;
        } else {
            $errors["category"] = Translate::sprint(Messages::STORE_CATEGORY_NOT_SET);
        }


        if (empty($errors) AND !empty($data) AND isset($store_id) AND $store_id > 0) {


            $this->db->where("id_store", $store_id);
            $this->db->where("user_id", $data["user_id"]);

            $count = $this->db->count_all_results("store");

            if ($count == 0) {
                $errors["Access"] = Translate::sprint(Messages::USER_ACCESS_DENIED);
            }


        }

        if (empty($errors) AND !empty($data)) {

            $date = date("Y-m-d H:i:s", time());
            $data['updated_at'] = MyDateUtils::convert($date, TIME_ZONE, "UTC");


            $this->db->where("id_store", $store_id);
            $this->db->update("store", $data);


            if (ModulesChecker::isRegistred("gallery")) {
                /*
            *  MANAGE STORE GALLERY
            */

                if (isset($gallery))
                    $gallery = json_decode($gallery, JSON_OBJECT_AS_ARRAY);
                else
                    $gallery = array();

                if (!empty($gallery)) {

                    $imageIds = array();
                    try {

                        if (!empty($gallery)) {
                            foreach ($gallery as $value) {
                                $image_name = $value;
                                if (preg_match("#[a-z0-9]#i", $image_name)) {
                                    $imageIds[$value] = $value;
                                }
                            }
                        }


                        if (!empty($imageIds)) {
                            $this->mGalleryModel->saveGallery("store", $store_id, $imageIds);
                        }

                    } catch (Exception $e) {

                    }

                }
                /*
                *  END MANAGE STORE GALLERY
                *////////////////////////////////////

            }

            return array(Tags::SUCCESS => 1, "url" => admin_url("store/stores"));
        } else {
            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }


    }


    public function createStore($params = array())
    {

        //params login password mac_address
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        /*
         *  MANAGE STORE PHOTOS
         */
        if (isset($images))
            $images = json_decode($images, JSON_OBJECT_AS_ARRAY);
        else
            $images = array();

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

        if (empty($data["images"]) OR $data["images"] == "") {
            $errors['img'] = Translate::sprint("Please select a photo");
        }
        /*
        *  END MANAGE STORE PHOTOS
        *////////////////////////////////////


        if (isset($name) and $name != "") {
            $data['name'] = Text::input(ucfirst($name));
        } else {
            $errors['name'] = Translate::sprint(Messages::STORE_NAME_EMPTY);
        }

        if (isset($address) and $address != "") {
            $data['address'] = Text::input($address);
        } else {
            $errors['address'] = Translate::sprint(Messages::STORE_ADDRESS_EMPTY);
        }

        if (isset($phone) && $phone != "") {

            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $phone)) {
                $data['telephone'] = Text::input($phone);
            } else {
                $errors['telephone'] = Translate::sprint(Messages::STORE_PHONE_INVALID);
            }

        } else {
            $data['telephone'] = "";
        }


        if (isset($latitude) and $latitude != "") {

            $latitude = doubleval($latitude);
            if (is_double($latitude)) {
                $data['latitude'] = doubleval($latitude);
                $this->session->set_userdata("latitude", doubleval($latitude));
            } else {
                $errors['latitude'] = Translate::sprint(Messages::USER_LOCATION_ERROR);
            }

        } else {
            $errors['latitude'] = Translate::sprint(Messages::USER_LOCATION_ERROR);
        }


        if (isset($longitude) and $longitude != "") {

            $longitude = doubleval($longitude);
            if (is_double($longitude)) {
                $data['longitude'] = doubleval($longitude);
                $this->session->set_userdata("longitude", doubleval($longitude));
            } else {
                $errors['longitude'] = Translate::sprint(Messages::USER_LOCATION_ERROR);
            }

        } else {
            $errors['longitude'] = Translate::sprint(Messages::USER_LOCATION_ERROR);
        }


        if (isset($category) and $category > 0) {
            $data['category_id'] = intval($category);
        } else {
            $errors['category_id'] = Translate::sprint(Messages::STORE_CATEGORY_NOT_SET);
        }


        if (isset($detail) and $detail != "") {
            $data['detail'] = Text::inputWithoutStripTags($detail);
        } else {
            $errors['detail'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }


        if (isset($user_id) AND $user_id > 0) {

            $this->db->where("id_user", $user_id);
            $count = $this->db->count_all_results("user");

            if ($count == 0) {
                $errors['user'] = Translate::sprint(Messages::USER_CREATE_ACCOUNT);
            } else {
                $data['user_id'] = $user_id;
            }

        }

        // current date from the system
        $data['date_created'] = date("Y-m-d H:i:s", time());


        if (empty($errors) AND !empty($data)) {


            $nbr_store = UserSettingSubscribe::getUDBSetting($user_id, KS_NBR_STORES);

            if ($nbr_store > 0 || $nbr_store == -1) {

                if (ENABLE_STORE_AUTO == TRUE)
                    $data['status'] = 1;
                else
                    $data['status'] = 0;

                $date = date("Y-m-d H:i:s", time());
                $data['created_at'] = MyDateUtils::convert($date, TIME_ZONE, "UTC");

                $this->db->insert("store",$data);
                $store_id =  $this->db->insert_id();

                $this->db->where("id_store", $store_id);
                $store = $this->db->get("store");
                $store = $store->result_array();

                //refresh number of stores
                if ($nbr_store > 0) {
                    $nbr_store--;
                    UserSettingSubscribe::refreshUSetting($user_id, KS_NBR_STORES, $nbr_store);
                }


                if (ModulesChecker::isRegistred("gallery")) {

                    /*
                    *  MANAGE STORE GALLERY
                    */

                    if (isset($gallery))
                        $gallery = json_decode($gallery, JSON_OBJECT_AS_ARRAY);
                    else
                        $gallery = array();

                    if (!empty($gallery)) {

                        $imageIds = array();
                        try {

                            if (!empty($gallery)) {
                                foreach ($gallery as $value) {
                                    $image_name = $value;
                                    if (preg_match("#[a-z0-9]#i", $image_name)) {
                                        $imageIds[$value] = $value;
                                    }
                                }
                            }


                            if (!empty($imageIds)) {
                                $this->mGalleryModel->saveGallery("store", $store_id, $imageIds);
                            }

                        } catch (Exception $e) {

                        }

                    }
                    /*
                    *  END MANAGE STORE GALLERY
                    *////////////////////////////////////

                }


                return array(Tags::SUCCESS => 1, Tags::RESULT => $store, "url" => admin_url("stores"));

            } else {
                $errors["stores"] = Translate::sprint(Messages::EXCEEDED_MAX_NBR_STORES);
            }


        } else {

            if (isset($errors['store']))
                return array(Tags::SUCCESS => -1, Tags::ERRORS => $errors);
            else
                return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }


        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);

    }


    public function getComments($params)
    {
        $errors = array();
        $data = array();
        extract($params);

        if (!isset($page))
            $page = 1;

        if (!isset($limit))
            $limit = 20;

        if (isset($store_id) && $store_id > 0)
            $this->db->where("store_id", $store_id);

        $count = $this->db->count_all_results("rate");

        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();

        if (isset($store_id) && $store_id > 0)
            $this->db->where("store_id", $store_id);


        $this->db->select("rate.*");
        $this->db->from("rate");

        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());

        $this->db->order_by("rate desc, date_created desc");
        $reviews = $this->db->get();
        $reviews = $reviews->result_array();

        /*
         * getComments
         */


        $this->load->model("User/mUserModel");
        foreach ($reviews as $key => $review) {

            $user = $this->mUserModel->getUserByGuestId($review['guest_id']);
            $image = base_url("/template/backend/images/profile_placeholder.png");

            if ($user != NULL and isset($user[Tags::RESULT][0])) {
                $user = $user[Tags::RESULT][0];
                if (isset($user['images'][0]['200_200']['url'])) {
                    $image = $user['images'][0]['200_200']['url'];
                } else {
                    $image = base_url("template/backend/images/profile_placeholder.png");
                }
            }

            $reviews[$key]['image'] = $image;
        }


        return array(Tags::SUCCESS => 1, Tags::RESULT => $reviews, Tags::COUNT => $count);

    }


    public function saveStore($params = array())
    {

        $data = array();
        $errors = array();


        extract($params);

        if (isset($store_id) AND $store_id > 0 AND isset($user_id) AND $user_id > 0) {

            $this->db->where("user_id", $user_id);
            $this->db->where("type", "stores");
            $obj = $this->db->get("saves");
            $obj = $obj->result_array();


            if (count($obj) == 0) {

                $this->db->insert("saves", array(
                    "user_id" => $user_id,
                    "type" => "stores",
                    "ids" => json_encode(array($store_id), JSON_OBJECT_AS_ARRAY),
                ));

            } else if ($obj[0]['ids'] != NULL) {

                $obj[0]['ids'] = json_decode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY);

                if (!in_array($store_id, $obj[0]['ids'])) {

                    $obj[0]['ids'][] = $store_id;

                    $this->db->where("user_id", $user_id);
                    $this->db->where("type", "stores");
                    $this->db->update("saves", array(
                        "ids" => json_encode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY),
                    ));
                }


            }

            $this->load->model("User/mUserModel");
            $this->mUserModel->addCustomer($user_id, $store_id);

        }

        return array(Tags::SUCCESS => 1);

    }

    public function removeStore($params = array())
    {

        $data = array();
        $errors = array();

        extract($params);

        if (isset($store_id) AND $store_id > 0 AND isset($user_id) AND $user_id > 0) {

            $this->db->where("user_id", $user_id);
            $this->db->where("type", "stores");
            $obj = $this->db->get("saves");
            $obj = $obj->result_array();


            if (count($obj) > 0 and $obj[0]['ids'] != NULL) {

                $obj[0]['ids'] = json_decode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY);

                foreach ($obj[0]['ids'] as $k => $v) {

                    if ($v == $store_id) {
                        unset($obj[0]['ids'][$k]);
                    }

                }

                $this->db->where("user_id", $user_id);
                $this->db->where("type", "stores");
                $this->db->update("saves", array(
                    "ids" => json_encode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY)
                ));

                $this->load->model("User/mUserModel");
                $this->mUserModel->removeCustomer($user_id, $store_id);
            }

        }

        return array(Tags::SUCCESS => 1);

    }


    private function getOwnStores()
    {

        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");
        $isManager = $this->mUserBrowser->getData("manager");


        if ($isManager != 1) {

            $this->db->where("user_id", $user_id);
            $this->db->select("id_store");
            $stores = $this->db->get("store");
            $stores = $stores->result();

            foreach ($stores as $store) {
                $data[] = $store->id_store;
            }
        }

        return $data;

    }


    public function recentlyAdd()
    {

        $c = $this->db->count_all_results("store");

        $this->db->select("store.*,category.name as nameCat");
        $this->db->from("store");
        $this->db->join("category", "category.id_category = store.category_id");
        // allow user to see only the their stores , except the admin
        if ($this->mUserBrowser->getData("manager") != 1) {
            $this->db->where("user_id", $this->mUserBrowser->getData("id_user"));
        }
        $this->db->order_by("id_store", "desc");
        $this->db->limit(NBR_RECENTLY_ADDED_STORES);
        $data = $this->db->get();
        return array(Tags::SUCCESS => 1, "stores" => $data->result(), "count" => $c);
    }


    public function updateFields()
    {


        if (!$this->db->field_exists('verified', 'store')) {
            $fields = array(
                'verified' => array('type' => 'INT', 'after' => 'tags', 'default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('store', $fields);
        }


        if (!$this->db->field_exists('created_at', 'store')) {
            $fields = array(
                'created_at' => array('type' => 'DATETIME', 'after' => 'tags', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('store', $fields);
        }

        if (!$this->db->field_exists('updated_at', 'store')) {
            $fields = array(
                'updated_at' => array('type' => 'DATETIME', 'after' => 'created_at', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('store', $fields);
        }


    }

    /* public function countStoresToEnable(){

        // get the the information user that create a store

        // get all stores in status = 0 disabled


            $this->db->where("status",0);
            $this->db->where("date_created >=",date("Y-m-d H:s",time()));
            $count = $this->db->count_all_results("store");
            if($count > 0 )
            {
                $InactivatedStores = $this->db->get();
                $new_stores_results = array();
                foreach ($InactivatedStores as $key => $store){
                    if(isset($store['user_id']))
                    {
                        $this->db->where("id_user",$store['user_id']);
                        $storeOwner  = $this->db->get('*');
                    }
                }


            }

            return array(
                Tags::SUCCESS=>1,
                Tags::COUNT=>$count
            );


        return array(Tags::SUCCESS=>0);
    } */


}