<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer_model extends CI_Model {

    private $limit = 10;

    function __construct()
    {
        parent::__construct();
        define('MAX_CHARS_OFFERS_DESC',2000);
    }

    public function getDefaultCurrencyCode(){
        return DEFAULT_CURRENCY;
    }



    public function markAsFeatured($params=array()){

        extract($params);

        if(isset($typeAuth) and $typeAuth!="admin")
            return array(Tags::SUCCESS=>0);


        if(!isset($type) and !isset($id) and !isset($featured))
            return array(Tags::SUCCESS=>0);


        $this->db->where("id_offer",$id);
        $this->db->update("offer",array(
            "featured"   => intval($featured)
        ));

        return array(Tags::SUCCESS=>1);
    }

    public function switchTo($old_owner=0,$new_owner=0){

        if($new_owner>0){

            $this->db->where("id_user",$new_owner);
            $c = $this->db->count_all_results("user");
            if($c>0){

                $this->db->where("user_id",$old_owner);
                $this->db->update("offer",array(
                    "user_id"   => $new_owner
                ));

                return TRUE;
            }

        }

        return FALSE;
    }

    public function editOffersCurrency(){

        $this->db->select("content,id_offer");
        $offers = $this->db->get("offer");
        $offers = $offers->result_array();

        foreach ($offers as $value){

            $content = $value['content'];

            if(!is_array($content))
                $content = json_decode($content,JSON_OBJECT_AS_ARRAY);

            print_r($content);

            if(isset($content['currency']['code'])){

                $currencyObject = $this->getCurrencyByCode($content['currency']['code']);

                $content = json_encode(array(
                    "description"   =>   $content['description'],
                    "price"         =>   $content['price'],
                    "percent"       =>   $content['percent'],
                    "currency"       =>   $currencyObject
                ),JSON_FORCE_OBJECT);



                $this->db->where("id_offer",$value['id_offer']);
                $this->db->update("offer",array(
                    "content"   => $content
                ));

            }

        }



    }

    public function getCurrencyByCode($code){

        $currencies = json_decode(CURRENCIES,JSON_OBJECT_AS_ARRAY);

        if(isset($currencies[$code])){
            return $currencies[$code];
        }

        return $this->getDefaultCurrency();
    }

    public function parseCurrencyFormat($price,$cData=""){
        //$formats = array("X0,000.00","0,000.00X","X 0,000.00","0,000.00 X","0,000.00","X0,000.00 XX","XX0,000.00","0,000.00XX");

        //emigrate to 1.1.6
        if(!is_array($cData)){
            return $price."".$cData;
        }

        if(empty($cData))
            $currency = $this->getDefaultCurrency();
        else
            $currency = $cData;


        switch ($currency['format']){
            case 1:
                return $currency['symbol'].number_format($price, 2, '.', ',');
                break;
            case 2:
                return number_format($price, 2, '.', ',').$currency['symbol'];
                break;
            case 3:
                return $currency['symbol']." ".number_format($price, 2, '.', ',');
                break;
            case 4:
                return number_format($price, 2, '.', ',')." ".$currency['symbol'];
                break;
            case 5:
                return number_format($price, 2, '.', ',');
                break;
            case 6:
                return $currency['symbol'].number_format($price, 2, '.', ',')." " .$currency['code'];
                break;
            case 7:
                return $currency['code'].number_format($price, 2, '.', ',');
                break;
            case 8:
                return number_format($price, 2, '.', ',').$currency['code'];
                break;
        }

    }

    public function getDefaultCurrency(){

        $currencies = json_decode(CURRENCIES,JSON_OBJECT_AS_ARRAY);
        $d = DEFAULT_CURRENCY;
        foreach ($currencies as $key => $value){
            if($key==$d){
                return $value;
            }
        }

        return ;
    }


    public function changeStatus($params=array()){

        $errors = array();
        $data = array();
        extract($params);

        if(isset($offer_id) and $offer_id>0){

            $this->db->where("id_offer",intval($offer_id));
            $offer = $this->db->get("offer",1);
            $offer = $offer->result();

            if(count($offer)>0){

                $status = $offer[0]->status;

                if($status==1){
                    $this->db->where("id_offer",intval($offer_id));
                    $this->db->update("offer",array(
                        "status"    =>0
                    ));
                }else{
                    $this->db->where("id_offer",intval($offer_id));
                    $this->db->update("offer",array(
                        "status"    =>1
                    ));
                }

            }

        }

        return array(Tags::SUCCESS=>1);
    }

    public function getMyAllOffers($params=array()){

        $errors = array();
        $data = array();

        extract($params);

        if(isset($user_id) and $user_id>0){

            $this->db->where("status",1);
            $this->db->where("user_id",intval($user_id));
            $this->db->order_by("id_offer","DESC");
            $data = $this->db->get("offer");
            $data = $data->result_array();

            return array(Tags::SUCCESS=> 1,Tags::RESULT=>$data);
        }

        return array(Tags::SUCCESS=> 0);
    }

    public function getOffers($params=array())
    {



        extract($params);
        $errors = array();
        $data = array();


        if(!isset($page)){
            $page = 1;
        }

        if(!isset($limit)){
            $limit = NO_OF_ITEMS_PER_PAGE;
        }


        if(isset($search) and $search!=""){
            $this->db->like('offer.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('store.name', $search);
        }


        if( isset($user_id) and $user_id>0){
            $data ['offer.user_id'] = intval($user_id);
        }

        if( isset($store_id) and $store_id>0){
            $data ['offer.store_id'] = intval($store_id);
        }

        if( isset($offer_id) and $offer_id>0){
            $data ['offer.id_offer'] = intval($offer_id);
        }


        if(isset($date_end) and $date_end!="" and Text::validateDate($date_end)){

            $date_end = MyDateUtils::convert(  $date_end ,TIME_ZONE,"UTC","Y-m-d");
            $this->db->where("offer.date_end >=",$date_end);
        }


        if(isset($user_id) and $user_id>0){

            $this->db->where("offer.user_id",intval($user_id));

        }else if(isset($is_super) AND $is_super){

        }else{
            $this->db->where("offer.status",1);
        }


        //distance
        $calcul_distance = "";
        if(
            isset($longitude)
            AND
            isset($latitude)

        ){

            $longitude = doubleval($longitude);
            $latitude = doubleval($latitude);



            $calcul_distance = " , IF( store.latitude = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(".$latitude.") )
                              * cos( radians( store.latitude ) )
                              * cos( radians( store.longitude ) - radians(".$longitude.") )
                              + sin ( radians(".$latitude.") )
                              * sin( radians( store.latitude ) )
                            )
                          ) ) ) as 'distance'  ";


        }


        $this->db->where($data);
        $this->db->join("store","store.id_store=offer.store_id");
        $count = $this->db->count_all_results("offer");



        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();



        if($count==0)
            return array(Tags::SUCCESS=>1,"pagination"=>$pagination,  Tags::COUNT=>$count,  Tags::RESULT=>array());


        if(isset($search) and $search!=""){
            $this->db->like('offer.name', $search);
            $this->db->or_like('store.address', $search);
            $this->db->or_like('store.name', $search);
        }

        if(isset($date_end) and $date_end!="" and Text::validateDate($date_end)){
            $date_end = MyDateUtils::convert(  $date_end ,TIME_ZONE,"UTC","Y-m-d");
            $this->db->where("offer.date_end >=",$date_end);
        }

        if(isset($user_id) and $user_id>0){

            $this->db->where("offer.user_id",intval($user_id));

        }else if(isset($is_super) AND $is_super){

        }else{
            $this->db->where("offer.status",1);
        }

        $this->db->join("store","store.id_store=offer.store_id");

        $this->db->select("offer.*,store.latitude,store.longitude,store.name as 'store_name'".$calcul_distance,FALSE);


        $this->db->where($data);
        $this->db->from("offer");
        $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());

        if($calcul_distance=="")
            $this->db->order_by("offer.id_offer","DESC");
        else
            $this->db->order_by("distance","ASC");


        $this->db->group_by("offer.id_offer");

        if(isset($radius) and $radius>0 && $calcul_distance!="")
            $this->db->having('distance <= '.intval($radius), NULL, FALSE);

        $stores = $this->db->get();
        $offers = $stores->result_array();


        if(count($offers)<$limit){
            $count = count($offers);
        }

        foreach ($offers as $key => $offer){

            $imgs = _openDir($offer['image']);
            $offers[$key]['image'] = $imgs;
            $offers[$key]['link'] = base_url("offer/id/".$offer["id_offer"]);
        }


        if($calcul_distance!=""  && isset ($order_by) && $order_by!=-2 &&  $order_by!=-3){
            $offers = $this->re_order_featured_item($offers);
        }


        return array(Tags::SUCCESS=>1,"pagination"=>$pagination,  Tags::COUNT=>$count,  Tags::RESULT=>$offers);
    }



    public function re_order_featured_item($data=array()){

        $new_data = array();

        foreach ($data as $key => $value){
            if($value['featured']==1){
                $new_data[] = $data[$key];
                unset($data[$key]);
            }
        }


        foreach ($data as  $value){
            $new_data[] = $value;
        }

        return $new_data;
    }


    public function addOffer($params=array()){

        extract($params);


        $errors = array();
        $data = array();


        if(isset($image) and ($image!="" OR !empty($image))){

            if(!is_array($image))
                $images = json_decode($image,JSON_OBJECT_AS_ARRAY);
            else
                $images = $image;


            if(count($images)>0){
                foreach ($images as $image){
                    $data['image']  = $image;
                    break;
                }
            }
        }

        if(isset($data["images"]) and empty($data["images"])){
            $errors['images'] = Translate::sprint("Please upload an image");
        }

        if(isset($store_id) and $store_id>0){
            $data['store_id'] = intval($store_id);
        }else{
            $errors['store_id'] = Translate::sprint(Messages::STORE_NOT_SPECIFIED);
        }


        if(isset($name) and $name!=""){
            $data['name'] = Text::input($name);
        }else{
            $errors['name'] = Translate::sprint("Offer name is empty!");
        }

        if(isset($name_ar) and $name_ar!=""){
            $data['name_ar'] = Text::input($name_ar);
        }else{
            $errors['name_ar'] = Translate::sprint("Offer AR name is empty!");
        }

        if(isset($description) and $description!=""){
            $description = Text::inputText($description);
        }else{
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }

        if(isset($description_ar) and $description_ar!=""){
            $description_ar = Text::inputText($description_ar);
        }else{
            $errors['description_ar'] = Translate::sprint("Offer AR Description is Empty ");
        }


        if(isset($price) and intval($price)>0){
            $price = floatval($price);
        }else{
            $price = 0;
        }


        if(isset($percent) and (intval($percent)>0 || intval($percent)<0  )){
            $percent = floatval($percent);
        }else{
            $percent = 0;
        }

        if(isset($date_start) and Text::validateDate($date_start)){
            $date_start = MyDateUtils::convert(  $date_start  ,TIME_ZONE,"UTC","Y-m-d");
            $data['date_start'] = $date_start;
        }else{
            $errors['date_start'] = Translate::sprint(Messages::DATE_BEGIN_NOT_VALID);
        }

        if(isset($date_end) and Text::validateDate($date_end)){

            $date_end = MyDateUtils::convert(  $date_end  ,TIME_ZONE,"UTC","Y-m-d");
            $data['date_end'] = $date_end;
        }else{
            //$errors['date_end'] = Text::_print("Date of end is not valid!");
            $data['date_end'] = "";
        }

        if( isset($user_id) and $user_id>0){
            $data['user_id'] = $user_id;
        }else{
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }

        if(!isset($user_type) OR (isset($user_type) and $user_type=="manager")){

            if(empty($errors) and $store_id>0 ){

                $this->db->where("user_id",$user_id);
                $this->db->where("id_store",$store_id);
                $this->db->where("status",1);
                $store = $this->db->get("store",1);
                $store = $store->result_array();
                if(count($store)==0){
                    $errors['store'] = Translate::sprint(Messages::USER_NOT_FOUND);;
                }

            }

        }

        //link image with offer

        if(isset($currency) and $currency!=""){
            $currencyObject = $this->getCurrencyByCode($currency);
        }else{
            $currencyObject = $this->getDefaultCurrency($currency);
        }


        if(empty($errors) and  isset($user_id) and $user_id>0){

            $this->db->where("user_id",$user_id);
            $user_setting = $this->db->get("setting");
            $user_setting = $user_setting->result();

            if(count($user_setting)>0){
                $user_setting = $user_setting[0];
                $nbr_offers_monthly = $user_setting->nbr_offer_monthly;

                if($nbr_offers_monthly>0 || $nbr_offers_monthly==-1){

                    $data['content'] = json_encode(array(
                        "description"   =>   substr($description,0,MAX_CHARS_OFFERS_DESC),
                        "price"         =>   $price,
                        "percent"       =>   $percent,
                        "currency"       =>   $currencyObject
                    ),JSON_FORCE_OBJECT);

                    $data['content_ar'] = json_encode(array(
                        "description"   =>   substr($description_ar,0,MAX_CHARS_OFFERS_DESC),
                        "price"         =>   $price,
                        "percent"       =>   $percent,
                        "currency"       =>   $currencyObject
                    ),JSON_FORCE_OBJECT);


                    if(ENABLE_OFFER_AUTO==TRUE || (isset($typeAuth) and $typeAuth=="admin"))
                        $data['status'] = 1;
                    else
                        $data['status'] = 0;

                    $this->db->insert("offer",$data);

                    if($nbr_offers_monthly>0){

                        $this->db->where("user_id",$user_id);
                        $this->db->update("setting",array(
                            "nbr_offer_monthly" => ($nbr_offers_monthly-1)
                        ));

                    }

                    return array(Tags::SUCCESS=>1);

                }else{
                    $errors["offers"] = Translate::sprint(Messages::EXCEEDED_MAX_NBR_STORES);
                }

            }

        }else{
            $errors['store'] = Text::_print("Error!");
        }

        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);

    }



    public function editOffer($params=array()){

        extract($params);


        $errors = array();
        $data = array();



        if(isset($image) and ($image!="" OR !empty($image))){

            if(!is_array($image))
                $images = json_decode($image,JSON_OBJECT_AS_ARRAY);
            else
                $images = $image;

            if(count($images)>0){
                foreach ($images as $image){
                    $data['image']  = $image;
                    break;
                }
            }
        }



        if(isset($data["images"]) and empty($data["images"])){
            $errors['images'] = Translate::sprint("Please upload an image");
        }


        if(isset($name) and $name!=""){
            $data['name'] = Text::input($name);
        }else{
            //$errors['store'] = Text::_print("Store id is messing");
        }

        if(isset($name_ar) and $name_ar!=""){
            $data['name_ar'] = Text::input($name_ar);
        }else{
            //$errors['store'] = Text::_print("Store id is messing");
        }

        if(isset($store_id) and $store_id>0){
            $data['store_id'] = intval($store_id);
        }else{
            $errors['store'] = Translate::sprint("Please_select_store","Please select store");
        }

        if(isset($offer_id) and $offer_id>0){
            $data['id_offer'] = intval($offer_id);
        }else{
            $errors['id_offer'] = Translate::sprint(Messages::OFFER_ID_MISSING);
        }

        if(isset($description) and $description!=""){
            $description = Text::inputText($description);
        }else{
            $errors['description'] = Translate::sprint(Messages::EVENT_DESCRIPTION_EMPTY);
        }

        if(isset($description_ar) and $description_ar!=""){
            $description_ar = Text::inputText($description_ar);
        }else{
            $errors['description_ar'] = Translate::sprint("Offer AR Description is empty ");
        }

        if(isset($price) and intval($price)>0){
            $price = floatval($price);
        }else{
            $price = 0;
        }



        if(isset($percent) and (intval($percent)>0 || intval($percent)<0  )){
            $percent = floatval($percent);
        }else{
            $percent = 0;
        }


//        if(isset($date_start) and Text::validateDate($date_start)){
//            $data['date_start'] = Text::validateDate($date_start);
//        }else{
//            $errors['date_start'] = Text::_print("Date of begin is not valid!");
//        }

        if(isset($date_end) and Text::validateDate($date_end)){

            $date_end = MyDateUtils::convert(  $date_end  ,TIME_ZONE,"UTC","Y-m-d");
            $data['date_end'] = $date_end;
        }else{
            //$errors['date_end'] = Text::_print("Date of end is not valid!");
            $data['date_end'] = "";
        }



        if( isset($user_id) and intval($user_id)>0){
            $data['user_id'] = $user_id;
        }else{
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }

        if(empty($errors) and $store_id>0 ){

            $this->db->where("user_id",$user_id);
            $this->db->where("id_store",$store_id);
            $this->db->where("status",1);
            $c = $this->db->count_all_results("store");
            if($c==0){
                $errors['store'] = Translate::sprint(Messages::STORE_ID_NOT_VALID);
            }

        }

        //link image with offer

        if(isset($currency) and $currency!=""){
            $currencyObject = $this->getCurrencyByCode($currency);
        }else{
            $currencyObject = $this->getDefaultCurrency($currency);
        }

        if(empty($errors) and  isset($user_id) and $user_id>0){

            $data['content'] = json_encode(array(
                "description"   =>   substr($description,0,MAX_CHARS_OFFERS_DESC),
                "price"         =>   $price,
                "percent"       =>   $percent,
                "currency"       =>   $currencyObject
            ),JSON_FORCE_OBJECT);
        
            $data['content_ar'] = json_encode(array(
                "description"   =>   substr($description_ar,0,MAX_CHARS_OFFERS_DESC),
                "price"         =>   $price,
                "percent"       =>   $percent,
                "currency"       =>   $currencyObject
            ),JSON_FORCE_OBJECT);


            //$data['status'] = 1;
            $this->db->where("id_offer",$offer_id);
            $this->db->where("user_id",$user_id);
            $this->db->update("offer",$data);

            return array(Tags::SUCCESS=>1);

        }else{
            $errors['store'] = Text::_print("Error! ");
        }

        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);

    }


    public function deleteOffer($params=array())
    {

        extract($params);
        $errors = array();
        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");
        $authType = $this->mUserBrowser->getData("authType");

        if(isset($offer_id) and $offer_id>0 && $user_id>0){

            if($authType=="manager")
                $this->db->where("user_id",$user_id);


            $this->db->where("id_offer",$offer_id);
            $this->db->delete("offer");

            return array(Tags::SUCCESS=>1);

        }

        return array(Tags::SUCCESS=>0);
    }


    function hiddenOfferOutOfDate()
    {
        $this->db->select("date_end,id_offer");
        $this->db->where("status",1);
        $offers = $this->db->get("offer",1);
        $offers = $offers->result_array();
        if(count($offers)>0){
                $currentDate = date("Y-m-d H:i:s",time()) ;
                $currentDate = MyDateUtils::convert($currentDate,TIME_ZONE,"UTC","Y-m-d H:i:s");
                foreach ($offers as $value) {
                    if (strtotime($value["date_end"]) < strtotime($currentDate))
                    {
                        $this->db->where("id_offer",$value["id_offer"]);
                        $this->db->update("offer",array(
                            "status"=>0));
                    }
                }
                return array(Tags::SUCCESS=>1);
        }else
        {
            return array(Tags::SUCCESS=>0);
        }
    }


}