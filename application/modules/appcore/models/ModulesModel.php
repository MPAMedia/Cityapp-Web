<?php

/**
 * Created by Console.
 * User: {user}
 * Date: {date}
 * Time: {time}
 */
class ModulesModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->createTable();
    }

    //protected $fillable = [];
    protected $table = 'modules';

    //db forge
    public function createTable()
    {

        $this->load->dbforge();
        $this->dbforge->add_field(array(
            'module_name' => array(
                'type' => 'VARCHAR(60)',
            ),
            'version_code' => array(
                'type' => 'INT'
            ),
            'version_name' => array(
                'type' => 'VARCHAR(60)',
            ),
            '_enabled' => array(
                'type' => 'INT'
            ),
            '_order' => array(
                'type' => 'INT'
            ),
            'updated_at' => array(
                'type' => 'DATETIME'
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            ),
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('module_name', TRUE);
        $this->dbforge->create_table('modules', TRUE, $attributes);

    }


}