<?php

class TokenSetting{


    public static function createToken($uid=0,$type="unspecified"){

        $context = &get_instance();

        $token = md5(time() . rand(0, 999));

        $context->db->insert('token', array(
            "id" => $token,
            "uid" => $uid,
            "type" => $type,
            "created_at" => date("Y-m-d", time())
        ));

        return $token;
    }



    public static function isValid($uid=0,$type="unspecified",$token=""){

        $context = &get_instance();

        $context->db->where("uid",$uid);
        $context->db->where("type",$type);
        $context->db->where("id",$token);
        $count = $context->db->count_all_results('token');
        if($count==1)
            return TRUE;

        return FALSE;
    }


    public static function clear($token=""){

        $context = &get_instance();
        $context->db->where("id",$token);
        $context->db->delete('token');

    }


}