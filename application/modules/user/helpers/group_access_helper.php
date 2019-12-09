<?php
/**
 * Created by PhpStorm.
 * User: amine
 * Date: 1/15/19
 * Time: 12:32
 */


class GroupAccess{

    private static $permissions=NULL;

    public static function registerActions($module,$actions=array()){

        $context = &get_instance();

        ///////////// CRATE TABLE IF NEEDED /////////////
        GroupAccess::createTableModuleActions();
        GroupAccess::createTableGroupAccess();
        //////////////////////////////////////////////////



        $context->db->where('module',$module);
        $count = $context->db->count_all_results("module_actions");

        if($count ==0){
            $array = array(
                'module' => trim($module),
                'actions' => json_encode($actions,JSON_OBJECT_AS_ARRAY)
            );
            $context->db->insert("module_actions",$array);
        }
    }

    public static function getModuleActions(){

        $context = &get_instance();
        $actions = $context->db->get("module_actions");
        $actions = $actions->result_array();

        $modules_actions = array();

        foreach ($actions as $value){
            $modules_actions[$value['module']] = json_decode($value['actions'],JSON_OBJECT_AS_ARRAY);
        }

        return $modules_actions;
    }

    public static function validateActions($actions = array()){

        foreach ($actions as $key => $value){
            if(!ModulesChecker::isEnabled($key))
                unset($actions[$key]);
        }

        return $actions;
    }

    public static function validateGrpAcc($grps = array()){

        foreach ($grps as $key => $value){

            $value['permissions'] = json_decode($value['permissions'],JSON_OBJECT_AS_ARRAY);

            $value['permissions'] = self::validateActions( $value['permissions']);

            $grps[$key]['permissions'] = json_encode( $value['permissions'] );
        }

        return $grps;
    }

    public static function initGrant(){

        if(self::$permissions==NULL) {

            $context = &get_instance();
            $grp_id = $context->mUserBrowser->getData("grp_access_id");

            $context->db->where('id', $grp_id);
            $grp = $context->db->get("group_access", 1);
            $grp = $grp->result_array();

            if (count($grp) > 0) {
                $grp = $grp[0];
                self::$permissions = json_decode($grp['permissions'], JSON_OBJECT_AS_ARRAY);
            }

        }

    }

    public static function isGrantedUser($user_id,$module,$action=""){

        $permissions = array();

        $context = &get_instance();

        $context->db->select('grp_access_id');
        $context->db->where('id_user',$user_id);
        $user = $context->db->get('user',1);
        $user = $user->result();

        if(count($user)==0)
            return FALSE;

        $grp_id = $user[0]->grp_access_id;

        $context->db->where('id', $grp_id);
        $grp = $context->db->get("group_access", 1);
        $grp = $grp->result_array();

        if (count($grp) > 0) {
            $grp = $grp[0];
            $permissions = json_decode($grp['permissions'], JSON_OBJECT_AS_ARRAY);
        }

        return self::checkisGrant($permissions,$module,$action);
    }

    public static function isGranted($module, $action = "")
    {

        if (self::$permissions == NULL) {

            $context = &get_instance();
            $grp_id = $context->mUserBrowser->getData("grp_access_id");

            $context->db->where('id', $grp_id);
            $grp = $context->db->get("group_access", 1);
            $grp = $grp->result_array();

            if (count($grp) > 0) {
                $grp = $grp[0];
                self::$permissions = json_decode($grp['permissions'], JSON_OBJECT_AS_ARRAY);
            }

        }


        return self::checkIsGrant(self::$permissions,$module,$action);

    }

    private static function checkIsGrant($permissions,$module, $action = ""){


        if ($action == "") {

            if (isset($permissions[$module])) {
                foreach ($permissions[$module] as $key1 => $value1) {
                    if ($value1 == 1)
                        return TRUE; //module is granted
                }
            }

        } else {

            if (isset($permissions[$module][$action]) AND
                $permissions[$module][$action] == 1) {
                return TRUE; //module & action is granted
            }

        }


        return FALSE;
    }

    public static function createTableModuleActions(){

        $context = &get_instance();

        if ($context->db->table_exists('module_actions') )
            return;

        $context->load->dbforge();
        $context->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'module' => array(
                'type' => 'VARCHAR(100)',
                'default' => NULL
            ),
            'actions' => array(
                'type' => 'TEXT',
                'default' => NULL
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'default' => NULL
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'default' => NULL
            ),
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $context->dbforge->add_key('id', TRUE);
        $context->dbforge->create_table('module_actions', TRUE, $attributes);


    }

    public static function createTableGroupAccess(){

        $context = &get_instance();

        if ($context->db->table_exists('group_access') )
            return;

        $context->load->dbforge();
        $context->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR(50)',
                'default' => NULL
            ),
            'permissions' => array(
                'type' => 'TEXT',
                'default' => NULL
            ),
            'editable' => array(
                'type' => 'INT',
                'default' => 1
            ),
            'updated_at' => array(
                'type' => 'DATETIME'
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            )
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $context->dbforge->add_key('id', TRUE);
        $context->dbforge->create_table('group_access', TRUE, $attributes);


    }

}