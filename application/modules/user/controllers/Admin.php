<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */
class Admin extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load model

        $this->load->model("user/user_model", "mUserModel");
        $this->load->model("user/user_browser", "mUserBrowser");
    }

    public function index()
    {


    }


    public function group_access()
    {

        if (!GroupAccess::isGranted('user', MANAGE_GROUP_ACCESS))
            redirect("error?page=permission");

        $data['actions'] = GroupAccess::getModuleActions();
        $data['actions'] = GroupAccess::validateActions($data['actions']);

        $data['group_accesses'] = $this->mGroupAccessModel->getGroupAccesses();
        $data['group_accesses'] = GroupAccess::validateGrpAcc($data['group_accesses']);



        $this->load->view("backend/header", $data);
        $this->load->view('backend/html/grp_access/add_group_access');
        $this->load->view("backend/footer");

    }

    public function edit_group_access()
    {

        if (!GroupAccess::isGranted('user', MANAGE_GROUP_ACCESS))
            redirect("error?page=permission");


        $id = $this->input->get("id");
        $id = intval($id);


        $grp = $this->mGroupAccessModel->getGroupAccess($id);
        if ($grp != NULL)
            if ($grp['editable'] == 0){
                if(ENVIRONMENT!="development"){
                    redirect(admin_url('error404'));
                }
            }

        $data['actions'] = GroupAccess::getModuleActions();
        $data['actions'] = GroupAccess::validateActions($data['actions']);

        $data['group_access'] = $this->mGroupAccessModel->getGroupAccess($id);
        $data['group_access']['permissions'] =  GroupAccess::validateActions(
            json_decode($data['group_access']['permissions'],JSON_OBJECT_AS_ARRAY)
        );

        $data['group_access']['permissions'] = json_encode($data['group_access']['permissions']);


        if ($data['group_access'] != NULL) {

            $this->load->view("backend/header", $data);
            $this->load->view('backend/html/grp_access/edit_group_access');
            $this->load->view("backend/footer");

        } else
            redirect(admin_url('error404'));

    }


    public function delete_group_access()
    {

        if (!GroupAccess::isGranted('user', MANAGE_GROUP_ACCESS))
            redirect("error?page=permission");

        $id = $this->input->get("id");
        $id = intval($id);

        $grp = $this->mGroupAccessModel->getGroupAccess($id);

        if ($grp != NULL) {

            if ($grp['editable'] == 1) {
                $this->mGroupAccessModel->deleteGrp($grp['id']);
            }

        }

        redirect(admin_url('user/group_access'));

    }

    public function shadowing()
    {

        if (!GroupAccess::isGranted('user'))
            redirect("error?page=permission");

        $id = $this->input->get("id");

        if (defined("DEMO") and DEMO == TRUE) {
            $id = 3;
        }


        $re = $this->mUserBrowser->shadowing_mode($id);
        if ($re)
            redirect(admin_url());

    }

    public function close_shadowing()
    {

        if ($this->mUserBrowser->isLogged() && $this->mUserBrowser->isShadowing()) {

            $re = $this->mUserBrowser->close_shadowing_mode();
            if ($re)
                redirect(admin_url("user/users"));
        } else {
            redirect(admin_url("error404"));
        }

    }

    public function users()
    {

        if(!GroupAccess::isGranted('user',USER_ADMIN))
            redirect("error?page=permission");

        $params = array(
            "page" => $this->input->get("page"),
            "id" => $this->input->get("id"),
            "search" => $this->input->get("search"),
            'limit' => NO_OF_ITEMS_PER_PAGE,
            "is_super" => TRUE,
            "user_id" => $this->mUserBrowser->getData("id_user")
        );

        $data['data'] = $this->mUserModel->getUsers($params);


        $this->load->view("backend/header", $data);
        $this->load->view("backend/html/users");
        $this->load->view("backend/footer");


    }


    public function edit()
    {

        if (!GroupAccess::isGranted('user', EDIT_USER))
            redirect("error?page=permission");

        $id = intval($this->input->get("id"));
        $data['user'] = $this->mUserModel->userDetail($id);
        $data['grp_accesses'] = $this->mGroupAccessModel->getGroupAccesses();
        $data['user_settings'] = UserSettingSubscribe::load();
        $data['config'] = $this->mConfigModel->getParams();

        if(isset( $data['user'][Tags::RESULT][0])){
            $data['user'] = $data['user'][Tags::RESULT][0];
            $this->load->view("backend/header", $data);
            $this->load->view("backend/html/edit");
            $this->load->view("backend/footer");
        }else{
            redirect(admin_url('error404?code=u09'));
        }

    }


    public function profile()
    {

        $id = intval($this->mUserBrowser->getData("id_user"));
        $data['user'] = $this->mUserModel->userDetail($id);
        $data['grp_accesses'] = $this->mGroupAccessModel->getGroupAccesses();
        $data['user_settings'] = UserSettingSubscribe::load();
        $data['config'] = $this->mConfigModel->getParams();

        if(isset( $data['user'][Tags::RESULT][0])){
            $data['user'] = $data['user'][Tags::RESULT][0];
            $this->load->view("backend/header", $data);
            $this->load->view("backend/html/profile");
            $this->load->view("backend/footer");
        }else{
            redirect(admin_url('error404?code=u09'));
        }


    }

    public function add()
    {

        if (!GroupAccess::isGranted('user', ADD_USERS))
            redirect("error?page=permission");

        $data['grp_accesses'] = $this->mGroupAccessModel->getGroupAccesses();
        $data['user_subscribe_fields'] = UserSettingSubscribe::load();
        $data['config'] = $this->mConfigModel->getParams();

        $this->load->view("backend/header", $data);
        $this->load->view("backend/html/add");
        $this->load->view("backend/footer");

    }


    public function userSetting()
    {

        if (!GroupAccess::isGranted('user', USER_SETTING))
            redirect("error?page=permission");

        $data['user_subscribe_fields'] = UserSettingSubscribe::load();
        $data['config'] = $this->mConfigModel->getParams();

        $data['grp_accesses'] = $this->mGroupAccessModel->getGroupAccesses();


        TemplateManager::set_settingActive('user');

        $this->load->view("backend/header", $data);
        $this->load->view("backend/html/user_setting");
        $this->load->view("backend/footer");


    }

    public function resendMail(){

        if(GroupAccess::isGranted('user',USER_ADMIN)){

            $user_id = $this->input->get('id');
            $user_id = intval($user_id);

            $this->mUserModel->resendMailConfirmation($user_id);

            $callback_url = $this->session->userdata('user_edit_callback');
            redirect($callback_url);

        }

    }


    public function login(){
        if(!$this->mUserBrowser->isLogged())
            redirect(site_url('user/login'));
        else
            redirect(site_url(''));

    }

    public function signup(){
        if(!$this->mUserBrowser->isLogged())
            redirect(site_url('user/signup'));
        else
            redirect(site_url(''));

    }


}

/* End of file UserDB.php */