<?php
/**
 * Created by PhpStorm.
 * User: Amine
 * Date: 11/15/2017
 * Time: 23:00
 */

class Translate{

    public static $data = array();
    public static $l = FALSE;

    public static function changeSessionLang($lang="en"){
        $context =& get_instance();
        $context->load->library('session');
        $context->session->set_userdata("lang",$lang);
        self::getDefaultLangData();
    }

    public static function getLangsCodes(){
        return self::loadYmls();
    }
    public static function getDir(){

        if(!defined("TRANSLATE_DATA")){
            self::getDefaultLangData();
        }

        $data = json_decode(TRANSLATE_DATA,JSON_OBJECT_AS_ARRAY);

        if(isset($data['config'])
            AND isset($data['config']['dir'])){

            $data['config']['dir'] = strtolower($data['config']['dir']);
            define("__DIR",$data['config']['dir']);
            return $data['config']['dir'];
        }else{
            define("__DIR","ltr");
        }


        return "ltr";
    }

    public static function sprint($msgId="",$default=""){


        if(self::$l)
            return "*****";

        $msgId = trim($msgId);
        $default = trim($default);

        if(!defined("TRANSLATE_DATA")){
            self::getDefaultLangData();
        }




        if(defined("TRANSLATE_DATA")){


            if(empty(self::$data)){
                $data = json_decode(TRANSLATE_DATA,JSON_OBJECT_AS_ARRAY);
                self::$data = $data;
            }else{
                $data =  self::$data;
            }


            if(isset($data[$msgId])){


                if(ENVIRONMENT=="development"){

                    if(isset($data[$msgId]) and $data[$msgId]==""){
                        $_SESSION['toTranslate'][$msgId] = "********** NEED TO TRANSLATE ***********";
                    }else if(isset($data[$msgId]) and $data[$msgId]!=""){
                        $_SESSION['toTranslate'][$msgId] = $data[$msgId];
                    }

                }


                return ($data[$msgId]);
            }else{

                if(ENVIRONMENT=="development"){
                    $_SESSION['toTranslate'][$msgId] = "********** NEED TO TRANSLATE ***********";
                }

                if(ENVIRONMENT!="development"){
                    if($default!="")
                        return $default;
                    else
                        return ($msgId);
                } else{
                    return ($msgId);
                }





            }


        }else{
            if(ENVIRONMENT!="development")
                return ucfirst($default);
            else
                return ucfirst($msgId);
        }



    }

    public static function sprintf($msgId="",$args=array(),$default=""){


        if(self::$l)
            return "*****";

        if(!defined("TRANSLATE_DATA")){
            self::getDefaultLangData();
        }


        if(defined("TRANSLATE_DATA")){

            if(empty(self::$data)){
                $data = json_decode(TRANSLATE_DATA,JSON_OBJECT_AS_ARRAY);
                self::$data = $data;
            }else{
                $data =  self::$data;
            }

            if(isset($data[$msgId])){
                if(empty($args))
                    return $data[$msgId];
                else{

                    if(ENVIRONMENT=="development"){

                        if(isset($_SESSION['toTranslate'][$msgId]) and $_SESSION['toTranslate'][$msgId]==""){
                            $_SESSION['toTranslate'][$msgId] = $default;
                        }else if(!isset($_SESSION['toTranslate'][$msgId])){
                            $_SESSION['toTranslate'][$msgId] = $default;
                        }

                    }

                    return vsprintf($data[$msgId],$args);
                }

            }

            else
                return vsprintf($msgId,$args);

        }else{
            return vsprintf($msgId,$args);
        }


    }


    public static function getDefaultLang(){

        $context =& get_instance();
        $lngFromSession = $context->session->userdata('lang');
        if($lngFromSession!=""){
             return $lngFromSession;
        }else{
            return DEFAULT_LANG;
        }

    }

    public static function getDefaultLangData(){

        $context =& get_instance();
        $context->load->library('yaml');


        $lngFromSession = $context->session->userdata('lang');
        if($lngFromSession!=""){
            $fileYaml = Path::getPath(array("languages",trim($lngFromSession).".yml"));
        }else{

            $fileYaml = Path::getPath(array("languages",DEFAULT_LANG.".yml"));
        }



        if(file_exists($fileYaml)){

            $context =& get_instance();
            //load yanl file
            $data = $context->yaml->load($fileYaml);

            if(!defined("TRANSLATE_DATA")){
                define("TRANSLATE_DATA",json_encode($data,JSON_FORCE_OBJECT));
            }

        }

    }


    public static function loadLanguageFromYmlToTranslate($def=""){

        $context =& get_instance();
        $context->load->library('yaml');
        $lngFromSession = $context->session->userdata('lang');

        if($def!="" && preg_match("#[a-zA-Z]{2}#",$def)){
            $fileYaml = Path::getPath(array("languages",trim($def).".yml"));
        }else if($lngFromSession!=""){
            $fileYaml = Path::getPath(array("languages",trim($lngFromSession).".yml"));
        }else{
            $fileYaml = Path::getPath(array("languages",DEFAULT_LANG.".yml"));
        }


        if(!file_exists($fileYaml)){
            $fileYaml = Path::getPath(array("languages",DEFAULT_LANG.".yml"));
        }


        if(file_exists($fileYaml)){

            $context =& get_instance();
            //load yanl file
            $data = $context->yaml->load($fileYaml);

            return $data;
        }


        return  array();

    }

    private static function loadYmls(){

        $data_to_json = array();
        $path = Path::getPath(array("languages"));

        if(!is_dir($path)){
            mkdir($path);
        }

        if ($handle = opendir($path) AND $path!="" AND is_dir($path)) {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." ) {

                    //get instance of object
                    $context =& get_instance();

                    //prepare path of files
                    $fileYaml = Path::addPath($path, array($entry));

                    //load yanl file
                    $data = $context->yaml->load($fileYaml);

                    //prepare config data for lang

                    if(isset($data['config'])
                        AND isset($data['config']['name'])
                        AND isset($data['config']['version'])){

                        $lng = preg_replace("#.yml#", "", $entry);

                        $data_to_json[$lng] = array(
                            "name"  => $data['config']['name'],
                            "version"  => $data['config']['version'],
                            "lang"     => $lng,
                            "dir"       => "ltr"
                        );


                        if(isset($data['config']['dir'])){
                            $data_to_json[$lng]['dir'] = $data['config']["dir"];
                        }

                        unset($data);

                    }


                }
            }


            closedir($handle);
        }

        return $data_to_json;
    }

}