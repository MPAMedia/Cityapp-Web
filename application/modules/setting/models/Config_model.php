<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Config_model extends CI_Model
{

    public $loadedFiles = "";

    public function __construct()
    {
        parent::__construct();

        if(file_exists('updating.flag')){
            define("ON_UPDATING",TRUE);
        }

        $this->load->model("user/user_model", "mUserModel");
        $this->load->model("user/user_browser", "mUserBrowser");

        $this->defineAllConfigDATABASE();
        $this->loadFile();


    }




    //save settings
    public function save($key, $value)
    {

        if (!$this->db->table_exists('app_config'))
            return true;

        $type = 'N/A';

        if (is_array($value)) {
            $value = json_encode($value, JSON_FORCE_OBJECT);
            $type = 'json';
        } else if (is_numeric($value)) {

            $value = Text::strToNumber($value);

            if (is_float($value)) {
                $value = floatval($value);
                $type = 'float';
            } else if (is_integer($value)) {
                $value = intval($value);
                $type = 'int';
            } else if (is_double('double')) {
                $value = doubleval($value);
                $type = 'double';
            }

        } else if ($value == 'true' OR $value == 'false' OR is_bool($value)) {

            if ($value == 'true')
                $value = 1;
            else if ($value == 'false')
                $value = 0;
            else
                if(function_exists('boolval')) { $value = boolval($value);}


            $type = 'boolean';

        } else {
            $type = 'string';
        }

        $key = Text::input($key);


        $this->db->where('_key', $key);
        $c = $this->db->count_all_results('app_config');

        if ($c == 0) {

            $this->db->insert('app_config', array(
                '_key' => $key,
                'value' => $value,
                '_type' => $type,
                'is_verified' => 1,
                '_version' => APP_VERSION));

        } else {

            $this->db->where('_key', $key);
            $this->db->update('app_config', array(
                'value' => $value,
                '_type' => $type
            ));
        }


        /*if(defined('CONFIG_EMIGRATE')){



        }else{


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
        }*/


    }

    public function removeConfig($key)
    {

        if (defined('CONFIG_EMIGRATE')) {

            $this->db->where('_key', $key);
            $this->db->delete('app_config');

        } else {

            $params = json_decode($this->loadedFiles, JSON_OBJECT_AS_ARRAY);

            if (isset($params[$key]))
                unset($params[$key]);

            $this->loadedFiles = json_encode($params);
            $this->saveSettingsFile($params);

        }

    }


    public function saveSettingsFile($params)
    {

        $data = json_encode($params, JSON_FORCE_OBJECT);

        //save file
        if (file_exists("config/" . PARAMS_FILE . ".json")) {
            $myfile = fopen("config/" . PARAMS_FILE . ".json", "w") or die("Unable to open file!");
            fwrite($myfile, $data);
            fclose($myfile);
        } else {
            //get default file
        }

        return TRUE;
    }

    public function loadFile()
    {
        if ($this->loadedFiles == "") {
            $this->loadedFiles = loadData("config/" . PARAMS_FILE . ".json");
        }
    }


    public function getLogo($size = "200_200")
    {

        $imageUrl = base_url("views/skin/backend/images/logo.png");
        if (!is_array(APP_LOGO)) {

            if (preg_match('#^([a-zA-Z0-9]+)$#', APP_LOGO)) {
                $images = array(APP_LOGO => APP_LOGO);
                $this->save('APP_LOGO', $images);
            } else {
                $images = json_decode(APP_LOGO, JSON_OBJECT_AS_ARRAY);
            }

        } else
            $images = json_decode(APP_LOGO, JSON_OBJECT_AS_ARRAY);

        $imagesData = array();


        if (!is_array($images))
            $images = array($images);


        if (count($images) > 0) {
            foreach ($images as $key => $value)
                $imagesData = _openDir($value);
        }

        if (!empty($imagesData) and isset($imagesData[$size]['url'])) {
            $imageUrl = $imagesData[$size]['url'];
        }


        return $imageUrl;
    }


    public function saveAppConfig($data = array())
    {

        $skip = array(
            'CURRENCIES',
            'HASLINKER',
            'FILE',
            'ANDROID_API',
            'IOS_API'
        );

        foreach ($skip as $key) {
            if (isset($data[$key]))
                unset($data[$key]);
        }


        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->save($key, $value);
            }
        }

        return array(Tags::SUCCESS => 1);
    }


    public function getParams()
    {

        if (!$this->db->table_exists('app_config'))
            return array();

        $params = array();
        $config = $this->db->get('app_config');
        $config = $config->result_array();

        foreach ($config as $value) {

            if ($value['_type'] == 'int') {
                $params[$value['_key']] = intval($value['value']);
            } else if ($value['_type'] == 'float') {
                $params[$value['_key']] = floatval($value['value']);
            } else if ($value['_type'] == 'double') {
                $params[$value['_key']] = doubleval($value['value']);
            } else if ($value['_type'] == 'boolean') {
                if ($value['value'] == 1) {
                    $params[$value['_key']] = TRUE;
                } else {
                    $params[$value['_key']] = FALSE;
                }
            } else {
                $params[$value['_key']] = $value['value'];
            }

        }

        return $params;
    }

    public function defineAllConfigDATABASE()
    {

        if (!$this->db->table_exists('app_config')){
            return;
        }



        if (INIT_PLATFORM == "ns-android") {

            if (!defined('ANDROID_PURCHASE_ID')) {

                $config = $this->db->get('app_config');
                $config = $config->result_array();

                foreach ($config as $value) {

                    if (!defined($value['_key'])) {
                        if ($value['_type'] == 'int') {
                            define($value['_key'], intval($value['value']));
                        } else if ($value['_type'] == 'float') {
                            define($value['_key'], floatval($value['value']));
                        } else if ($value['_type'] == 'double') {
                            define($value['_key'], doubleval($value['value']));
                        } else if ($value['_type'] == 'boolean') {
                            if ($value['value'] == 1) {
                                define($value['_key'], TRUE);
                            } else {
                                define($value['_key'], FALSE);
                            }
                        } else {
                            define($value['_key'], $value['value']);
                        }
                    }

                }

                if (!defined('ANDROID_PURCHASE_ID')) {
                    echo '<h1>Error 404</h1><h4>Error: 112</h4>';
                    exit();
                }

            }

        }else if (INIT_PLATFORM == "ns-ios") {

            if (!defined('IOS_PURCHASE_ID')) {

                $config = $this->db->get('app_config');
                $config = $config->result_array();

                foreach ($config as $value) {

                    if (!defined($value['_key'])) {
                        if ($value['_type'] == 'int') {
                            define($value['_key'], intval($value['value']));
                        } else if ($value['_type'] == 'float') {
                            define($value['_key'], floatval($value['value']));
                        } else if ($value['_type'] == 'double') {
                            define($value['_key'], doubleval($value['value']));
                        } else if ($value['_type'] == 'boolean') {
                            if ($value['value'] == 1) {
                                define($value['_key'], TRUE);
                            } else {
                                define($value['_key'], FALSE);
                            }
                        } else {
                            define($value['_key'], $value['value']);
                        }
                    }

                }

                if (!defined('IOS_PURCHASE_ID')) {
                    echo '<h1>Error 404</h1><h4>Error: 112</h4>';
                    exit();
                }

            }

        }

    }

    public function emigrateAlConfigValues()
    {

        if (!$this->db->table_exists('app_config')){
            $this->createConfigTable();
        }

        $params = json_decode($this->loadedFiles, JSON_OBJECT_AS_ARRAY);

        foreach ($params as $key => $value) {

            $this->db->where('_key', $key);
            $c = $this->db->count_all_results('app_config');

            $type = 'N/A';

            if (is_array($value)) {
                $value = json_encode($value, JSON_FORCE_OBJECT);
                $type = 'json';
            } else if (is_numeric($value)) {

                $value = Text::strToNumber($value);

                if (is_float($value)) {
                    $value = floatval($value);
                    $type = 'float';
                } else if (is_integer($value)) {
                    $value = intval($value);
                    $type = 'int';
                } else if (is_double($value)) {
                    $value = doubleval($value);
                    $type = 'double';
                }

            } else if ($value == 'false' OR $value == 'true') {
                if(function_exists('boolval')) { $value = boolval($value);}
                $type = 'boolean';
            } else if (is_string($value)) {
                $type = 'string';
            }

            if ($c == 0) {

                $this->db->insert('app_config', array(
                    '_key' => $key,
                    'value' => $value,
                    '_type' => $type,
                    'is_verified' => 0,
                    '_version' => APP_VERSION
                ));

            }

            unset($params[$key]);

        }

        $this->db->where('_key', 'CONFIG_EMIGRATE');
        $c = $this->db->count_all_results('app_config');

        if ($c == 0) {

            $this->db->insert('app_config', array(
                '_key' => "CONFIG_EMIGRATE",
                'value' => 1,
                '_type' => "int",
                'is_verified' => 0,
                '_version' => APP_VERSION
            ));

        }

        //save all params
        $this->loadedFiles = NULL;
        $params['CONFIG_EMIGRATE'] = 1;
        $this->saveSettingsFile($params);

    }

    public function createConfigTable()
    {

        $this->load->dbforge();
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            '_key' => array(
                'type' => 'VARCHAR(100)',
                'default' => NULL
            ),
            'value' => array(
                'type' => 'TEXT',
                'default' => NULL
            ),
            '_type' => array(
                'type' => 'VARCHAR(30)',
                'default' => NULL
            ),
            'is_verified' => array(
                'type' => 'INT',
                'default' => NULL
            ),
            'user_id' => array(
                'type' => 'INT',
                'default' => NULL
            ),
            '_version' => array(
                'type' => 'VARCHAR(30)',
                'default' => NULL
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'default'=> NULL
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'default'=> NULL
            )
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('app_config', TRUE, $attributes);


    }


}