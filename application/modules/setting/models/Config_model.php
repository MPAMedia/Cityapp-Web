<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_model extends CI_Model
{

    public $loadedFiles = "";

    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

        $this->loadFile();

    }

    public function save($key,$value){

        $params = json_decode($this->loadedFiles,JSON_OBJECT_AS_ARRAY);
        $params[$key] = $value;

        $this->loadedFiles = json_encode($params);

        //save file
        if (file_exists("config/" . PARAMS_FILE . ".json")) {
            $myfile = fopen("config/" . PARAMS_FILE . ".json", "w") or die("Unable to open file!");
            fwrite($myfile, $this->loadedFiles);
            fclose($myfile);
        }else{
            //get default file
        }

    }

    public function saveSettingsFile($params){

        $this->loadedFiles = json_encode($params);

        //save file
        if (file_exists("config/" . PARAMS_FILE . ".json")) {
            $myfile = fopen("config/" . PARAMS_FILE . ".json", "w") or die("Unable to open file!");
            fwrite($myfile, $this->loadedFiles);
            fclose($myfile);
        }else{
            //get default file
        }

        return TRUE;
    }

    public function loadFile()
    {
        if($this->loadedFiles == ""){

            $this->loadedFiles = loadData("config/" . PARAMS_FILE . ".json");

        }
    }


    public function saveAppConfig($data=array()){

        $skip = array("CURRENCIES");

        $params = json_decode($this->loadedFiles,JSON_OBJECT_AS_ARRAY);

        $inputParams = $this->input->post();

        foreach ($params as $key => $conf){

            $key = trim($key);
            if(isset($inputParams[$key])){
                $input = $this->input->post($key);

                if(!in_array($key,$skip)){

                    if($input=="true")
                        $input = TRUE;
                    else if($input=="false")
                        $input = FALSE;
                    else if(is_numeric($input)){
                        if(is_integer($input))
                            $input = intval($input);
                        else if(is_double($input)){
                            $input = doubleval($input);
                        }else if(is_float($input)){
                            $input = floatval($input);
                        }
                    }

                    if ($key=="MAP_DEFAULT_LATITUDE" OR  $key=="MAP_DEFAULT_LATITUDE" ){
                        $input = doubleval($input);
                    }



                    if($key!="APP_LOGO"){
                        $params[$key] = trim($input);
                    }else{
                        if(!is_array($input) AND Json::isJson($input)){
                            $input = json_decode($input,JSON_OBJECT_AS_ARRAY);
                        }

                        if(is_array($input)){
                            foreach ($input as $i){
                                $params[$key] = $i;
                                break;
                            }
                        }

                    }


                }
            }

        }

        $this->saveSettingsFile($params);

        return array(Tags::SUCCESS=>1);
    }

    public function addNewCurrency($data=array()){

        $symbol = $this->input->post("symbol_currency");
        $name = $this->input->post("name_currency");
        $code = $this->input->post("code_currency");
        $format = $this->input->post("format_currency");
        $rate = $this->input->post("rate_currency");


        $params = file_get_contents("config/".PARAMS_FILE.".json");
        $params = json_decode($params,JSON_OBJECT_AS_ARRAY);

        if($code!="" and $code!=""){

            if(!isset($params['CURRENCIES'][$code])){

                $params['CURRENCIES'][$code]=array(
                    "name"      => $name,
                    "code"      =>$code,
                    "symbol"    => $symbol,
                    "format"    => $format,
                    "rate"      => $rate,
                );

                $this->saveSettingsFile($params);
                return array(Tags::SUCCESS=>1);
            }else{
                return array(Tags::SUCCESS=>0,Tags::ERRORS=>array("error"=>Translate::sprint(Messages::CURRENCY_EXIST)));
            }


        }

        return array(Tags::SUCCESS=>0);
    }

    public function editCurrency($data=array()){

        $symbol = $this->input->post("symbol_currency");
        $name = $this->input->post("name_currency");
        $code = $this->input->post("code_currency");
        $format = $this->input->post("format_currency");
        $rate = $this->input->post("rate_currency");

        $params = file_get_contents("config/".PARAMS_FILE.".json");
        $params = json_decode($params,JSON_OBJECT_AS_ARRAY);

        if($code!="" and $code!=""){

            $params['CURRENCIES'][$code]=array(
                "name"      => $name,
                "code"      => $code,
                "symbol"    => $symbol,
                "format"    => $format,
                "rate"      => $rate,
            );

            $this->saveSettingsFile($params);
            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }

    public function deleteCurrency($data=array()){

        $code = $this->input->post("code");

        $params = file_get_contents("config/".PARAMS_FILE.".json");
        $params = json_decode($params,JSON_OBJECT_AS_ARRAY);

        if($code!="" and $code!=""){

            unset($params['CURRENCIES'][$code]);

            $this->saveSettingsFile($params);

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }






}