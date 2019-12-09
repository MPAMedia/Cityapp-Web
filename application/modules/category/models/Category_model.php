<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */
class Category_model extends CI_Model
{


    public function getByCategory($id = 0)
    {

        $this->db->order_by("name");
        if ($id > 0) {
            $this->db->where("id_category", intval($id));
        }
        $cats = $this->db->get("category");
        $cats = $cats->result_array();

        foreach ($cats as $key => $cat) {
            $this->db->where("category_id", $cat['id_category']);
            $cats[$key]['nbrStore'] = $this->db->count_all_results("store");

            //check image avalailability
            $is_valid = $this->uploader->checkAvailabilityID($cat['image']);
            if($is_valid==FALSE){
                $this->db->where('id_category',$cat['id_category']);
                $this->db->update('category',array(
                    'image' => ""
                ));
                $cats[$key]['image'] = "";
            }

        }

        return array("success" => 1, "cats" => $cats);

    }

    public function addCategory($params = array())
    {

        extract($params);

        if (empty($cat) || $cat == '') {
            $errors["name"] = Translate::sprint(Messages::CATEGORY_NAME_EMPTY);
            return (array("success" => 0, "errors" => $errors));
        }

        if (isset($image) and $image != "") {
            $data["image"] = json_encode($image, JSON_FORCE_OBJECT);
            $image = json_decode($data["image"], JSON_OBJECT_AS_ARRAY);
            foreach ($image as $img) {
                $data["image"] = $img;
                break;
            }

        }

        if (isset($cat) AND $cat != "") {
            $data["name"] = Text::input($cat);
        } else {
            $errors["name"] = Translate::sprint(Messages::CATEGORY_EMPTY);
        }


        if (empty($errors) AND isset($data)) {
            $this->db->where("name", $data["name"]);
            $count = $this->db->count_all_results("category");
            if ($count == 0) {
                $this->db->insert("category", $data);
            }

            return (array("success" => 1, "message" => "DONE"));

        } else {
            return (array("success" => 0, "errors" => $errors));
        }
    }

    public function editCategory($params = array())
    {

        extract($params);

        if ((empty($cat) || $cat == '') AND $cat_id == 0) {
            $errors["name"] = Translate::sprint(Messages::CATEGORY_NAME_EMPTY);
            return (array("success" => 0, "errors" => $errors));
            die();
        }


        if (isset($image) and $image != "") {
            $data["image"] = json_encode($image, JSON_FORCE_OBJECT);
            $image = json_decode($data["image"], JSON_OBJECT_AS_ARRAY);
            foreach ($image as $img) {
                $data["image"] = $img;
                break;
            }

        }

        if (isset($cat) AND $cat != "") {
            $data["name"] = Text::input($cat);
        } else {
            $errors["name"] = Translate::sprint(Messages::CATEGORY_EMPTY);
        }


        if (empty($errors) AND isset($data)) {

            $this->db->where("id_category", $cat_id);
            $this->db->update("category", $data);

            return (array("success" => 1, "message" => "DONE"));
        } else {
            return (array("success" => 0, "errors" => $errors));
        }
    }

    public function getCategories($params=array())
    {

        extract($params);


        $this->db->order_by("name");
        $data = $this->db->get("category");
        $data = $data->result_array();


        foreach ($data as $key => $cat) {

            $data[$key]['name'] = Translate::sprint($cat['name']);


            if(isset($latitude) and isset($longitude) and $latitude!=0){

                $calcul_distance = " , IF( latitude = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(".$latitude.") )
                              * cos( radians( latitude ) )
                              * cos( radians( longitude ) - radians(".$longitude.") )
                              + sin ( radians(".$latitude.") )
                              * sin( radians( latitude ) )
                            )
                          ) ) ) as 'distance'  ";

                if($calcul_distance!="")
                    $this->db->having('distance <= '.intval(RADUIS_TRAGET*1024), NULL, FALSE);

                $this->db->where("category_id",$cat['id_category']);
                $this->db->select("id_store ".$calcul_distance,FALSE);

            }else{
                $this->db->select("id_store");
            }


            $this->db->where("category_id",$cat['id_category']);
            $this->db->where("status",1); //get all enabled stores
            $r = $this->db->get("store");
            $c =  $r->result();
            $c = count($c);


            $this->db->where("category_id",$cat['id_category']);
            $this->db->where("status",1); //get all enabled stores
            $r = $this->db->get("store");
            $c = count($r->result());

            $data[$key]['nbr_stores'] = $c;
        }


        return array(Tags::SUCCESS => 1, Tags::RESULT => $data);
    }

    public function delete($id)
    {


        if (isset($id) AND $id > 0) {
            $this->db->where("category_id", $id);
            $c = $this->db->count_all_results("store");

            if ($c == 0) {

                $this->db->where("id_category", $id);
                $cat = $this->db->get("category",1);
                $cat = $cat->result();

                if(count($cat)>0){
                    $this->load->model("uploader/uploader_model");
                    $image = $cat[0]->image;
                    $this->uploader_model->delete($image);
                }

                $this->db->where("id_category", $id);
                $this->db->delete("category");
            } else {
                $errors["category"] = Translate::sprint(Messages::CATEGORY_DELETE);

                return (array("success" => 0, "errors" => $errors));
            }

            return (array("success" => 1, "url" => admin_url("categories")));
        } else {
            $errors["category"] = Translate::sprint(Messages::CATEGORY_NOT_FOUND);

            return (array("success" => 0, "errors" => $errors));
        }

    }


    public function getCategoriesByGroup($parent_id=0)
    {


        $this->db->where("parent_id", $parent_id);
        $this->db->order_by("name");

        $data = $this->db->get("category");
        $data = $data->result_array();

        $group_categories = array();

        $i = 0;
        foreach ($data as $key => $cat) {

            if(isset($cat['image']) and $cat['image']!="")
                $cat["image"] = _openDir($cat['image']);

            $cat['name'] = Translate::sprint($cat['name']);



            $this->db->where("parent_id", $cat['id_category']);
            $this->db->order_by("_order", "asc");
            $sub_categories = $this->db->get("category");
            $sub_categories = $sub_categories->result_array();

            $group_categories[$i] = $cat;
            $gid = $i;
            $i++;

            $this->db->where("category_id",$cat['id_category']);
            $group_nbrStores = $this->db->count_all_results("store");

            foreach ($sub_categories as $sub_category) {

                $this->db->where("category_id",$sub_category['id_category']);
                $nbrStores = $this->db->count_all_results("store");


                $group_nbrStores = $group_nbrStores+$nbrStores;

                $group_categories[$i] = $sub_category;
                $group_categories[$i]['nbr_stores'] = $nbrStores;


                if(isset($sub_category['image']) and $sub_category['image']!="")
                    $group_categories[$i]["image"] = _openDir($sub_category['image']);

                $i++;
            }

            $group_categories[$gid]['nbr_stores'] = $group_nbrStores;

        }

        return array(Tags::SUCCESS => 1, Tags::RESULT => $group_categories);
    }





}