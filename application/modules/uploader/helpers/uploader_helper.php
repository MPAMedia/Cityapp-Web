<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 12/28/18
 * Time: 22:28
 */

class ImageManagerUtils{

    public static function checkAndClearImages(){

        $context = &get_instance();




    }

    public static function getValidImages($userImageStr){

        if($userImageStr=="")
            return array();

        //convert from image ID or json to the array
        if (!is_array($userImageStr) and !preg_match('#^([0-9]+)$#',$userImageStr)) {
            $userImage = json_decode($userImageStr, JSON_OBJECT_AS_ARRAY);
        }else if(!is_array($userImageStr) and preg_match('#^([0-9]+)$#',$userImageStr)) {
            $userImage = array($userImageStr);
        }



        $array = array();

        if(isset($userImage)){
            foreach ($userImage as $dirName){

                $userImage = _openDir($dirName);

                if(!empty($userImage))
                    $array[] = $userImage;

            }
        }else{
            $array = $userImageStr;
        }

        //validate all images

        $new_arrays = array();

        foreach ($array as $key => $img){
            if(empty($img))
                unset($array[$key]);
            else
                $new_arrays[] = $img;
        }

        return $new_arrays;
    }




}