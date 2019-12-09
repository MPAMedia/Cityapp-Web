<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */
class Event_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->updateFields();
    }


    public function getEventsAnalytics($months = array(),$owner_id=0){

        $analytics = array();

        foreach ($months as $key => $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t");

            $start_month = date("Y-m-1", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1");

            $this->db->where("created_at >=", $start_month);
            $this->db->where("created_at <=", $last_month);


            if($owner_id>0)
                $this->db->where('user_id',$owner_id);

            $count = $this->db->count_all_results("event");

            $index = date("m", strtotime($start_month));

            $analytics['months'][intval($index)] = $count;

        }

        if($owner_id>0)
            $this->db->where('user_id',$owner_id);

        $analytics['count'] = $this->db->count_all_results("event");

        $analytics['count_label'] = "Total_events";
        $analytics['color'] = "#00a65a";
        $analytics['icon_tag'] = "<i class=\"mdi mdi-calendar\"></i>";
        $analytics['label'] = "Event";


        return $analytics;

    }


    public function markAsFeatured($params=array()){

        extract($params);


        if (!isset($type) and !isset($id) and !isset($featured))
            return array(Tags::SUCCESS => 0);


        $this->db->where("id_event", $id);
        $this->db->update("event", array(
            "featured" => intval($featured)
        ));

        return array(Tags::SUCCESS => 1);
    }

    public function delete($params = array())
    {

        extract($params);
        $data = array();
        $errors = array();


        if (!isset($id)) {
            $errors["event"] = Translate::sprint(Messages::EVENT_NOT_SPECIFIED);
        } else {
        }


        if (!isset($user_id)) {
            $errors["uid"] = Translate::sprint(Messages::USER_MISS_AUTHENTIFICATION);
        } else {
        }

        if (empty($errors) AND isset($data)) {
            $this->db->where("id_event", $id);

            $eventToDelete = $this->db->get("event", 1);
            $eventToDelete = $eventToDelete->result();
            if (count($eventToDelete) == 0) {
                $errors["event"] = Translate::sprint(Messages::EVENT_NOT_FOUND);
            } else {

                //Delete all images from this event
                if (isset($eventToDelete[0]->images)) {
                    $images = (array)json_decode($eventToDelete[0]->images);
                    foreach ($images AS $k => $v) {
                        _removeDir($v);
                    }
                }

                $this->db->where("id_event", $id);
                $this->db->delete("event");
                return array(Tags::SUCCESS => 1);
            }
        }

        return array(Tags::SUCCESS => 0);
    }

    public function create($params = array())
    {
        $data = array();
        $errors = array();
        extract($params);


        if (isset($user_id) and $user_id > 0) {
            $data["user_id"] = intval($user_id);
        } else {

            $errors["user"] = Translate::sprint(Messages::USER_NOT_LOGGED_IN);

        }

        if (isset($store_id) and $store_id > 0 && isset($user_id) and $user_id > 0) {

            $user_id = intval($user_id);
            $this->db->where("user_id", $user_id);
            $this->db->where("id_store", $store_id);
            $count = $this->db->count_aLL_results("store");

            if ($count == 1) {
                $data['store_id'] = $store_id;
            }

        }

        if (isset($images))
            $images = json_decode($images);
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

        if (isset($data["images"]) and empty($data["images"])) {
            $errors['images'] = Translate::sprint("Please upload an image");
        }

        if (isset($name) AND $name != "") {
            $data["name"] = Text::input($name);
        } else {
            $errors['name'] = Translate::sprint(Messages::EVENT_NAME_INVALID);
        }

        if (isset($desc) AND $desc != "") {
            $data["description"] = Text::input($desc, TRUE);
        } else {
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_INVALID);
        }


        if (isset($address) AND $address != "") {
            $data["address"] = trim($address);
        } else {
            $errors['address'] = Translate::sprint(Messages::STORE_ADDRESS_EMPTY);
        }


        if (isset($detail) AND $detail != "") {
            $data["detail"] = Text::input($detail);
        }

        if (isset($tel) AND $tel != "") {
            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $tel)) {
                $data["tel"] = $tel;
            } else {
                $errors['tel'] = Translate::sprint(Messages::EVENT_PHONE_INVALID);
            }


        }

        if (isset($date_b) AND $date_b != "") {

            if(Text::validateDate($date_b,"Y-m-d"))
            {
                $data["date_b"] = date('Y-m-d',strtotime($date_b));
                //$data["date_b"] = MyDateUtils::convert($data["date_b"],TIME_ZONE,"UTC");

            } else {
                $errors["date_e"] = Translate::sprint(messages::EVENT_DATE_BEGIN_INVALID);
            }

        } else {
            $errors["date_b"] = Translate::sprint(messages::EVENT_DATE_BEGIN_INVALID);
        }

        if (isset($date_e) AND $date_e != "") {
            if (Text::validateDate($date_e, "Y-m-d")) {

                $data["date_e"] = date('Y-m-d',strtotime($date_e));
                //$data["date_e"]  = MyDateUtils::convert( $data["date_e"] ,TIME_ZONE,"UTC");

            } else {
                $errors["date_e"] = Translate::sprint(messages::EVENT_DATE_END_INVALID);
            }
        } else {
            $errors["date_e"] = Translate::sprint(messages::EVENT_DATE_END_INVALID);
        }


        if (isset($website) and $website != "") {

            if (filter_var($website, FILTER_VALIDATE_URL)) {
                $pattern = '/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/';
                if (preg_match($pattern, $website)) {
                    $data['website'] = Text::input($website);
                } else {
                    $errors['website'] = Translate::sprint(Messages::EVENT_WEBSITE_INVALID);
                }
            } else {
                $errors['website'] = Translate::sprint(Messages::EVENT_WEBSITE_INVALID);
            }

        }


        if ((!isset($lat) && !isset($lng)) OR ($lat == 0 AND $lng == 0)) {
            $errors['location'] = Translate::sprint(Messages::EVENT_POSITION_NOT_FOUND);
        } else {
            $data["lat"] = $lat;
            $data["lng"] = $lng;
        }


        //ENABLE_STORE_AUTO


        if (empty($errors) AND !empty($data)) {

            $nbr_events_monthly = UserSettingSubscribe::getUDBSetting($user_id, KS_NBR_EVENTS_MONTHLY);

            if ($nbr_events_monthly > 0 || $nbr_events_monthly == -1) {

                if (ENABLE_EVENT_AUTO == TRUE)
                    $data['status'] = 1;
                else
                    $data['status'] = 0;

                // current date from the system
                $data['date_created'] = date("Y-m-d H:i:s", time());
                $data['created_at'] = $data["date_created"];
                $this->db->insert("event", $data);

                $event_id = $this->db->insert_id();

                if ($nbr_events_monthly > 0) {
                    $nbr_events_monthly--;
                    UserSettingSubscribe::refreshUSetting($user_id, KS_NBR_EVENTS_MONTHLY, $nbr_events_monthly);
                }

                return array(Tags::SUCCESS => 1, "url" => admin_url("event/events"));

            } else {
                $errors["events"] = Translate::sprint(Text::_print(Messages::EXCEEDED_MAX_NBR_EVENTS));
            }
        }

        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }

    public function edit()
    {

        $id = intval($this->input->post("id"));
        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $desc = $this->input->post("desc");
        $website = $this->input->post("website");
        $tel = $this->input->post("tel");
        $date_b = $this->input->post("date_b");
        $date_e = $this->input->post("date_e");
        //$user = Security::decrypt($this->input->post("id_user"));
        $images = $this->input->post("images");
        $store_id = intval($this->input->post("store_id"));

        if ($this->mUserBrowser->isLogged()) {
            $data["user_id"] = $this->mUserBrowser->getAdmin("id_user");
        } else {

            $errors["user"] = Translate::sprint(Messages::USER_NOT_LOGGED_IN);

        }

        if ($store_id > 0 && $this->mUserBrowser->isLogged()) {

            $user_id = $this->mUserBrowser->getAdmin("id_user");
            $this->db->where("user_id", $user_id);
            $this->db->where("id_store", $store_id);
            $count = $this->db->count_aLL_results("store");

            if ($count == 1) {
                $data['store_id'] = $store_id;
            }

        }


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


        $lat = doubleval($this->input->post("lat"));
        $lng = doubleval($this->input->post("lng"));
        $images = $this->input->post("images");


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


        if (isset($id) AND $id > 0) {
            $data["id_event"] = $id;
        } else {
            $errors['name'] = Translate::sprint(messages::EVENT_NOT_SPECIFIED);
        }


        if (isset($name) AND $name != "") {
            $data["name"] = $name;
        } else {
            $errors['name'] = Translate::sprint(messages::EVENT_NAME_EMPTY);
        }

        if (isset($desc) AND $desc != "") {
            $data["description"] = htmlentities($desc);
        } else {
            $errors['description'] = Translate::sprint(messages::EVENT_DESCRIPTION_EMPTY);
        }


        if (isset($address) AND $address != "") {
            $data["address"] = $address;
        } else {
            $errors['address'] = Translate::sprint(messages::EVENT_ADRESSE_EMPTY);
        }


        if (isset($detail) AND $detail != "") {
            $data["detail"] = $detail;
        }

        if (isset($tel) AND $tel != "") {
            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $tel)) {
                $data["tel"] = $tel;
            } else {
                $errors['tel'] = Translate::sprint(messages::EVENT_PHONE_INVALID);
            }


        }

        if(isset($date_b) AND $date_b!="")
        {
            if(Text::validateDate($date_b,"d-m-Y"))
            {
                $data["date_b"] = date('Y-m-d',strtotime($date_b));
                //$data["date_b"]  = MyDateUtils::convert(  $data["date_b"] ,TIME_ZONE,"UTC");

            } else {
                $errors["date_b"] = Translate::sprint(messages::EVENT_DATE_BEGIN_INVALID);
            }

        } else {
            $errors["date_b"] = Translate::sprint(messages::EVENT_DATE_BEGIN_INVALID);
        }

        if(isset($date_e) AND $date_e!="")
        {
            if(Text::validateDate($date_e,"d-m-Y"))
            {
                $data["date_e"] =  date('Y-m-d',strtotime($date_e));
                //$data["date_e"]  = MyDateUtils::convert(  $data["date_e"] ,TIME_ZONE,"UTC");

            } else {
                $errors["date_e"] = Translate::sprint(messages::EVENT_DATE_END_INVALID);
            }
        } else {
            $errors["date_e"] = Translate::sprint(messages::EVENT_DATE_END_INVALID);
        }


        if ($website != "") {
            $pattern = '/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/';
            if (preg_match($pattern, $website)) {
                $data['website'] = Text::input($website);
            } else {
                $errors['website'] = Translate::sprint(Messages::EVENT_WEBSITE_INVALID);
            }
        }


        // $data["status"] = 1;


        if ((!isset($lat) && !isset($lng)) OR ($lat == 0 AND $lng == 0)) {
            $errors['location'] = Translate::sprint(Messages::EVENT_POSITION_NOT_FOUND);
        } else {
            $data["lat"] = $lat;
            $data["lng"] = $lng;
        }


        if (empty($errors) AND !empty($data)) {

            //Enable The event after making the update
            $data["status"] = 1;

            $this->db->where("name", $data["name"]);
            $this->db->where("id_event <> $id ");

            $count = $this->db->count_all_results("event");

            if ($count != 0) {
                $errors["name"] = Translate::sprint(Messages::EVENT_NAME_EXIST);

            }


            $this->db->where("id_event", $data["id_event"]);
            $this->db->update("event", $data);


            return json_encode(array("success" => 1, "url" => admin_url("events")));
        } else {
            return json_encode(array("success" => 0, "errors" => $errors));
        }


    }

    public function switchTo($old_owner = 0, $new_owner = 0)
    {

        if ($new_owner > 0) {

            $this->db->where("id_user", $new_owner);
            $c = $this->db->count_all_results("user");
            if ($c > 0) {

                $this->db->where("user_id", $old_owner);
                $this->db->update("event", array(
                    "user_id" => $new_owner
                ));

                return TRUE;
            }

        }

        return FALSE;
    }

    public function getEvents($params = array(), $whereArray = array(), $callback = NULL)
    {

        //params login password mac_address
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        if (!isset($page))
            $page = 1;


        if (!isset($page) OR $page == 0) {
            $page = 1;
        }

        if (!isset($limit)) {
            $limit = 20;
        }

        if ($limit == 0) {
            $limit = 20;
        }

        if (!empty($whereArray))
            foreach ($whereArray as $key => $value) {
                $this->db->where($key, $value);
            }

        if ($callback != NULL)
            call_user_func($callback, $params);


        if (isset($status) and $status >= 0) {
            $this->db->where("event.status", $status);
        }


        if (isset($user_id) and $user_id >= 0) {
            $this->db->where("event.user_id", $user_id);
        }


        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('event.name', $search);
            $this->db->or_like('event.address', $search);
            $this->db->or_like('event.description', $search);
            $this->db->group_end();
        }


        if (isset($event_ids) && $event_ids != "") {

            if (preg_match("#^([0-9,]+)$#", $event_ids)) {
                $new_ids = explode(",", $event_ids);
                foreach ($new_ids as $key => $id) {
                    $new_ids[$key] = intval($id);
                }

                $this->db->where_in("event.id_event", $new_ids);
            }

        }


        if (isset($event_id) and $event_id > 0) {
            $this->db->where("event.id_event", $event_id);
        }


        $calcul_distance = "";

        if (
            isset($longitude)
            AND
            isset($latitude)

        ) {


            $latitude = doubleval($latitude);
            $longitude = doubleval($longitude);

            $calcul_distance = " , IF( event.lat = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(" . $latitude . ") )
                              * cos( radians( event.lat ) )
                              * cos( radians( event.lng ) - radians(" . $longitude . ") )
                              + sin ( radians(" . $latitude . ") )
                              * sin( radians( event.lat ) )
                            )
                          ) ) ) as 'distance'  ";

        }


        $this->db->join("store", "store.id_store=event.store_id", "left outer");
        $count = $this->db->count_all_results("event");



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
            $this->db->where("event.status", $status);
        }

        if (isset($user_id) and $user_id >= 0) {
            $this->db->where("event.user_id", $user_id);
        }

        if (isset($search) and $search != "") {
            $this->db->group_start();
            $this->db->like('event.name', $search);
            $this->db->or_like('event.address', $search);
            $this->db->or_like('event.description', $search);
            $this->db->group_end();
        }


        if (isset($event_ids) && $event_ids != "") {

            if (preg_match("#^([0-9,]+)$#", $event_ids)) {
                $new_ids = explode(",", $event_ids);

                $this->db->where_in("event.id_event", $new_ids);
            }

        }


        if (isset($event_id) and $event_id > 0) {
            $this->db->where("event.id_event", $event_id);
        }

        $this->db->join("store", "store.id_store=event.store_id", "left outer");

        $this->db->select("store.id_store as 'store_id',store.name as 'store_name',event.*" . $calcul_distance, FALSE);
        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());

        if (!empty($order_by) and $order_by == "by_date_desc") {
            $this->db->order_by("event.date_created", "DESC");
        } else {
            if ($calcul_distance != "") {
                $this->db->order_by("distance", "ASC");
            } else if (isset ($order_by) AND $order_by == -2) {

                $this->db->order_by("event.id_event", "RANDOM");
            } else if (isset ($order_by) AND $order_by == -3) {
                $this->db->order_by("event.id_event", "RANDOM");
            } else {
                $this->db->order_by("event.id_event", "DESC");
            }
        }

        if (isset($radius) and $radius > 0 && $calcul_distance != "")
            $this->db->having('distance <= ' . intval($radius), NULL, FALSE);


        $this->db->from("event");
        //$this->db->join("category","category.id_category=store.category_id");

        $events = $this->db->get();
        $events = $events->result_array();

        if (count($events) < $limit) {
            $count = count($events);
        }

        $new_events_results = array();

        foreach ($events as $key => $event) {

            $new_events_results[$key] = $event;

            if (isset($event['images'])) {

                $images = (array)json_decode($event['images']);
                $new_events_results[$key]['images'] = array();
                // $new_stores_results[$key]['image'] = $store['images'];
                foreach ($images AS $k => $v) {
                    $imgs = _openDir($v);
                    if (!empty($imgs))
                        $new_events_results[$key]['images'][] = $imgs;
                }

            } else {
                $new_events_results[$key]['images'] = array();
            }


            $new_events_results[$key]['link'] = base_url("event/id/" . $event["id_event"]);


            //check event is coming soon


        }


        if ($calcul_distance != "" && isset ($order_by) && $order_by != -2 && $order_by != -3) {
            $new_events_results = $this->re_order_featured_item($new_events_results);
        }

        return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => $new_events_results);

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

    public function saveEvent($params = array())
    {

        $data = array();
        $errors = array();


        extract($params);

        if (isset($event_id) AND $event_id > 0 AND isset($user_id) AND $user_id > 0) {

            $this->db->where("user_id", $user_id);
            $this->db->where("type", "event");
            $obj = $this->db->get("saves");
            $obj = $obj->result_array();


            if (count($obj) == 0) {

                $this->db->insert("saves", array(
                    "user_id" => $user_id,
                    "type" => "event",
                    "ids" => json_encode(array($event_id), JSON_OBJECT_AS_ARRAY),
                ));

            } else if ($obj[0]['ids'] != NULL) {

                $obj[0]['ids'] = json_decode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY);

                if (!in_array($event_id, $obj[0]['ids'])) {

                    $obj[0]['ids'][] = $event_id;

                    $this->db->where("user_id", $user_id);
                    $this->db->where("type", "event");
                    $this->db->update("saves", array(
                        "ids" => json_encode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY),
                    ));
                }


            }

            //  $this->addCustomer($user_id,$event_id);

        }

        return array(Tags::SUCCESS => 1);

    }

    public function removeEvent($params = array())
    {

        $data = array();
        $errors = array();

        extract($params);

        if (isset($event_id) AND $event_id > 0 AND isset($user_id) AND $user_id > 0) {

            $this->db->where("user_id", $user_id);
            $this->db->where("type", "event");
            $obj = $this->db->get("saves");
            $obj = $obj->result_array();


            if (count($obj) > 0 and $obj[0]['ids'] != NULL) {

                $obj[0]['ids'] = json_decode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY);

                foreach ($obj[0]['ids'] as $k => $v) {

                    if ($v == $event_id) {
                        unset($obj[0]['ids'][$k]);
                    }

                }

                $this->db->where("user_id", $user_id);
                $this->db->where("type", "event");
                $this->db->update("saves", array(
                    "ids" => json_encode($obj[0]['ids'], JSON_OBJECT_AS_ARRAY)
                ));

                //   $this->removeCustomer($user_id,$event_id);
            }

        }

        return array(Tags::SUCCESS => 1);

    }


    public function updateEvent($params = array())
    {

        $errors = array();
        $data = array();
        extract($params);


        if (isset($user_id) && $user_id > 0) {
            $data["user_id"] = intval($user_id);
        } else {

            $errors["user"] = Translate::sprint(Messages::USER_NOT_LOGGED_IN);

        }

        if (empty($errors) && isset($store_id)) {

            if ($store_id > 0) {
                $this->db->where("user_id", $user_id);
                $this->db->where("id_store", $store_id);
                $count = $this->db->count_aLL_results("store");

                if ($count == 1) {
                    $data['store_id'] = $store_id;
                }
            } else {
                $data['store_id'] = null;

            }

        }


        if (isset($images))
            $images = json_decode($images);
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


        if (isset($id) AND $id > 0) {
            $data["id_event"] = $id;
        } else {
            $errors['name'] = Translate::sprint(Messages::EVENT_NOT_SPECIFIED);
        }


        if (isset($name) AND $name != "") {
            $data["name"] = Text::input($name);
        } else {
            $errors['name'] = Translate::sprint(Messages::EVENT_NAME_EMPTY);
        }

        if (isset($desc) AND $desc != "") {
            $data["description"] = Text::input($desc, TRUE);
        } else {
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }


        if (isset($address) AND $address != "") {
            $data["address"] = $address;
        } else {
            $errors['address'] = Translate::sprint(Messages::EVENT_ADRESSE_EMPTY);
        }


        if (isset($detail) AND $detail != "") {
            $data["detail"] = $detail;
        }

        if (isset($tel) AND $tel != "") {
            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $tel)) {
                $data["tel"] = $tel;
            } else {
                $errors['tel'] = Translate::sprint(Messages::EVENT_PHONE_INVALID);
            }


        }

        if (isset($date_b) AND $date_b != "") {
            if (Text::validateDate($date_b)) {
                $data["date_b"] = date('Y-m-d', strtotime($date_b));
                $data["date_b"] = MyDateUtils::convert($data["date_b"], TIME_ZONE, "UTC");

            } else {
                $errors["date_b"] = Translate::sprint(Messages::EVENT_DATE_BEGIN_INVALID);
            }

        } else {
            $errors["date_b"] = Translate::sprint(Messages::EVENT_DATE_BEGIN_INVALID);
        }

        if (isset($date_e) AND $date_e != "") {
            if (Text::validateDate($date_e)) {
                $data["date_e"] = date('Y-m-d', strtotime($date_e));
                $data["date_e"] = MyDateUtils::convert($data["date_e"], TIME_ZONE, "UTC");

            } else {
                $errors["date_e"] = Translate::sprint(Messages::EVENT_DATE_END_INVALID);
            }
        } else {
            $errors["date_e"] = Translate::sprint(Messages::EVENT_DATE_END_INVALID);
        }


        if (isset($website) and $website != "") {

            if (filter_var($website, FILTER_VALIDATE_URL)) {
                $pattern = '/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/';
                if (preg_match($pattern, $website)) {
                    $data['website'] = Text::input($website);
                } else {
                    $errors['website'] = Translate::sprint(Messages::EVENT_WEBSITE_INVALID);
                }
            } else {
                $errors['website'] = Translate::sprint(Messages::EVENT_WEBSITE_INVALID);
            }

        }


        if ((!isset($lat) && !isset($lng)) OR ($lat == 0 AND $lng == 0)) {
            $errors['location'] = Translate::sprint(Messages::EVENT_POSITION_NOT_FOUND);
        } else {
            $data["lat"] = $lat;
            $data["lng"] = $lng;
        }


        if (empty($errors) AND !empty($data)) {

            $date = date("Y-m-d H:i:s", time());
            $data['updated_at'] = MyDateUtils::convert($date, TIME_ZONE, "UTC");


            $this->db->where("id_event",$data["id_event"]);
            $this->db->update("event",$data);

            return array(Tags::SUCCESS => 1, "url" => admin_url("event/events"));
        } else {
            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }


    }


    public function getMyAllEvents($params = array())
    {

        $errors = array();
        $data = array();

        extract($params);

        if (isset($user_id) and $user_id > 0) {

            $this->db->where("status", 1);
            $this->db->where("user_id", intval($user_id));
            $this->db->order_by("id_event", "DESC");
            $data = $this->db->get("event");
            $data = $data->result_array();

            return array(Tags::SUCCESS => 1, Tags::RESULT => $data);
        }

        return array(Tags::SUCCESS => 0);
    }

    function hiddenEventsOutOfDate()
    {
        $this->db->select("date_e,id_event");
        $this->db->where("status", 1);
        $events = $this->db->get("event");
        $events = $events->result_array();

        if (count($events) > 0) {
            $currentDate = date("Y-m-d H:i:s", time());
            $currentDate = MyDateUtils::convert($currentDate, TIME_ZONE, "UTC", "Y-m-d H:i:s");
            foreach ($events as $value) {
                if (strtotime($value["date_e"]) < strtotime($currentDate)) {
                    $this->db->where("id_event", $value["id_event"]);
                    $this->db->update("event", array(
                        "status" => 0));
                }
            }
            return array(Tags::SUCCESS => 1);
        } else {
            return array(Tags::SUCCESS => 0);
        }
    }


    public function updateFields()
    {


        if (!$this->db->field_exists('verified', 'event')) {
            $fields = array(
                'verified' => array('type' => 'INT', 'after' => 'tags', 'default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('event', $fields);
        }


        if (!$this->db->field_exists('created_at', 'event')) {
            $fields = array(
                'created_at' => array('type' => 'DATETIME', 'after' => 'tags', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('event', $fields);
        }

        if (!$this->db->field_exists('updated_at', 'event')) {
            $fields = array(
                'updated_at' => array('type' => 'DATETIME', 'after' => 'created_at', 'default' => NULL),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('event', $fields);
        }


    }


}