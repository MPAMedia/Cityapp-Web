<?php

class Group_access_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

    }

    public function setupDefaultGroupAccess(){

        $permission_init = @file_get_contents('permissions_init.json');
        if($permission_init!="")
            $permission_init = json_decode($permission_init,JSON_OBJECT_AS_ARRAY);

        if(!is_array($permission_init))
            return;

        $adminPermissions = json_encode($permission_init['Admin'],JSON_FORCE_OBJECT);
        $MobileUserPermissions =  json_encode($permission_init['MobileUser'],JSON_FORCE_OBJECT);
        $ownerPermissions =  json_encode($permission_init['Owner'],JSON_FORCE_OBJECT);

        $count = $this->db->count_all_results('group_access');

        if($count==0){

            $data = array(
              "name" => "SuperAdmin",
              "permissions" => $adminPermissions,
              "editable" => 0,
            );

            $this->db->insert("group_access",$data); //1 ID

            $data['permissions'] = $MobileUserPermissions;
            $data['editable'] = 0;
            $data['name'] = "MobileUser";

            $this->db->insert("group_access",$data); //2 ID

            $data['permissions'] = $ownerPermissions;
            $data['editable'] = 1;
            $data['name'] = "BusinessOwner";


            $this->db->insert("group_access",$data); //3 ID



            $count = $this->db->count_all_results('user');

            if($count>1){

                $this->db->where('typeAuth','admin');
                $this->db->update('user',array(
                    'typeAuth'  => "SuperAdmin",
                    'grp_access_id'  => 1,
                ));

                $this->db->where('typeAuth','customer');
                $this->db->update('user',array(
                    'typeAuth'  => "MobileUser",
                    'grp_access_id'  => 2,
                ));


                $this->db->where('typeAuth','manager');
                $this->db->update('user',array(
                    'typeAuth'  => "BusinessOwner",
                    'grp_access_id'  => 3,
                ));

            }else{

                $this->db->update('user',array(
                    'typeAuth'  => "SuperAdmin",
                    'grp_access_id'  => 1,
                ));

            }


            $this->mConfigModel->save('DEFAULT_USER_MOBILE_GRPAC',2);
            $this->mConfigModel->save('DEFAULT_USER_GRPAC',3);

        }

    }


    public function add_group_access($params=array()){

        $errors = array();
        $data = array();

        extract($params);

        if(isset($name) and $name!=""){
            if(Text::checkUsernameValidate($name)){
                $data['name'] = $name;
            }else
                $errors[] = Translate::sprint('Name is not valid!');
        }else{
            $errors[] = Translate::sprint('Name field is empty!');
        }

        if(isset($grp_access) and is_array($grp_access)){

        }else{
            $errors[] = Translate::sprint('Error with actions');
        }

        //check actions

        $permissions = array();

        if(empty($errors)){
            $actions = GroupAccess::getModuleActions();
            foreach ($actions as $key => $action){
                $permissions[$key] = array();
                foreach ($action as $value){
                    $permissions[$key][$value] = 0;
                    if(isset($grp_access[$key][$value]))
                        $permissions[$key][$value] = intval($grp_access[$key][$value]);
                }

            }


            if(!empty($permissions)){

                $data['permissions']  = json_encode($permissions,JSON_FORCE_OBJECT);
                $this->db->insert('group_access',$data);
                return array(Tags::SUCCESS=>1);

            }

        }




        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
    }

    public function edit_group_access($params=array()){

        $errors = array();
        $data = array();

        extract($params);

        if(isset($id_grp) and $id_grp>0){
            $data['id'] = $id_grp;
        }else
            $errors[] = Translate::sprint('The ID is not valid!');

        if(isset($name) and $name!=""){
            if(Text::checkUsernameValidate($name)){
                $data['name'] = $name;
            }else
                $errors[] = Translate::sprint('Name is not valid!');
        }else{
            $errors[] = Translate::sprint('Name field is empty!');
        }

        if(isset($grp_access) and is_array($grp_access)){

        }else{
            $errors[] = Translate::sprint('Error with actions');
        }

        //check actions

        $permissions = array();

        if(empty($errors)){
            $actions = GroupAccess::getModuleActions();
            foreach ($actions as $key => $action){
                $permissions[$key] = array();
                foreach ($action as $value){
                    $permissions[$key][$value] = 0;
                    if(isset($grp_access[$key][$value]))
                        $permissions[$key][$value] = intval($grp_access[$key][$value]);
                }

            }


            if(!empty($permissions)){
                $data['permissions']  = json_encode($permissions,JSON_FORCE_OBJECT);
                $this->db->where('id',$data['id']);
                $this->db->update('group_access',$data);
                return array(Tags::SUCCESS=>1);
            }
        }



        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
    }

    public function getGroupAccesses(){

        $group_accesses = $this->db->get('group_access');
        $group_accesses = $group_accesses->result_array();

        return $group_accesses;
    }

    public function getGroupAccess($id){

        $this->db->where('id',$id);
        $group_access = $this->db->get('group_access',1);
        $group_access = $group_access->result_array();

        if(isset($group_access[0]))
            return $group_access[0];

        return NULL;
    }

    public function getEnabledModules($id){

        $this->db->where('id',$id);
        $group_access = $this->db->get('group_access',1);
        $group_access = $group_access->result_array();

        $modules = array();

        if(isset($group_access[0])){

            $permission = $group_access[0]['permissions'];
            $permission = json_decode($permission,JSON_OBJECT_AS_ARRAY);

            foreach ($permission as $module_name => $object){
                foreach ($object as $value){
                    if($value==1)
                        $modules[$module_name] = 1;
                }
            }
        }

        return $modules;
    }

    public function deleteGrp($id){

        $this->db->where('grp_access_id',$id);
        $count = $this->db->count_all_results('user');

        if($count==0){
            $this->db->where('id',$id);
            $this->db->delete('group_access');
        }

    }


    public function createTableGroupAccess()
    {

        GroupAccess::createTableGroupAccess();

    }


    public function createTableModuleActions()
    {

        GroupAccess::createTableModuleActions();

    }

    public function updateFields(){

        if (!$this->db->field_exists('grp_access_id', 'user'))
        {
            $fields = array(
                'grp_access_id'       => array('type' => 'INT', 'after' => 'guest_id','default' => 0),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('user', $fields);
        }

    }



}