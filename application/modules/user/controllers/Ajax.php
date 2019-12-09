<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mUserModel
 *
 * @author idriss
 */
class Ajax extends AJAX_Controller
{


    public function __construct()
    {
        parent::__construct();

        $this->load->model("user/user_model", "mUserModel");
        $this->load->model("user/user_browser", "mUserBrowser");

        $this->load->model("messenger/messenger_model", "mMessengerModel");

        $this->load->model("store/store_model", "mStoreModel");
        $this->load->model("offer/offer_model", "mOfferModel");
        $this->load->model("event/event_model", "mEventModel");

    }


    public function refreshPackage($uid = 0)
    {

        $this->load->model("User/mUserModel");
        $this->mUserModel->refreshPackage($uid);

    }

    public function signUp()
    {


        $params = array(
            'name' => $this->input->post("name"),
            'username' => $this->input->post("username"),
            'password' => $this->input->post("password"),
            'email' => $this->input->post("email"),
            "typeAuth" => "manager"
        );




        $data = $this->mUserModel->signUp($params, array(
            "name", "username", "password", "email", "typeAuth"
        ));

        if ($data[Tags::SUCCESS] == 1) {

            $this->mUserBrowser->cleanToken("S0XsOi");
            $this->mUserBrowser->setID($data[Tags::RESULT][0]['id_user']);
            $this->mUserBrowser->setUserData($data[Tags::RESULT][0]);

            $this->session->set_userdata(array(
                "savesession"=>array()
            ));

            //send message welcome
            if (MESSAGE_WELCOME != "") {

                $this->load->model("messenger/messenger_model");

                $this->db->select("id_user");
                $this->db->order_by("id_user", "ASC");
                $user = $this->db->get("user", 1);
                $user = $user->result();

                $result = $this->messenger_model->sendMessage(array(
                    "sender_id" => $user[0]->id_user,
                    "receiver_id" => $data[Tags::RESULT][0]['id_user'],
                    "discussion_id" => 0,
                    "content" => Text::input(MESSAGE_WELCOME)
                ));

            }

        }

        if(ModulesChecker::isRegistred("pack") && $data[Tags::SUCCESS]==1 && isset($data[Tags::RESULT][0])){

            $this->load->model("pack/pack_model");
            $this->pack_model->setUserAsCustomer($data[Tags::RESULT][0]['id_user']);
            $data['url'] = site_url("pack/pickpack");

        }


        echo json_encode($data);
        return;
    }

    public function resetpassword()
    {

        $token = $this->input->post("stoken");
        $password = $this->input->post("password");
        $confirm = $this->input->post("confirm");


        $this->load->model("User/mUserModel");

        $data = $this->mUserModel->resetPassword(array(
            "token" => $token,
            "password" => $password,
            "confirm" => $confirm
        ));

        echo json_encode($data);

    }

    public function forgetpassword()
    {

        $login = $this->input->post("login");
        $token = $this->input->post("token");

        $this->load->model("User/mUserModel");

        $data = $this->mUserModel->sendNewPassword(array(
            "login" => $login
        ));

        echo json_encode($data);
    }


    public function signIn()
    {

        //$this->load->model("User/mUserModel");
        $errors = array();

        $login = Security::decrypt($this->input->post("login"));
        $password = Security::decrypt($this->input->post("password"));
        $token = Security::decrypt($this->input->post("token"));

        $params = array(
            "login" => $login,
            "password" => $password
        );

        $data = $this->mUserModel->signIn($params);

        if(isset($data[Tags::SUCCESS]) && $data[Tags::SUCCESS]==1){
            if (isset($data[Tags::RESULT][0])){
                $user = $data[Tags::RESULT][0];
                if($user['typeAuth']=="customer"){

                    $err = Messages::USER_ACCOUNT_ISNT_BUSINESS;

                    if(ModulesChecker::isRegistred("pack")){
                        $err = Messages::USER_ACCOUNT_ISNT_BUSINESS_2.", "."<a href='".admin_url("pack/pickpack")."'>".Translate::sprint("Upgrade your account")."</a>";
                    }

                    echo json_encode(
                        array(
                            Tags::SUCCESS => 0,
                            Tags::ERRORS => array(
                                "err"   => $err
                            )
                        )
                    );
                    return;
                }
            }
        }

        if (isset($data[Tags::RESULT][0])){


            $this->mUserBrowser->setUserData($data[Tags::RESULT][0]);
            $this->session->set_userdata(array(
                "savesession"=>array()
            ));
        }


        echo json_encode(
            $data
        );
        return;

    }



    public function edit()
    {

        //check if user have permission
        $this->checkDemoMode();

        $errors = array();

        $id_user = intval($this->input->post("id"));
        $password = $this->input->post("password");
        $confirm = $this->input->post("confirm");
        $name = $this->input->post("name");
        $username = $this->input->post("username");
        $typeAuth = $this->input->post("typeAuth");

        $token = $this->input->post("token");
        $tokenSession = $this->mUserBrowser->getToken("S0XsNOi");
        if ($token != $tokenSession) {
            return array(Tags::SUCCESS => 0);
        }


        $nbr_stores = $this->input->post("nbr_stores");
        $nbr_events_monthly = $this->input->post("nbr_events_monthly");
        $nbr_campaign_monthly = $this->input->post("nbr_campaign_monthly");
        $nbr_offer_monthly = $this->input->post("nbr_offer_monthly");
        $push_campaign_auto = intval($this->input->post("push_campaign_auto"));
        $push_campaign_auto = 1;

        $image = $this->input->post("image");

        $params = array(
            "id_user"               =>$id_user,
            "password"              =>$password,
            "confirm"               =>$confirm,
            "name"                  =>$name,
            "username"              =>$username,
            "typeAuth"              =>$typeAuth,
            "nbr_stores"            =>$nbr_stores,
            "nbr_events_monthly"    =>$nbr_events_monthly,
            "nbr_campaign_monthly"  =>$nbr_campaign_monthly,
            "nbr_offer_monthly"     =>$nbr_offer_monthly,
            "push_campaign_auto"    =>$push_campaign_auto,
            "image"                 =>$image,
        );


        $sessionTypeAuth = $this->mUserBrowser->getData("typeAuth");
        $sessionUser_id = $this->mUserBrowser->getData("id_user");

        $params["sessionTypeAuth"] = $sessionTypeAuth;
        $params["sessionUser_id"] = $sessionUser_id;

        $data = $this->mUserModel->edit($params);
        echo json_encode($data);return;

    }

    public function getOwners()
    {


        $typeAuth = $this->mUserBrowser->getData("typeAuth");
        $user_id = $this->mUserBrowser->getData("id_user");

       $json = $this->mUserModel->getOwners(array(
           "typeAuth"   => $typeAuth,
           "user_id"   => $user_id,
       ));

        echo json_encode($json);
    }


    public function checkAdminData($id = 0)
    {

        $this->db->select("user.*,setting.*");
        $this->db->where("user.id_user", $id);
        $this->db->join("setting", "setting.user_id=user.id_user", "INNER");
        $this->db->from("user");

        $admin = $this->db->get();
        $admin = $admin->result_array();

        if (count($admin) > 0)
            return $admin[0];
        else
            return null;

    }

    public function create()
    {
        //check if user have permission
        $this->checkDemoMode();

        $name = $this->input->post("name");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $confirm = $this->input->post("confirm");
        $mail = $this->input->post("mail");
        $typeAuth = $this->input->post("typeAuth");
        $tel = $this->input->post("tel");
        $image = $this->input->post("image");


        $nbr_stores = $this->input->post("nbr_stores");
        $nbr_events_monthly = $this->input->post("nbr_events_monthly");
        $nbr_campaign_monthly = $this->input->post("nbr_campaign_monthly");
        $nbr_offer_monthly = $this->input->post("nbr_offer_monthly");
        $push_campaign_auto = intval($this->input->post("push_campaign_auto"));

        if ($typeAuth == "")
            $typeAuth = "manager";

        if ($push_campaign_auto == "on")
            $push_campaign_auto = 1;
        else
            $push_campaign_auto = 0;


        if ($this->mUserBrowser->isLogged()) {
            // $data["manager"]= $this->mUserBrowser->getAdmin("id_user");
        } else {
            $errors['login'] = Translate::sprint(Messages::USER_MISS_AUTHENTIFICATION);
            return json_encode(array(Tags::SUCCESS => 0, "errors" => $errors));
        }

        $params = array(
            "image"                => $image,

            "name"                  => $name,
            "username"              => $username,
            "password"              => $password,
            "confirm"               => $confirm,
            "mail"                  => $mail,
            "tel"                   => $tel,
            "typeAuth"              => $typeAuth,

            "nbr_stores"            => $nbr_stores,
            "nbr_events_monthly"    => $nbr_events_monthly,
            "nbr_campaign_monthly"  => $nbr_campaign_monthly,
            "nbr_offer_monthly"     => $nbr_offer_monthly,
            "push_campaign_auto"    => $push_campaign_auto,
        );

        $data = $this->mUserModel->create($params);

        echo json_encode($data);return;

    }

    public function getUser($params = array())
    {

        $this->load->model("User/mUserModel");
        return $this->mUserModel->getUsers($params);

    }

    public function detailUser()
    {

        $id = intval($this->input->get("id"));

        if (isset($id) AND $id > 0) {
            $this->db->where("id_user", $id);
        }
        $myUsers = $this->db->get("user");
        $myUsers = $myUsers->result();
        return array("success" => 1, "user" => $myUsers);

    }

    public function delete()
    {
        //check if user have permission
        $this->checkDemoMode();

        $id_user = intval($this->input->post("id"));
        $switch_to = intval($this->input->post("switch_to"));

        if ($this->mUserBrowser->isLogged() && $this->mUserBrowser->getAdmin("typeAuth") == "admin") {

            if (isset($id_user) AND $id_user > 0) {
                $data["id_user"] = $id_user;

                // $this->db->where("manager",$data["manager"]);
                $this->db->where("id_user", $data["id_user"]);
                $c = $this->db->count_all_results("user");
                if ($c > 0) {


                    $this->mMessengerModel->deleteAllForUser($data["id_user"]);

                    //assign all to another owner
                    if ($switch_to > 0) {

                        $this->mEventModel->switchTo($data["id_user"], $switch_to);
                        $this->mOfferModel->switchTo($data["id_user"], $switch_to);
                        $this->mStoreModel->switchTo($data["id_user"], $switch_to);

                    }


                    $this->db->where("user_id", $data["id_user"]);
                    $this->db->delete("store");

                    $this->db->where("id_user", $data["id_user"]);
                    $this->db->delete("user");

                    $this->db->where("user_id", $data["id_user"]);
                    $this->db->delete("setting");


                    echo json_encode(array("success" => 1, "url" => admin_url("getUsers")));return;
                } else {
                    $errors["access"] = Translate::sprint(Messages::USER_AUTORIZATION_ACCESS);
                }


            } else {
                $errors["User"] = Translate::sprint(Messages::USER_NOT_SELECTED);
            }

            echo json_encode(array("success" => 0, "errors" => $errors));return;
        }
    }

    public function confirm()
    {
        $id = intval($this->input->get("id"));
        $typeAuth = $this->mUserBrowser->getData("typeAuth");

        if ($typeAuth == "admin" && $id > 0) {

            $this->db->where("id_user", $id);
            $this->db->update('user', array(
                "confirmed" => 1
            ));

        }

        echo json_encode(array(Tags::SUCCESS => 1));
        return;
    }

    public function access()
    {
        $id = intval($this->input->get("id"));

        echo json_encode(
            $this->mUserModel->access($id)
        );

    }


}
