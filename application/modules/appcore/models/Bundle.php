<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bundle extends CI_Model
{

    public function inArrayClauseWhere($array = array())
    {
        $str = "(";

        foreach ($array as $value) {

            if ($str != "(") {
                $str = $str . ",";
            }

            $str = $str . $value;
        }
        $str = $str . ")";


        return $str;
    }


    public function isBlocked($user_id = 0, $bloked_id)
    {

        $this->db->select("blocked_id");
        $this->db->where("user_id", $user_id);
        $this->db->where("blocked_id", $bloked_id);
        $users = $this->db->count_all_results("block");

        if ($users > 0) {
            return TRUE;
        }

        return FALSE;
    }

    public function getBlockedId($user_id = 0)
    {

        $ids = array();


        //blocked
//        $this->db->select("blocked_id");
//        $this->db->where("user_id",$user_id);
//        $users = $this->db->get("block");
//        $users = $users->result();
//
//        if(count($users)>0){
//            foreach ($users AS $user){
//                $ids[] = $user->blocked_id;
//            }
//        }


        $this->db->select("user_id");
        $this->db->where("user_id !=", $user_id);
        $this->db->where("blocked_id", $user_id);
        $users = $this->db->get("block");
        $users = $users->result();


        if (count($users) > 0) {
            foreach ($users AS $k => $user) {
                $ids[] = $user->user_id;
            }
        }

        return $ids;
    }

    //preparation of post images to json
    public function prepareData($datas = array())
    {

        $new_data_results = array();

        foreach ($datas as $key => $data) {
            $new_data_results[$key] = $data;

            foreach ($data AS $index => $dvalue) {

                if ((preg_match("#(.*)images#i", $index) OR preg_match("#images#i", $index)) AND isset($data[$index])) {


                    $images = (array)json_decode($dvalue);

                    $new_data_results[$key][$index] = array();
                    //$new_data_results[$key] = $dvalue;

                    $i = 0;
                    foreach ($images AS $k => $v) {

                        $new_data_results[$key][$index][$i] = _openDir($v, "");
                        $i++;

                    }

                } else {
                    //$new_data_results[$key]["images"] = array();
                    //$new_data_results[$key]["images"]['image'] = array();
                }
            }

        }


        return $new_data_results;
    }


}