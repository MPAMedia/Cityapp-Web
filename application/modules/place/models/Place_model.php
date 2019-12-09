<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */
class Place_model extends CI_Model
{


    public function getByPlace($id = 0)
    {

        $this->db->order_by("id_place", "DESC");
        if ($id > 0) {
            $this->db->where("id_place", intval($id));
        }
        $places = $this->db->get("place");
        $places = $places->result_array();

        // foreach ($places as $key => $cat) {
        //     $this->db->where("category_id", $cat['id_category']);
        //     $cats[$key]['nbrStore'] = $this->db->count_all_results("store");
        // }

        return array("success" => 1, "places" => $places);

    }

    public function addPlace($params = array())
    {

        extract($params);
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
        if (empty($name) || $name == '') {
            $errors["name"] = Translate::sprint(Messages::PLACE_NAME_EMPTY);
            return (array("success" => 0, "errors" => $errors));
        }
        if (empty($name_ar) || $name_ar == '') {
            $errors["name_ar"] = Translate::sprint(Messages::PLACE_AR_NAME_EMPTY);
            return (array("success" => 0, "errors" => $errors));
        }
        
        if (empty($latitude) || $latitude == '') {
            $errors["latitude"] = Translate::sprint("Latitude is Empty");
            return (array("success" => 0, "errors" => $errors));
        }
        
        if (empty($longitude) || $longitude == '') {
            $errors["longitude"] = Translate::sprint("Logitude is Empty");
            return (array("success" => 0, "errors" => $errors));
        }


        if (isset($name) AND $name != "") {
            $data["name"] = Text::input($name);
        } else {
            $errors["name"] = Translate::sprint(Messages::PLACE_NAME_EMPTY);
        }
        if (isset($name_ar) AND $name_ar != "") {
            $data["name_ar"] = Text::input($name_ar);
        } else {
            $errors["name_ar"] = Translate::sprint(Messages::PLACE_AR_NAME_EMPTY);
        }
        
        if (isset($latitude) AND $latitude != "") {
            $data["latitude"] = Text::input($latitude);
        } else {
            $errors["latitude"] = Translate::sprint(Messages::PLACE_AR_NAME_EMPTY);
        }
        
        if (isset($longitude) AND $longitude != "") {
            $data["longitude"] = Text::input($longitude);
        } else {
            $errors["longitude"] = Translate::sprint(Messages::PLACE_AR_NAME_EMPTY);
        }


        if (empty($errors) AND isset($data)) {
            $this->db->where("name", $data["name"]);
            $count = $this->db->count_all_results("place");
            if ($count == 0) {
                $this->db->insert("place", $data);
            }

            return (array("success" => 1, "message" => "DONE"));

        } else {
            return (array("success" => 0, "errors" => $errors));
        }
    }

    public function editPlace($params = array())
    {

        extract($params);
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
        if ((empty($name) || $name == '') AND $place_id == 0) {
            $errors["name"] = Translate::sprint("name is empty");
            return (array("success" => 0, "errors" => $errors));
            die();
        }
        else{
            $data['name']=Text::input($name);
        }

        if (empty($name_ar) || $name_ar == '') {
            $errors["name_ar"] = Translate::sprint("ar name is empty");
            return (array("success" => 0, "errors" => $errors));
            die();
        }
        else{
            $data['name_ar']=Text::input($name_ar);

        }
       

        if (isset($latitude) AND $latitude != "") {
            $data["latitude"] = Text::input($latitude);
        } else {
            $errors["latitude"] = Translate::sprint("latitude is empty");
        }

        if (isset($longitude) AND $longitude != "") {
            $data["longitude"] = Text::input($longitude);
        } else {
            $errors["longitude"] = Translate::sprint("longitude is empty");
        }


        if (empty($errors) AND isset($data)) {
            $this->db->where("id_place", $place_id);
            $this->db->update("place", $data);

            // return (array("success" => 0, "errors" => "id:".$place_id));
            return (array("success" => 1, "message" => "DONE"));
        } else {
            return (array("success" => 0, "errors" => $errors));
        }

    }

    public function getPlaces($params=array())
    {

        extract($params);

        if(!isset($language)){
            $language =0;
        }

        $this->db->order_by("id_place", "desc");
        // $this->db->where("language", $language);
        $data = $this->db->get("place");
        $data = $data->result_array();

        foreach ($data as $key => $place) {

            $data[$key]['name'] = Translate::sprint($place['name']);


            // if(isset($latitude) and isset($longitude) and $latitude!=0){

            //     $calcul_distance = " , IF( latitude = 0,99999,  (1000 * ( 6371 * acos (
            //                   cos ( radians(".$latitude.") )
            //                   * cos( radians( latitude ) )
            //                   * cos( radians( longitude ) - radians(".$longitude.") )
            //                   + sin ( radians(".$latitude.") )
            //                   * sin( radians( latitude ) )
            //                 )
            //               ) ) ) as 'distance'  ";

            //     if($calcul_distance!="")
            //         $this->db->having('distance <= '.intval(RADUIS_TRAGET*1024), NULL, FALSE);

            //     $this->db->where("category_id",$cat['id_category']);
            //     $this->db->select("id_store ".$calcul_distance,FALSE);

            // }else{
            //     $this->db->select("id_store");
            // }


            // $this->db->where("category_id",$cat['id_category']);
            // $this->db->where("status",1); //get all enabled stores
            // $r = $this->db->get("store");
            // $c =  $r->result();
            // $c = count($c);


            // $this->db->where("category_id",$cat['id_category']);
            // $this->db->where("status",1); //get all enabled stores
            // $r = $this->db->get("store");
            // $c = count($r->result());

            // $data[$key]['nbr_stores'] = $c;
        }


        return array(Tags::SUCCESS => 1, Tags::RESULT => $data);
    }

    public function delete($id)
    {


        if (isset($id) AND $id > 0) {
           

                $this->db->where("id_place", $id);
                $this->db->delete("place");
            

            return (array("success" => 1, "url" => admin_url("places")));
        } else {
            $errors["place"] = Translate::sprint(Messages::CATEGORY_NOT_FOUND);

            return (array("success" => 0, "errors" => $errors));
        }

    }




}