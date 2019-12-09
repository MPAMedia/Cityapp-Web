<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->initCurrencies();
    }


    public function getAllCurrencies(){

        $currencies = $this->db->get('currencies');
        $currencies = $currencies->result_array();

        return $currencies;
    }


    public function addNewCurrency($data=array()){


        if(isset( $data["symbol_currency"]))
            $symbol = $data["symbol_currency"];
        else
            $symbol = '';

        if(isset($data["name_currency"]))
            $name = $data["name_currency"];
        else
            $name = '';

        if(isset($data["code_currency"]))
            $code = $data["code_currency"];
        else
            $code = '';

        if(isset($data["format_currency"]))
            $format = $data["format_currency"];
        else
            $format = 1;

        if(isset($data["rate_currency"]))
            $rate = $data["rate_currency"];
        else
            $rate = 0;

        if($code!="" and $name!=""){

            $currency =  array(
                'symbol' => $symbol,
                'format' => intval($format),
                'rate' => $rate,
                'name' => $name,
                'code' => $code
            );

            $this->db->where('code',$code);
            $c = $this->db->count_all_results('currencies');

            if($c==0){
                $this->db->where('code',$code);
                $this->db->insert('currencies',$currency);
            }

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }

    public function editCurrency($data=array()){

        if(isset( $data["symbol_currency"]))
            $symbol = $data["symbol_currency"];
        else
            $symbol = '';

        if(isset($data["name_currency"]))
            $name = $data["name_currency"];
        else
            $name = '';

        if(isset($data["code_currency"]))
            $code = $data["code_currency"];
        else
            $code = '';

        if(isset($data["format_currency"]))
            $format = $data["format_currency"];
        else
            $format = 1;

        if(isset($data["rate_currency"]))
            $rate = $data["rate_currency"];
        else
            $rate = 0;

        if($code!="" and $name!=""){

            $currency =  array(
                'symbol' => $symbol,
                'format' => intval($format),
                'rate' => $rate,
                'name' => $name,
            );

            $this->db->where('code',$code);
            $this->db->update('currencies',$currency);

            return array(Tags::SUCCESS=>1);
        }

        return array(Tags::SUCCESS=>0);
    }

    public function deleteCurrency($code)
    {

        if (isset($code) and !empty($code)) {
            foreach ($code as $key => $value) {
                $this->db->where('code', $value);
                $this->db->delete('currencies');

                return array(Tags::SUCCESS => 1);
            }

            return array(Tags::SUCCESS => 0);
        }
    }

    public function getCurrency($code){

        if($code!="" ){

            $this->db->where('code',$code);
            $currency = $this->db->get('currencies',1);
            $currency = $currency->result_array();

            if(isset($currency[0]))
                return $currency[0];
        }

        NULL;
    }




    public function initCurrencies()
    {

        if (!$this->db->table_exists('app_config') )
            return;

        $this->createTable();

        if($this->db->table_exists('currencies') and $this->db->count_all_results("currencies") > 0 )
            return;


        $params = $this->mConfigModel->getParams();

        if(isset($params['CURRENCIES'])){

            if(!is_array($params['CURRENCIES']))
                $params['CURRENCIES'] = json_decode($params['CURRENCIES'],JSON_OBJECT_AS_ARRAY);

            foreach ($params['CURRENCIES'] as $key => $value){
                $currency =  array(
                    'code' => $value['code'],
                    'symbol' => $value['symbol'],
                    'format' => $value['format'],
                    'rate' => $value['rate'],
                    'name' => $value['name'],
                );
                $this->db->insert('currencies',$currency);
            }

            $this->mConfigModel->removeConfig('CURRENCIES');
        }

    }

    private function createTable(){

        //create table if needed

        $this->load->dbforge();
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'code' => array(
                'type' => 'VARCHAR(10)',
                'default' => NULL
            ),
            'symbol' => array(
                'type' => 'VARCHAR(30)',
                'default' => NULL
            ),
            'name' => array(
                'type' => 'VARCHAR(60)',
                'default' => NULL
            ),
            'format' => array(
                'type' => 'INT',
                'default' => NULL
            ),
            'rate' => array(
                'type' => 'DOUBLE',
                'default' => 0
            ),
            'updated_at' => array(
                'type' => 'DATETIME'
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            ),
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('currencies', TRUE, $attributes);


        //emigrate to database


    }


}

