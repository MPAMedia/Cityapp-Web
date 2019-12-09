<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("appcore/bundle", "mBundle");

        $this->updateUserSession();
        $this->addFields();

    }

    public function updateUserSession(){

        $uri = $this->uri->segment(1);
        if($uri=="dashboard"){
            $this->load->model('user/user_browser','mUserBrowser');
            if($this->mUserBrowser->isLogged()){
                $user_id = intval($this->mUserBrowser->getData("id_user"));
                $admin = $this->getUserData($user_id);
                if(!empty($admin)){
                    $this->mUserBrowser->setUserData($admin);
                }
            }
        }
    }


    public function getGuestData($id)
    {

        if(intval($id)==0)
            return array();

        $this->db->select("guest.*");
        $this->db->from("guest");
        $this->db->where("id",$id);

        $guest_data = $this->db->get();
        $guest_data = $guest_data->result_array();

        if(isset($guest_data[0]))
            return $guest_data[0];
        else
            return array();

    }

    public function getUserData($id)
    {

        $this->db->select("user.*,setting.*");
        $this->db->from("user");
        $this->db->join("setting", "setting.user_id=user.id_user", "INNER");
        $this->db->where("id_user",$id);
        $this->db->select("user.*");

        $user_data = $this->db->get();
        $user_data = $user_data->result_array();

        if(isset($user_data[0]))
             return $user_data[0];
        else
            return array();

    }



    public function getUserByGuestId($guest_id)
    {

        $this->db->select("id_user");
        $this->db->where("guest_id", $guest_id);
        $u = $this->db->get("user", 1);
        $u = $u->result_array();

        if (count($u) > 0) {

            return $this->syncUser(array(
                "user_id" => $u[0]['id_user']
            ));

        }

        return NULL;
    }

    public function getFCM($user_id)
    {

        $this->db->select("guest_id");
        $this->db->where("id_user", $user_id);
        $u = $this->db->get("user", 1);
        $u = $u->result_array();

        if (count($u) > 0) {

            $this->db->select("fcm_id,platform");
            $this->db->where("id", $u[0]['guest_id']);
            $fcm = $this->db->get("guest");
            $fcm = $fcm->result_array();

            if (count($fcm) > 0) {
                return array(
                    "fcm"       => $fcm[0]["fcm_id"],
                    "platform"  => $fcm[0]["platform"],
                );
            }
        }

        return array();
    }

    public function updatePhotosProfile($params = array())
    {

        $data = array();
        $errors = array();

        extract($params);

        if (isset($image) && $image != "") {
            $data['images'] = json_encode(array($image), JSON_FORCE_OBJECT);
        }

        if (isset($user_id) and $user_id > 0 and isset($data['images'])) {

            $this->db->where("id_user", $user_id);
            $this->db->update("user", $data);

            $this->db->where("user.id_user", $user_id);
            $this->db->from('user');
            $userData = $this->db->get();

            $userData = $userData->result_array();

            $this->load->model("bundle/bundle");
            $userData = $this->bundle->prepareData($userData);

            return (array(Tags::SUCCESS => 1, Tags::RESULT => $userData));
        }

        return (array(Tags::SUCCESS => 0));

    }


    public function getUserNameById($uid)
    {
        $this->db->select("username");
        $this->db->where("id_user", $uid);
        $user = $this->db->get("user", 1);
        $user = $user->result();
        if (count($user) > 0)
            return $user[0]->username;

        return;
    }

    public function getUserIDByUsername($username)
    {

        $this->db->select("id_user");
        $this->db->where("username", $username);
        $user = $this->db->get("user", 1);
        $user = $user->result();
        if (count($user) > 0)
            return $user[0]->id_user;

        return 0;
    }


    public function getCurrentLimits($uid)
    {

        $this->db->where("user_id", $uid);
        $user = $this->db->get("setting", 1);
        $user = $user->result();


    }


    public function refreshPackage($uid)
    {

        if(ModulesChecker::isRegistred("pack")){
            $this->load->model("pack/pack_model");
            $this->mPack->refreshPackage($uid);
        }else{
            $this->db->select("last_updated,package");
            $this->db->where("user_id", $uid);
            $user = $this->db->get("setting", 1);
            $user = $user->result();

            if (count($user) > 0) {
                $date = $user[0]->last_updated;
                $date = date("Y-m", strtotime($date));
                $currentdate = date('Y-m', time());

                if ($currentdate != $date) {
                    $pack = $user[0]->package;
                    $pack = json_decode($pack, JSON_OBJECT_AS_ARRAY);
                    $pack['last_updated'] = date("Y-m-d", time());

                    $this->db->where("user_id", $uid);
                    $this->db->update("setting", $pack);
                }
            }
        }

    }

    public function mailVerification($token)
    {

        if (Text::tokenIsValid($token)) {

            $this->db->where("id", $token);
            $this->db->where("type", "confirm");
            $v = $this->db->get("token", 1);
            $v = $v->result();

            if (count($v) > 0) {

                $this->db->where("id_user", $v[0]->uid);
                $this->db->update("user", array(
                    "confirmed" => 1
                ));

                $this->db->where("id", $token);
                $this->db->delete("token");


                return $v[0]->uid;

            }

        }


        return FALSE;
    }

    public function resetPassword($params = array())
    {

        extract($params);
        $errors = array();
        $data = array();

        if (isset($token) and isset($password) and isset($confirm)) {

            if (Text::tokenIsValid($token)) {

                if (strlen($password) >= 6) {

                    if (Text::compareToStrings($password, $confirm)) {

                        $this->db->where("id", $token);
                        $this->db->where("type", "newpassword");
                        $v = $this->db->get("token", 1);
                        $v = $v->result();

                        if (count($v) > 0) {

                            $this->db->where("id_user", $v[0]->uid);
                            $this->db->update("user", array(
                                "password" => Security::cryptPassword($password)
                            ));


                            $this->db->where("id", $token);
                            $this->db->delete("token");

                            return array(Tags::SUCCESS => 1);

                        } else {

                            $errors['token'] = Translate::sprint(Messages::TOKEN_NOT_VALID);
                        }

                    } else {
                        $errors['password'] = Translate::sprint(Messages::USER_PASSWORD_INVALID);
                    }
                } else {
                    $errors['password'] = Translate::sprint(Messages::PASSWORD_FORMAT);
                }

            } else {
                $errors['token'] = Translate::sprint(Messages::TOKEN_NOT_VALID);
            }


        } else {
            $errors['reset'] = Translate::sprint(Messages::RESET_ERROR);
        }


        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }

    public function sendNewPassword($params = array())
    {

        //params login password mac_address
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        if (isset($login) AND $login != "") {

            if (Text::checkEmailFields($login)) {

                $data["email"] = $login;

            } else if (Text::checkUsernameValidate($login)) {

                $data["username"] = Text::input($login);

            } else {

                $errors['login'] = Translate::sprint(Messages::USER_LOGIN_INVALID);
            }

        } else {
            $errors['login'] = Translate::sprint(Messages::USER_NAME_EMPTY) . "  " . json_encode($params);
        }


        if (empty($errors)) {

            //$this->db->where("auth_type","email");
            $this->db->where($data);
            $user = $this->db->get("user");
            $user = $user->result();

            if (count($user) > 0) {

                //send password to user email
                $letter = array_merge(range('A', 'Z'), range('a', 'z'));

                //generate new random password Q23qo5
                $token = md5(time() . rand(0, 999));

                $this->db->insert('token', array(
                    "id" => $token,
                    "uid" => $user[0]->id_user,
                    "type" => "newpassword",
                    "created_at" => date("Y-m-d", time())
                ));


                $appLogo = _openDir(APP_LOGO);
                $imageUrl = "";
                if (!empty($appLogo)) {
                    $imageUrl = $appLogo['200_200']['url'];
                }


                $messageText = Text::textParser(array(
                    "name" => $user[0]->name,
                    "url" => admin_url("user/rpassword?recover=$token"),
                    "imageUrl" => $imageUrl,
                    "email" => $user[0]->email,
                    "appName" => APP_NAME,
                ), "passwordforgot");

                $messageText = ($messageText);

                // $mail = new Mailer();
                // $mail->setDistination($user[0]->email);
                // $mail->setFrom(DEFAULT_EMAIL);
                // $mail->setFrom_name(APP_NAME);
                // $mail->setMessage($messageText);
                // $mail->setReplay_to(DEFAULT_EMAIL);
                // $mail->setReplay_to_name(APP_NAME);
                // $mail->setType("html");
                // $mail->setSubjet("New Password");

                

//                $this->db->where("auth_type","email");
//                $this->db->where("email",$user[0]->email);
//                $this->db->set("password",$user[0]->fpassword+1);
//                $this->db->update("user");

                $this->load->library('email');
                $this->email->from(DEFAULT_EMAIL, APP_NAME);
                $this->email->to($user[0]->email);
                 $this->email->set_mailtype("html");
                $this->email->subject('New Password ');
                $this->email->message($messageText);
                if($this->email->send())
                    return array(Tags::SUCCESS => 1);
                else
                    return array(Tags::SUCCESS => 0);

            } else {
                $errors['login'] = Translate::sprint(Messages::LOGIN_NOT_EXIST_OR_LIMIT_EXCEEDED);
            }


        }


        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }


    public function refreshPosition($params = array())
    {

        $data = array();
        $errors = array();
        extract($params);

        if (isset($guest_id) AND $guest_id > 0) {

            if (isset($lng) AND isset($lat) AND $lat != 0 AND $lng != 0) {
                $data["lat"] = $lat;
                $data["lng"] = $lng;
            }

            $this->db->where("id", $guest_id);
            $this->db->update("guest", $data);


            //markVisit

        }

        return array(Tags::SUCCESS => 1);

    }

    public function createNewGuest($params = array())
    {

        extract($params);
        $errors = array();
        $data = array();


        if (isset($fcm_id) AND $fcm_id != "") {
            $data['fcm_id'] = $fcm_id;
        }

        if (isset($sender_id) AND $sender_id != "") {
            $data['sender_id'] = $sender_id;

        } else {
            $errors['error'] = Translate::sprint(Messages::ERROR_SENDER_ID);
        }


        if (isset($lat) AND $lat != "") {
            $data['lat'] = $lat;
        }

        if (isset($lng) AND $lng != "") {
            $data['lng'] = $lng;
        }


        if (isset($platform) AND $platform != "") {
            $data['platform'] = $platform;
        }


        if (empty($errors)) {


            $this->db->where("sender_id", $sender_id);
            $guest = $this->db->get("guest", 1);
            $guest = $guest->result();

            $data['last_activity'] = date("Y-m-d", time());


            if (count($guest) == 0) {

                $this->db->insert("guest", $data);
                $id = $this->db->insert_id();

            } else {

                $this->db->where("id", $guest[0]->id);
                $this->db->update("guest", $data);
                $id = $guest[0]->id;

            }

            $this->db->where("id", $id);
            $guest = $this->db->get("guest", 1);
            $guest = $guest->result_array();

            return array(Tags::SUCCESS => 1, Tags::RESULT => $guest);
        }

        return json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => $errors));
    }


    public function generateToken($params = array())
    {
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        if (
        (isset($mac_adr) and Security::checkMacAddress($mac_adr))
            /* AND
             (isset($ip_adr) and Security::checkIpAddress($ip_adr)) */
        ) {


            $mac_adr = trim($mac_adr);
            //GENERATE NEW TOKEN TO USING APPLICATION
            $token = md5($mac_adr . "_" . APP_KEY . "_" . time());

            /* $this->db->where("_device_id",trim($mac_adr));
             $this->db->delete("token");*/

            $this->db->insert("token", array(
                "_id" => $token,
                "_device_id" => $mac_adr,
                "_ip_adr" => $ip_adr
            ));


            $data = $this->db->get("option");
            $data = $data->result_array();


            return array("success" => 1, "token" => $token, "data" => $data);

        }

        /*if(isset($token) and $token!="" and preg_match("#^[a-z0-9]+$#i", $token)){

        }*/

        return array("success" => 0, "error" => "");
    }


    public function checkUser($params = array())
    {

        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);


        if (isset($user_id) and $user_id > 0) {
            $this->db->where("id_user", $user_id);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                return array(Tags::SUCCESS => 1);
            }
        }

        return array(Tags::SUCCESS => 0);
    }


    public function signIn($params = array())
    {

        //params login password mac_address
        $errors = array();
        $data = array();

        //extract — Importe les variables dans la table des symboles
        extract($params);

        if (isset($login) AND $login != "") {

            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $data["email"] = strtolower($login);
            } else if (preg_match("#^[a-zA-Z0-9\-_." . REGEX_FR . "]+$#i", $login)) {
                $data["username"] = strtolower(Text::input($login));
            } else {
                $errors['login'] = Translate::sprint(Messages::USER_LOGIN_INVALID);
            }

        } else {
            $errors['login'] = Translate::sprint(Messages::USER_LOGIN_EMPTY);
        }


        if (isset($password) AND $password != "") {
            $data["password"] = Security::cryptPassword($password);
        } else {
            $errors['password'] = Translate::sprint(Messages::USER_PASSWORD_EMPTY);
        }


        if (empty($errors)) {
            $this->db->where($data);
            $user = $this->db->get("user");


            if ($user->num_rows() == 1) {

                $user_data = $user->result_array();
                //$this->generateToken($user_data);

                if (isset($lat) AND isset($lng))
                    $this->changeLocation($lat, $lng, $user_data[0]['id_user']);

                //update guest ID with FCM
                if (isset($guest_id) and $guest_id > 0) {

                    $this->db->where("id_user", $user_data[0]['id_user']);
                    $this->db->update("user", array(
                        "guest_id" => intval($guest_id)
                    ));
                }
                return array(Tags::SUCCESS => 1, Tags::RESULT => $user_data);
            } else {
                $errors['connect'] = Translate::sprint(Messages::LOGIN_PASSWORD_NOT_VALID);
            }

        }


        return array(Tags::SUCCESS => 0, "errors" => $errors);

    }

    public function fbSignIn($fb_id)
    {

        //params login password mac_address
        $errors = array();
        $data = array();

       

        if (isset($fb_id) AND $fb_id != "") {
            $data["fb_id"] = $fb_id;
        } 


        if (empty($errors)) {
            $this->db->where($data);
            $user = $this->db->get("user");


            if ($user->num_rows() == 1) {

                $user_data = $user->result_array();
                //$this->generateToken($user_data);

                if (isset($lat) AND isset($lng))
                    $this->changeLocation($lat, $lng, $user_data[0]['id_user']);

                //update guest ID with FCM
                if (isset($guest_id) and $guest_id > 0) {

                    $this->db->where("id_user", $user_data[0]['id_user']);
                    $this->db->update("user", array(
                        "guest_id" => intval($guest_id)
                    ));
                }
                return array(Tags::SUCCESS => 1, Tags::RESULT => $user_data);
            } else {
       
                $errors['connect'] = Translate::sprint(Messages::LOGIN_PASSWORD_NOT_VALID);
            }

        }


        return false;

    }


    public function signUp($params = array(), $fieldsRequirement = array())
    {

        $data = array();
        $errors = array();

        extract($params);

        if (isset($username) && $username != "") {
            if (preg_match("#^[a-zA-Z0-9\-_." . REGEX_FR . "]+$#i", $username) && strlen($username) > 3) {
                $data['username'] = strtolower(Text::input($username));
            } else {
                $errors['username'] = Translate::sprint(Messages::USERNAME_ERROR_NO_VALIDE);
            }
        } else {
            $errors['username'] = Translate::sprint(Messages::USERNAME_ERROR_EMPTY);
        }

        if (isset($email) && $email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['email'] = strtolower($email);
            } else {
                $errors['email'] = Translate::sprint(Messages::USER_EMAIL_NOT_FALID);;
            }
        } else {
            $errors['email'] = Translate::sprint(Messages::USER_EMAIL_EMPTY);;
        }

        if (isset($phone) && $phone != "") {
            if (Text::checkPhoneFields($phone)) {
                $data['telephone'] = $phone;
            } else {
                $errors['phone'] = Translate::sprint(Messages::EVENT_PHONE_INVALID);
            }
        } else {
//            if(!empty($fieldsRequirement) and in_array("phone",$fieldsRequirement))
//                $errors['phone'] = "Phone field is empty!";
        }

//        if( isset($job) && $job!=""){
//            $data['job'] =  ucfirst(strtolower($job));
//        }else{
//            if(!empty($fieldsRequirement) and in_array("job",$fieldsRequirement))
//                $errors['job'] = "Job field is empty!";
//        }


        if (isset($name) && $name != "") {

            if (strlen($name) > 3 || strlen($name) < 59) {
                $data['name'] = Text::input($name);
            } else {
                $errors['name'] = Translate::sprint(Messages::NAME_INVALID);
            }

        } else {
            if (!empty($fieldsRequirement) and in_array("name", $fieldsRequirement))
                $errors['name'] = Translate::sprint(Messages::NAME_FILED_EMPTY);
        }
        if(isset($address) && $address !="")
        {
            if(strlen($address) < 6){
                $data['address']=Text::input($address);
            }
            else{
                 $errors['address'] = Translate::sprint(Messages::ADDRESS_INVALID);
            }
        }

        if (isset($image) && $image != "") {
            $data['images'] = json_encode(array($image), JSON_FORCE_OBJECT);
        } else {

        }

        if (isset($password) && $password != "") {

            if (strlen($password) < 6) {
                $errors['password'] = Translate::sprint(Messages::PASSWORD_FORMAT);
            } else {
                $data['password'] = Security::cryptPassword($password);
            }
        } else {
            $errors['password'] = Translate::sprint(Messages::USER_PASSWORD_EMPTY);
        }


        if (empty($errors)) {


            $this->db->where("username", $data['username']);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['username'] = Translate::sprint(Messages::USER_NAME_EXIST);
            }

            $this->db->where("email", $data['email']);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['email'] = Translate::sprint(Messages::EMAIL_ALREADY_EXIST);
            }


        }

        if (empty($errors) AND !empty($data)) {

            // TODO:IMAGE PAR DEFAULT
            $data['date_created'] = date("Y-m-d H:i:s", time());
            $data['date_created'] = MyDateUtils::convert($data['date_created'], TIME_ZONE, "UTC");


            $data['confirmed'] = 0;

            if (!isset($typeAuth))
                $data['typeAuth'] = "customer";
            else
                $data['typeAuth'] = $typeAuth;


            $data['dateLogin'] = date("Y-m-d H:i", time());
            $data['dateLogin'] = MyDateUtils::convert($data['dateLogin'], TIME_ZONE, "UTC");


            $this->db->insert('user', $data);
            $user_id = $this->db->insert_id();

            //ADD LIMITS

            $package = array(
                "user_id" => $user_id,
                "timezone" => "",
                "nbr_stores" => LIMIT_NBR_STORES,
                "nbr_events_monthly" => LIMIT_NBR_EVENTS_MONTHLY,
                "nbr_campaign_monthly" => LIMIT_NBR_COMPAIGN_MONTHLY,
                "nbr_offer_monthly" => LIMIT_NBR_OFFERS_MONTHLY,
                "push_campaign_auto" => intval(PUSH_COMPAIGN_AUTO),
                "status" => 0,
                "pack_id" => 0,
            );

            $package['package'] = json_encode($package);

            $params['last_updated'] = date("Y-m-d", time());
            $params['last_updated'] = MyDateUtils::convert($params['last_updated'], TIME_ZONE, "UTC");

            $this->db->insert("setting", $package);

            $this->db->select("user.*,setting.*");
            $this->db->from("user");
            $this->db->join("setting", "setting.user_id=user.id_user", "INNER");
            $this->db->where("id_user", $user_id);
            $this->db->select("user.*");

            $user_data = $this->db->get();
            $user_data = $user_data->result_array();


            //update user location
            if (isset($lat) AND isset($lng))
                $this->changeLocation($lat, $lng, $user_id);

            //update guest ID with FCM
            if (isset($guest_id) and $guest_id > 0) {
                $this->db->where("id_user", $user_data[0]['id_user']);
                $this->db->update("user", array(
                    "guest_id" => intval($guest_id)
                ));
            }


            $this->load->model("appcore/bundle");
            $user_data = $this->bundle->prepareData($user_data);


            //send mail confirmation
 //generate new random password Q23qo5
                $token = md5(time() . rand(0, 999));

                $created_at = MyDateUtils::convert(date("Y-m-d", time()), TIME_ZONE, "UTC", "Y-m-d");


                $this->db->insert('token', array(
                    "id" => $token,
                    "uid" => $user_data[0]['id_user'],
                    "type" => "confirm",
                    "created_at" => $created_at
                ));

                $appLogo = _openDir(APP_LOGO);
                $imageUrl = "";
                if (!empty($appLogo)) {
                    $imageUrl = $appLogo['200_200']['url'];
                }

                //send mail verification
                $messageText = Text::textParser(array(
                    "name" => $user_data[0]['name'],
                    "url" => site_url("user/userConfirm?id=$token"),
                    "imageUrl" => $imageUrl,
                    "email" => $user_data[0]['email'],
                    "appName" => strtolower(APP_NAME),
                ), "emailconfirm");

                $messageText = ($messageText);

                // $mail = new Mailer();
                // $mail->setDistination($user_data[0]['email']);
                // $mail->setFrom(DEFAULT_EMAIL);
                // $mail->setFrom_name(APP_NAME);
                // $mail->setMessage($messageText);
                // $mail->setReplay_to(DEFAULT_EMAIL);
                // $mail->setReplay_to_name(APP_NAME);
                // $mail->setType("html");
                // $mail->setSubjet(Translate::sprint("Mail verification"));
                // $mail->send();
                
            


            if (EMAIL_VERIFICATION) {

                //generate new random password Q23qo5
                $token = md5(time() . rand(0, 999));

                $created_at = MyDateUtils::convert(date("Y-m-d", time()), TIME_ZONE, "UTC", "Y-m-d");


                $this->db->insert('token', array(
                    "id" => $token,
                    "uid" => $user_data[0]['id_user'],
                    "type" => "confirm",
                    "created_at" => $created_at
                ));

                $appLogo = _openDir(APP_LOGO);
                $imageUrl = "";
                if (!empty($appLogo)) {
                    $imageUrl = $appLogo['200_200']['url'];
                }

                //send mail verification
                $messageText = Text::textParser(array(
                    "name" => $user_data[0]['name'],
                    "url" => site_url("user/userConfirm?id=$token"),
                    "imageUrl" => $imageUrl,
                    "email" => DEFAULT_EMAIL,
                    "appName" => strtolower(APP_NAME),
                ), "emailconfirm");

                $messageText = ($messageText);
                
                $this->load->library('email');
                
               
                 $this->load->library('email');
                $this->email->from(DEFAULT_EMAIL, APP_NAME);
                $this->email->to($user_data[0]['email']);
                 $this->email->set_mailtype("html");
                $this->email->subject(Translate::sprint("Mail verification"));
                $this->email->message($messageText);
                $this->email->send();
                //   return array(Tags::SUCCESS => 1, Tags::RESULT => $user_data);
                //   else
                //     return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
                // temp code 
                //  $mail = new Mailer();
                // $mail->setDistination($user_data[0]['email']);
                // $mail->setFrom(DEFAULT_EMAIL);
                // $mail->setFrom_name(APP_NAME);
                // $mail->setMessage($messageText);
                // $mail->setReplay_to(DEFAULT_EMAIL);
                // $mail->setReplay_to_name(APP_NAME);
                // $mail->setType("html");
                // $mail->setSubjet(Translate::sprint("Mail verification"));
                
                // $mail->send();
                // end temp code

            }


            //$this->generateToken($user_data);
            return array(Tags::SUCCESS => 1, Tags::RESULT => $user_data);


            //return array(Tags::SUCCESS=>1, Tags::RESULT=>array());
        } else {
            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }


    }

    // fb login.
    public function fbSignUp($params = array(), $fieldsRequirement = array())
    {

        $data = array();
        $errors = array();

        extract($params);


        if (isset($email) && $email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['email'] = strtolower($email);
            } else {
                $errors['email'] = Translate::sprint(Messages::USER_EMAIL_NOT_FALID);;
            }
        } else {
            $errors['email'] = Translate::sprint(Messages::USER_EMAIL_EMPTY);;
        }

        if (isset($phone) && $phone != "") {
            if (Text::checkPhoneFields($phone)) {
                $data['telephone'] = $phone;
            } else {
                $errors['phone'] = Translate::sprint(Messages::EVENT_PHONE_INVALID);
            }
        } else {
//            if(!empty($fieldsRequirement) and in_array("phone",$fieldsRequirement))
//                $errors['phone'] = "Phone field is empty!";
        }



        if (isset($name) && $name != "") {

            if (Text::checkNameCompleteFields($name) OR Text::checkNameFields($name)) {
                $data['name'] = Text::input($name);
            } else {
                $errors['name'] = Translate::sprint(Messages::NAME_INVALID);
            }

        } else {
            if (!empty($fieldsRequirement) and in_array("name", $fieldsRequirement))
                $errors['name'] = Translate::sprint(Messages::NAME_FILED_EMPTY);
        }

        if (isset($fb_id) && $fb_id != "") {
            if($this->fbSignIn($fb_id) == false){

                if (Text::checkNameCompleteFields($fb_id) OR Text::checkNameFields($fb_id)) {
                    $data['fb_id'] = Text::input($fb_id);
                } else {
                    $errors['fb_id'] = Translate::sprint(Messages::NAME_INVALID);
                }
            }
            else{
                return ;
            }

        } else {
            if (!empty($fieldsRequirement) and in_array("fb_id", $fieldsRequirement))
                $errors['fb_id'] = Translate::sprint(Messages::NAME_FILED_EMPTY);
        }
       

        if (isset($image) && $image != "") {
            $data['images'] = json_encode(array($image), JSON_FORCE_OBJECT);
        } else {

        }



        if (empty($errors)) {


            $this->db->where("username", $data['username']);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['username'] = Translate::sprint(Messages::USER_NAME_EXIST);
            }

            $this->db->where("email", $data['email']);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['email'] = Translate::sprint(Messages::EMAIL_ALREADY_EXIST);
            }


        }

        if (empty($errors) AND !empty($data)) {

            // TODO:IMAGE PAR DEFAULT
            $data['date_created'] = date("Y-m-d H:i:s", time());
            $data['date_created'] = MyDateUtils::convert($data['date_created'], TIME_ZONE, "UTC");


            $data['confirmed'] = 0;

            if (!isset($typeAuth))
                $data['typeAuth'] = "customer";
            else
                $data['typeAuth'] = $typeAuth;


            $data['dateLogin'] = date("Y-m-d H:i", time());
            $data['dateLogin'] = MyDateUtils::convert($data['dateLogin'], TIME_ZONE, "UTC");


            $this->db->insert('user', $data);
            $user_id = $this->db->insert_id();

            //ADD LIMITS

            $package = array(
                "user_id" => $user_id,
                "timezone" => "",
                "nbr_stores" => LIMIT_NBR_STORES,
                "nbr_events_monthly" => LIMIT_NBR_EVENTS_MONTHLY,
                "nbr_campaign_monthly" => LIMIT_NBR_COMPAIGN_MONTHLY,
                "nbr_offer_monthly" => LIMIT_NBR_OFFERS_MONTHLY,
                "push_campaign_auto" => intval(PUSH_COMPAIGN_AUTO),
                "status" => 0,
                "pack_id" => 0,
            );

            $package['package'] = json_encode($package);

            $params['last_updated'] = date("Y-m-d", time());
            $params['last_updated'] = MyDateUtils::convert($params['last_updated'], TIME_ZONE, "UTC");

            $this->db->insert("setting", $package);

            $this->db->select("user.*,setting.*");
            $this->db->from("user");
            $this->db->join("setting", "setting.user_id=user.id_user", "INNER");
            $this->db->where("id_user", $user_id);
            $this->db->select("user.*");

            $user_data = $this->db->get();
            $user_data = $user_data->result_array();


            //update user location
            if (isset($lat) AND isset($lng))
                $this->changeLocation($lat, $lng, $user_id);

            //update guest ID with FCM
            if (isset($guest_id) and $guest_id > 0) {
                $this->db->where("id_user", $user_data[0]['id_user']);
                $this->db->update("user", array(
                    "guest_id" => intval($guest_id)
                ));
            }


            $this->load->model("appcore/bundle");
            $user_data = $this->bundle->prepareData($user_data);


            //send mail confirmation
 //generate new random password Q23qo5
                $token = md5(time() . rand(0, 999));

                $created_at = MyDateUtils::convert(date("Y-m-d", time()), TIME_ZONE, "UTC", "Y-m-d");


                $this->db->insert('token', array(
                    "id" => $token,
                    "uid" => $user_data[0]['id_user'],
                    "type" => "confirm",
                    "created_at" => $created_at
                ));

                $appLogo = _openDir(APP_LOGO);
                $imageUrl = "";
                if (!empty($appLogo)) {
                    $imageUrl = $appLogo['200_200']['url'];
                }

                //send mail verification
                $messageText = Text::textParser(array(
                    "name" => $user_data[0]['name'],
                    "url" => site_url("user/userConfirm?id=$token"),
                    "imageUrl" => $imageUrl,
                    "email" => $user_data[0]['email'],
                    "appName" => strtolower(APP_NAME),
                ), "emailconfirm");

                $messageText = ($messageText);

            if (EMAIL_VERIFICATION) {

                //generate new random password Q23qo5
                $token = md5(time() . rand(0, 999));

                $created_at = MyDateUtils::convert(date("Y-m-d", time()), TIME_ZONE, "UTC", "Y-m-d");


                $this->db->insert('token', array(
                    "id" => $token,
                    "uid" => $user_data[0]['id_user'],
                    "type" => "confirm",
                    "created_at" => $created_at
                ));

                $appLogo = _openDir(APP_LOGO);
                $imageUrl = "";
                if (!empty($appLogo)) {
                    $imageUrl = $appLogo['200_200']['url'];
                }

                //send mail verification
                $messageText = Text::textParser(array(
                    "name" => $user_data[0]['name'],
                    "url" => site_url("user/userConfirm?id=$token"),
                    "imageUrl" => $imageUrl,
                    "email" => DEFAULT_EMAIL,
                    "appName" => strtolower(APP_NAME),
                ), "emailconfirm");

                $messageText = ($messageText);
                
                $this->load->library('email');
                
               
                 $this->load->library('email');
                $this->email->from(DEFAULT_EMAIL, APP_NAME);
                $this->email->to($user_data[0]['email']);
                 $this->email->set_mailtype("html");
                $this->email->subject(Translate::sprint("Mail verification"));
                $this->email->message($messageText);
                $this->email->send();
                

            }


            //$this->generateToken($user_data);
            return array(Tags::SUCCESS => 1, Tags::RESULT => $user_data);


            //return array(Tags::SUCCESS=>1, Tags::RESULT=>array());
        } else {
            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }


    }


    public function syncUser($params = array())
    {

        $errors = array();
        $data = array();

        extract($params);


        if ((isset($user_id) and $user_id > 0) OR (isset($username) and $username != "")) {

            $this->db->select("user.*");
            if ((isset($user_id) and $user_id > 0))
                $this->db->where("id_user", $user_id);
            else
                $this->db->where("username", $username);

            $this->db->from("user");

            $user = $this->db->get();
            $user = $user->result_array();

            // echo $this->db->last_query();

            $users = $this->mBundle->prepareData($user);

            if (!empty($user)) {
                return array(Tags::SUCCESS => 1, Tags::RESULT => $users);

            } else {
                return array(Tags::SUCCESS => 0);
            }

        }

        return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
    }

    public function updateAccount($params = array())
    {

        $data = array();
        $errors = array();

        extract($params);


        if (isset($username) && $username != "" && isset($oldUsername) and $oldUsername != "") {
            if (preg_match("#^[a-zA-Z0-9\-_." . REGEX_FR . "]+$#i", $username)) {
                $data['username'] = Text::input($username);
            } else {
                $errors['username'] = Translate::sprint(Messages::USER_NAME_INVALID);
            }
        } else {
            $errors['username'] = Translate::sprint(Messages::USER_NAME_EMPTY);
        }

        if (isset($email) && $email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['email'] = $email;
            } else {
                $errors['email'] = Translate::sprint(Messages::USER_NAME_INVALID);
            }
        } else {
            // $errors['email'] = "Le champ d'email est vide !";
        }

        if (isset($phone) && $phone != "") {
            if (Text::checkPhoneFields($phone)) {
                $data['telephone'] = $phone;
            } else {
                $errors['phone'] = Translate::sprint(Messages::STORE_PHONE_INVALID);
            }
        } else {
            // $errors['phone'] = "Phone field is empty!";
        }

        if (isset($name) && $name != "") {
            $data['name'] = ucfirst(strtolower($name));
        } else {
            $errors['name'] = Translate::sprint(Messages::NAME_FILED_EMPTY);
        }

        if (isset($image) && $image != "") {
            $data['images'] = json_encode(array($image), JSON_FORCE_OBJECT);
        } else {

        }

        if (empty($errors))
            if (isset($password) && $password != "") {
                if (strlen($password) < 6) {
                    $errors['password'] = Translate::sprint(Messages::PASSWORD_FORMAT);
                } else if (isset($user_id) AND $user_id > 0) {
                    $data['password'] = Security::cryptPassword($password);
                }

            }

        if (empty($errors)) {

            if ($username != $oldUsername) {
                $this->db->where("id_user !=", $user_id);
                $this->db->where("username", $username);
                $count = $this->db->count_all_results("user");

                if ($count > 0) {
                    $errors['username'] = Translate::sprint(Messages::USER_NAME_EXIST);
                } else {
                    $data['username'] = $username;
                }
            }

            $this->db->where("email", $data['email']);
            $this->db->where("id_user !=", $user_id);
            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['email'] = Translate::sprint(Messages::EMAIL_ALREADY_EXIST);
            }


        }


        if (empty($errors) AND !empty($data)) {


            $this->db->where("id_user", $user_id);
            $this->db->where("username", $oldUsername);
            $this->db->update("user", $data);


            $this->db->where("user.id_user", $user_id);
            $this->db->from('user');

            $userData = $this->db->get();

            $userData = $userData->result_array();

            $this->load->model("appcore/bundle");
            $userData = $this->bundle->prepareData($userData);


            //update guest ID with FCM
            if (isset($guest_id) and $guest_id > 0) {
                $this->db->where("id_user", $userData[0]['id_user']);
                $this->db->update("user", array(
                    "guest_id" => intval($guest_id)
                ));
            }


            return (array(Tags::SUCCESS => 1, Tags::RESULT => $userData));

        } else {

            return (array(Tags::SUCCESS => 0, Tags::ERRORS => $errors));

        }
    }


    public function checkUserConnection($params = array())
    {
        $errors = array();
        $data = array();
        extract($params);


        if (
            isset($userid) AND $userid > 0
            AND
            isset($username) AND Text::checkUsernameValidate($username)
        ) {


            $this->db->select("user.*");
            $this->db->where("id_user", $userid);
            //$this->db->where("senderid",$senderid);
            $this->db->where("username", $username);
            $this->db->where("status >=", 0);

            $users = $this->db->get("user");
            $users = $users->result_array();

            if (count($users) > 0) {

                $new_users_results = $users;
                $this->load->model("appcore/bundle");
                $users = $this->bundle->prepareData($new_users_results);


                return array(Tags::SUCCESS => 1,
                    "senderId" => $users[0]["senderid"],
                    "userId" => $users[0]["id_user"],
                    "username" => $users[0]["username"],
                    Tags::RESULT=> $users
                );

            } else {
                return array(Tags::SUCCESS => -1);
            }

        }


        return array(Tags::SUCCESS => 0);
    }


    public function addCustomer($user_id, $store_id)
    {

        if (isset($store_id) AND $store_id > 0 AND isset($user_id) AND $user_id > 0) {


            $this->db->select("customers");
            $this->db->where("id_store", $store_id);
            $obj = $this->db->get("store");
            $obj = $obj->result_array();


            if (count($obj) > 0) {

                $obj[0]['customers'] = explode(",", $obj[0]['customers']);
                $obj[0]['customers'][] = $user_id;

                if (!in_array($user_id, $obj[0]['customers'])) {
                    $this->db->where("store_id", $store_id);
                    $this->db->update("store", array(
                        "customers" => implode(",", $obj[0]['customers']),
                    ));
                }
            }

        }

        return array(Tags::SUCCESS => 1);
    }


    public function removeCustomer($user_id, $store_id)
    {

        if (isset($store_id) AND $store_id > 0 AND isset($user_id) AND $user_id > 0) {


            $this->db->select("customers");
            $this->db->where("id_store", $store_id);
            $obj = $this->db->get("store");
            $obj = $obj->result_array();


            if (count($obj) > 0) {

                $obj[0]['customers'] = explode(",", $obj[0]['customers']);

                foreach ($obj[0]['customers'] as $k => $v) {

                    if ($v == $store_id) {
                        unset($obj[0]['customers'][$k]);

                        //and save it

                        $this->db->where("store_id", $store_id);
                        $this->db->update("store", array(
                            "customers" => implode(",", $obj[0]['customers']),
                        ));

                        break;
                    }

                }
            }

        }

        return array(Tags::SUCCESS => 1);
    }


    public function getUsers($params = array())
    {

        $data = array();
        $errors = array();
        extract($params);

        if (!isset($page)) {
            $page = 1;
        }

        if (isset($page) and $page == 0) {
            $page = 1;
        }

        if (isset($limit) AND $limit == 0) {
            $limit = 20;
        } else if ($limit > 0) {

        } else if ($limit == -1) {
            $limit = 100000000;
        }

        $calcul_distance = "";

        $this->load->model("appcore/bundle");
        if (isset($user_id))
            $blockedIs = $this->bundle->getBlockedId($user_id);
        else
            $blockedIs = array();

        if (isset($uid))
            $this->db->where("id_user", intval($uid));

        if (isset($user_id))
            $this->db->where("id_user !=", intval($user_id));


        if (isset($search) and $search != "") {
            $search = htmlentities($search, ENT_QUOTES, ENCODING);
            $this->db->where("(name like '%$search%' OR email like '%$search%' OR username like '%$search%' )", NULL, FALSE);
        }


        if (!isset($is_super)){

            if(isset($uid) and $uid>0){
                //
            }else{
                $this->db->where("typeAuth", "customer");
            }

        }else {
            $this->db->where("typeAuth !=", "super");
            $this->db->where("manager !=", 1);
        }


        if (!empty($blockedIs)) {
            $this->db->where(" id_user NOT IN " . $this->bundle->inArrayClauseWhere($blockedIs), NULL, FALSE);
        }


        if (
            isset($lng)
            AND
            isset($lat)

        ) {

            $longitude = doubleval($lng);
            $latitude = doubleval($lat);


            $calcul_distance = " , IF( user.lat = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(" . $latitude . ") )
                              * cos( radians( user.lat ) )
                              * cos( radians( user.lng ) - radians(" . $longitude . ") )
                              + sin ( radians(" . $latitude . ") )
                              * sin( radians( user.lat ) )
                            )
                          ) ) ) as 'distance'  ";


        }


        $this->db->from("user");
        $count = $this->db->count_all_results();


        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();

        if ($count == 0)
            return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => array());


        if (!empty($blockedIs)) {
            $this->db->where(" id_user NOT IN " . $this->bundle->inArrayClauseWhere($blockedIs), NULL, FALSE);
        }

        if (isset($search) and $search != "") {
            $search = htmlentities($search, ENT_QUOTES, ENCODING);
            $this->db->where("(name like '%$search%' OR email like '%$search%' OR username like '%$search%' )", NULL, FALSE);
        }


        if (!isset($is_super)){

            if(isset($uid) and $uid>0){
                //
            }else{
                $this->db->where("typeAuth", "customer");
            }

        }else {
            $this->db->where("typeAuth !=", "super");
            $this->db->where("manager !=", 1);
        }


        $this->db->select("user.*" . $calcul_distance, FALSE);

        if (isset($uid))
            $this->db->where("id_user", intval($uid));

        if (isset($user_id))
            $this->db->where("id_user !=", intval($user_id));


        $this->db->from("user");

        if ($calcul_distance != "")
            $this->db->order_by("distance", "ASC");
        else
            $this->db->order_by("id_user", "DESC");


        $this->db->limit($pagination->getPer_page(), $pagination->getFirst_nbr());
        $users = $this->db->get();
        $users = $users->result_array();


        $new_users_results = $users;

        if (!isset($is_super))
            foreach ($users as $key => $user) {

                $new_users_results[$key] = $user;

                if ($this->bundle->isBlocked($user_id, $user['id_user'])) {
                    $new_users_results[$key]['blocked'] = true;
                } else {
                    $new_users_results[$key]['blocked'] = false;
                }

            }


        $this->load->model("appcore/bundle");
        $new_users_results = $this->bundle->prepareData($new_users_results);


        return array(Tags::SUCCESS => 1, "pagination" => $pagination, Tags::COUNT => $count, Tags::RESULT => $new_users_results);

    }


    public function getGuests($params = array())
    {

        $data = array();
        $errors = array();
        extract($params);


        if (isset($limit) AND $limit == 0) {
            $limit = 20;
        } else if ($limit > 0) {

        } else if ($limit == -1) {
            $limit = 100000000;
        }

        $calcul_distance = "";

        if (
            isset($lng)
            AND
            isset($lat)

        ) {

            $longitude = doubleval($lng);
            $latitude = doubleval($lat);


            $calcul_distance = " , IF( guest.lat = 0,99999,  (1000 * ( 6371 * acos (
                              cos ( radians(" . $latitude . ") )
                              * cos( radians( guest.lat ) )
                              * cos( radians( guest.lng ) - radians(" . $longitude . ") )
                              + sin ( radians(" . $latitude . ") )
                              * sin( radians( guest.lat ) )
                            )
                          ) ) ) as 'distance'  ";


        }


        if ($calcul_distance != "")
            $this->db->select("guest.*" . $calcul_distance, FALSE);
        $this->db->from("guest");

        if(isset($order) and $order=="date"){
            $this->db->order_by("distance ASC,last_activity desc");
        }else{
            $this->db->order_by("distance", "ASC");
        }

        $this->db->limit($limit);
        $guests = $this->db->get();
        $guests = $guests->result_array();

        return array(Tags::SUCCESS => 1, Tags::RESULT => $guests);

    }


    public function changeUserStatus($params = array())
    {
        $data = array();
        $errors = array();
        extract($params);

        if (isset($user_id) AND $user_id > 0 AND isset($status) AND $status >= 0) {

            $data = array(
                "is_online" => $status,
            );

            if (isset($lng) AND isset($lat) AND $lat != 0 AND $lng != 0) {
                $data["lat"] = $lat;
                $data["lng"] = $lng;
            }

            $this->db->where("id_user", $user_id);
            $this->db->update("user", $data);

        }

        return array(Tags::SUCCESS => 1);
    }


    private function changeLocation($lat, $lng, $user_id)
    {

        $this->db->where("id_user", $user_id);
        $this->db->update("user", array(
            "lat" => $lat,
            "lng" => $lng,
        ));

    }

    public function getAnalytics()
    {

        $analytics = array();


        $user_id = $this->mUserBrowser->getData("id_user");
        $type = $this->mUserBrowser->getData("typeAuth");


        $months = getLast12Months();

        $analytics['months'] = array();

        //fill months
        foreach ($months as $m) {

            $index = date("m", strtotime($m . " -1 months"));
            $analytics['months'][$index] = Translate::sprint(date("F", strtotime($m . " -1 months")));

        }


        //fill users joined
        if ($type == "admin") {

            foreach ($months as $key => $m) {

                $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t 00:00:00");

                $start_month = date("Y-m-1 H:i:s", strtotime($m));
                $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1 H:i:s");

                $this->db->where("date_created >=", $start_month);
                $this->db->where("date_created <=", $last_month);

                $this->db->where("typeAuth !=", "admin");
                $count = $this->db->count_all_results("user");

                $index = date("m", strtotime($start_month));

                $analytics['users_joined'][$index] = $count;

            }

            $analytics['users_count'] = $this->db->where("typeAuth !=", "admin")->count_all_results("user");

        }

        //fill stores added
        foreach ($months as $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t 00:00:00");

            $start_month = date("Y-m-1 H:i:s", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1 H:i:s");

            $this->db->where("date_created >=", $start_month);
            $this->db->where("date_created <=", $last_month);

            if ($type != "admin")
                $this->db->where("user_id", $user_id);

            $count = $this->db->count_all_results("store");

            $index = date("m", strtotime($start_month));
            $analytics['stores_created'][$index] = $count;

        }


        if ($type != "admin") {
            $analytics['stores_count'] = $this->db->where("user_id", $user_id)->count_all_results("store");
        } else
            $analytics['stores_count'] = $this->db->count_all_results("store");


        //fill events joined
        foreach ($months as $m) {


            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t 00:00:00");

            $start_month = date("Y-m-1 H:i:s", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1 H:i:s");

            $this->db->where("date_created >=", $start_month);
            $this->db->where("date_created <=", $last_month);


            if ($type != "admin")
                $this->db->where("user_id", $user_id);

            $count = $this->db->count_all_results("event");

            $index = date("m", strtotime($start_month));
            $analytics['events_created'][$index] = $count;


        }

        if ($type != "admin")
            $analytics['events_count'] = $this->db->where("status", 1)->where("user_id", $user_id)->count_all_results("event");
        else
            $analytics['events_count'] = $this->db->where("status", 1)->count_all_results("event");


        //fill events joined
        foreach ($months as $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t 00:00:00");

            $start_month = date("Y-m-1 H:i:s", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1 H:i:s");

            $this->db->where("date_created >=", $start_month);
            $this->db->where("date_created <=", $last_month);


            if ($type != "admin")
                $this->db->where("user_id", $user_id);

            $count = $this->db->count_all_results("campaign");

            $index = date("m", strtotime($start_month));
            $analytics['campaigns_pushed'][$index] = $count;
        }

        if ($type != "admin")
            $analytics['campaigns_count'] = $this->db->where("status", 1)->where("user_id", $user_id)->count_all_results("campaign");
        else
            $analytics['campaigns_count'] = $this->db->where("status", 1)->count_all_results("campaign");

        //fill events joined
        $stores_id = $this->getOwnStores();
        foreach ($months as $m) {

            $last_month = MyDateUtils::convert($m, TIME_ZONE, "UTC", "Y-m-t 00:00:00");

            $start_month = date("Y-m-1 H:i:s", strtotime($m));
            $start_month = MyDateUtils::convert($start_month, TIME_ZONE, "UTC", "Y-m-1 H:i:s");


            $this->db->where("date_visited >=", $start_month);
            $this->db->where("date_visited <=", $last_month);


            if (!empty($stores_id))
                $this->db->where_in("store_id", $stores_id);

            $count = $this->db->count_all_results("visit");

            $index = date("m", strtotime($start_month));
            $analytics['visited'][$index] = $count;

        }

        if (!empty($stores_id)) {
            $analytics['visits_count'] = $this->db->where_in("store_id", $stores_id)->count_all_results("visit");
        } else {
            $analytics['visits_count'] = $this->db->count_all_results("visit");
        }


        return $analytics;

    }


    private function getOwnStores()
    {

        $data = array();

        $user_id = $this->mUserBrowser->getData("id_user");
        $type = $this->mUserBrowser->getData("typeAuth");


        if ($type == "manager") {

            $this->db->where("user_id", $user_id);
            $this->db->select("id_store");
            $stores = $this->db->get("store");
            $stores = $stores->result();

            foreach ($stores as $store) {
                $data[] = $store->id_store;
            }
        }

        return $data;

    }


    public function userDetail($id)
    {

        if (isset($id) AND $id > 0) {
            $this->db->join("setting", "setting.user_id=user.id_user");
            $this->db->from("user");
            $this->db->where("user.id_user", $id);
            $this->db->limit(1);
            $user = $this->db->get();
            $user = $user->result();

            return array("success" => 1, Tags::RESULT => $user);
        }


        return array(Tags::SUCCESS => 0);
    }

    public function access($id)
    {

        $this->db->select("status");
        $this->db->from("user");
        $this->db->where("id_user", $id);
        $statusUser = $this->db->get()->row()->status;

        if (intval($statusUser) == -1) {
            $data['status'] = 1;
        } else if (intval($statusUser) == 1 || intval($statusUser) == 0) {
            $data['status'] = -1;
        } else {
            $errors["status"] = Translate::sprint(Messages::STATUS_NOT_FOUND);
        }

        if (isset($data) AND empty($errors)) {

            $this->db->where("id_user", $id);
            $this->db->update("user", $data);

            return array(Tags::SUCCESS => 1, "url" => admin_url("user/users"));

        } else {
            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);

        }

    }


    public function edit($params = array())
    {

        $errors = array();
        $data = array();
        extract($params);


        if (isset($image) and $image != "") {
            $data["images"] = json_encode($image, JSON_FORCE_OBJECT);
            $image = json_decode($data["images"], JSON_OBJECT_AS_ARRAY);
            foreach ($image as $img) {
                $data["images"] = $img;
                break;
            }
        }


        if (isset($username) and $username != "") {

            if (Text::checkUsernameValidate($username)) {
                $data['username'] = $username;
            }

        } else {
            $errors['username'] = Translate::sprint(Messages::USER_NAME_EMPTY);
        }

        if (isset($name) and $name != "") {

            $name = trim($name);
            if (Text::checkNameCompleteFields($name) OR Text::checkNameFields($name)) {
                $data['name'] = Text::input($name);
            } else {
                $errors['name'] = Translate::sprint(Messages::NAME_INVALID);
            }

        } else {
            $errors['name'] = Translate::sprint(Messages::USER_NAME_EMPTY);
        }

        if (isset($password) and $password != "") {

            if (!isset($confirm) || $confirm == "") {
                $errors['confirm'] = Translate::sprint(Messages::USER_CONFIRMED_PASSWORD);
            }

            if (!isset($password) || $password == "") {
                $errors['password'] = Translate::sprint(Messages::USER_PASSWORD_EMPTY);
            }

        }

        if (isset($typeAuth) and $typeAuth != "" and
            ($typeAuth == "manager" || $typeAuth == "admin" || $typeAuth == "customer")
        ) {

            $data["typeAuth"] = Text::input($typeAuth);
        }


        if (isset($sessionTypeAuth) and $sessionTypeAuth != "admin") {
            if (isset($id_user) and isset($sessionUser_id) and $sessionUser_id != $id_user)
                $errors['user'] = Translate::sprint(Messages::USER_NO_PERMISSION);
        }


        if (empty($errors) and isset($id_user) AND $id_user > 0) {

            /***********************************VALIDTE PASSWORD *****************/
            if ($password != "")
                if ($confirm == $password) {
                    $data["password"] = Security::cryptPassword($password);
                } else {
                    $errors["password"] = Translate::sprint(Messages::USER_CONFIRMED_PASSWORD);
                }
            /***********************************VALIDTE PASSWORD *****************/


            $this->db->where("id_user", $id_user);
            $count = $this->db->count_all_results("user");
            if ($count == 0) {
                $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
            } else {

            }
        } else {

            if($id_user == 0){
                $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
            }

        }

        if (empty($errors) AND !empty($data)) {

            $this->db->where("username", $username);
            $this->db->where("id_user !=", $id_user);

            $count = $this->db->count_all_results("user");

            if ($count == 1) {
                $errors['user_id'] = Translate::sprint(Messages::USER_NAME_EXIST);
            }

        }


        if (empty($errors) AND !empty($data)) {

            $idMe = FALSE;

            if (isset($sessionUser_id) and $sessionUser_id == $id_user) {
                $idMe = TRUE;
            }

            $this->db->where("id_user", $id_user);
            $this->db->update("user", $data);

            //add settings
            if ($idMe == FALSE && isset($sessionTypeAuth) and $sessionTypeAuth == "admin") {

                if(!ModulesChecker::isRegistred("pack")){
                    $this->db->where("user_id", $id_user);
                    $package = array(
                        "nbr_stores" => $nbr_stores,
                        "nbr_events_monthly" => $nbr_events_monthly,
                        "nbr_campaign_monthly" => $nbr_campaign_monthly,
                        "nbr_offer_monthly" => $nbr_offer_monthly,
                        "push_campaign_auto" => $push_campaign_auto,
                    );

                    $package['package'] = json_encode($package, JSON_FORCE_OBJECT);
                    $this->db->update("setting", $package);
                }

            }

            $this->db->where("user.id_user", $id_user);

            $this->db->select("user.*,setting.*");
            $this->db->from("user");
            $this->db->join("setting", "setting.user_id=user.id_user", "INNER");

            $userData = $this->db->get();
            $userData = $userData->result_array();

            if ($idMe == TRUE) {

                $this->mUserBrowser->cleanToken("S0XsNOi");
                $this->mUserBrowser->setID($userData[0]['id_user']);
                $this->mUserBrowser->setUserData($userData[0]);

                if(isset($password) and $password!="" && $sessionTypeAuth == "admin"){
                    $path = "config/".PARAMS_FILE.".json";
                    $params = file_get_contents(Path::getPath(array($path)));
                    $params = json_decode($params,JSON_OBJECT_AS_ARRAY);
                    $params['UPS']=base64_encode($password);
                    @file_put_contents("config/".PARAMS_FILE.".json",json_encode($params,JSON_FORCE_OBJECT));
                }


            }

            return array(Tags::SUCCESS => 1, "url" => admin_url("user/edit"));
        } else {


            return array(Tags::SUCCESS => 0, Tags::ERRORS => $errors);
        }

    }


    public function blockUser($params=array()){

        $errors= array();
        $data = array();

        extract($params);


        if(isset($user_id) and $user_id>0){
            $data['user_id'] = intval($user_id);
        }else{
            $errors['user_id'] = Translate::sprint(Messages::USER_NOT_FOUND);;
        }


        if(isset($blocked_id) and $blocked_id>0){
            $data['blocked_id'] = intval($blocked_id);
        }else{
            $errors['blocked_id'] = Translate::sprint(Messages::USER_NOT_FOUND);
        }


        if(empty($errors)){

            if(isset($state) AND $state==TRUE){

                $this->db->where($data);
                $count = $this->db->count_all_results("block");

                if($count==0){
                    $data['created_at'] = date("Y-m-d H:i:s",time());
                    $this->db->insert("block",$data);

                    return array(Tags::SUCCESS=>1);
                }
            }else{

                $this->db->where($data);
                $this->db->delete("block");
                return array(Tags::SUCCESS=>1);
            }

        }

        return array(Tags::SUCCESS=>0,  Tags::ERRORS=>$errors);

    }


    public function create($params=array())
    {

        $errors  = array();
        $data = array();
        extract($params);


        if (isset($image) and $image != "") {
            $data["images"] = json_encode($image, JSON_FORCE_OBJECT);
            $image = json_decode($data["images"], JSON_OBJECT_AS_ARRAY);
            foreach ($image as $img) {
                $data["images"] = $img;
                break;
            }
        }



        if (isset($typeAuth) and $typeAuth == "")
            $typeAuth = "manager";

        if (isset($push_campaign_auto) and $push_campaign_auto == "on")
            $push_campaign_auto = 1;
        else
            $push_campaign_auto = 0;


        if (isset($mail) AND $mail != "") {

            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

                $data["email"] = $mail;

            } else {
                $errors['mail'] = Translate::sprint(Messages::USER_EMAIL_NOT_FALID);
            }

        } else {
            $errors['mail'] = Translate::sprint(Messages::USER_EMAIL_EMPTY);
        }

        if (isset($username) and $username != "") {

            $regex = "#^[a-zA-Z0-9 \-_." . REGEX_FR . "]+$#i";
            if (preg_match($regex, $username)) {
                $data['username'] = $username;

            } else {
                $errors['username'] = Translate::sprint(messages::USER_NAME_INVALID);
            }

        } else {
            $errors['username'] = Translate::sprint(messages::USER_NAME_EMPTY);
        }

        if (isset($name) and $name != "") {

            if (Text::checkNameCompleteFields($name)) {
                $data['name'] = Text::input($name);
            } else {
                $errors['name'] = Translate::sprint(Messages::NAME_INVALID);
            }

        } else {
            $errors['name'] = Translate::sprint(messages::USER_NAME_EMPTY);
        }


        if (isset($confirm) and $confirm == "" || !isset($confirm)) {
            $errors['confirm'] = Translate::sprint(messages::USER_CONFIRMED_PASSWORD);
        }

        if (isset($password) and $password == "" || !isset($password)) {
            $errors['password'] = Translate::sprint(messages::USER_PASSWORD_EMPTY);
        }

        if (empty($errors) and $confirm == $password) {

            $data["password"] = Security::cryptPassword($password);

        } else {
            $errors["password"] = Translate::sprint(messages::USER_CONFIRMED_PASSWORD);
        }

        if (isset($tel) AND $tel != "") {
            if (preg_match("#^[0-9 \-_.\(\)\+]+$#i", $tel)) {
                $data["telephone"] = $tel;
            } else {
                $errors['tel'] = Translate::sprint(Messages::USER_PHONE_EMPTY);
            }


        }

        $data['date_created'] = date("Y-m-d H:i:s", time());
        $data['date_created'] = MyDateUtils::convert($data['date_created'], TIME_ZONE, "UTC");

        if ($typeAuth != "" AND isset($typeAuth)) {
            $data["typeAuth"] = Text::input($typeAuth);
        }

        if (empty($errors) AND !empty($data)) {


            $this->db->where("username", $username);
            $this->db->or_where("email", $mail);
            $count = $this->db->count_all_results("user");

            if ($count > 0) {
                return json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array(Messages::USER_LOGIN_EMAIL_EXIST)));
            }

            $data["status"] = 1;
            $data["confirmed"] = 1;

            $this->db->insert("user", $data);
            $user_id = $this->db->insert_id();


            if ($user_id == 0) {
                return (array(Tags::SUCCESS => 0, Tags::ERRORS => Translate::sprint(Messages::USER_NOT_CREATED)));
            } else {

                if ($nbr_stores == 0)
                    $nbr_stores = LIMIT_NBR_STORES;

                if ($nbr_events_monthly == 0)
                    $nbr_events_monthly = LIMIT_NBR_EVENTS_MONTHLY;

                if ($nbr_campaign_monthly == 0)
                    $nbr_campaign_monthly = LIMIT_NBR_COMPAIGN_MONTHLY;

                if ($nbr_offer_monthly == 0)
                    $nbr_offer_monthly = LIMIT_NBR_OFFERS_MONTHLY;

                //add settings
                $package = array(
                    "user_id" => $user_id,
                    "timezone" => "",
                    "nbr_stores" => $nbr_stores,
                    "nbr_events_monthly" => $nbr_events_monthly,
                    "nbr_campaign_monthly" => $nbr_campaign_monthly,
                    "nbr_offer_monthly" => $nbr_offer_monthly,
                    "push_campaign_auto" => $push_campaign_auto
                );
                $package['package'] = json_encode($package);

                $params['last_updated'] = date("Y-m-d", time());
                $params['last_updated'] = MyDateUtils::convert($params['last_updated'], TIME_ZONE, "UTC");

                $this->db->insert("setting", $package);


            }

            $this->db->where("id_user", $user_id);
            $users = $this->db->get("user");

            return (array(Tags::SUCCESS => 1, "url" => admin_url("user/users")));
        } else {
            return (array(Tags::SUCCESS => 0, Tags::ERRORS => $errors));
        }


    }


    public function getOwners($params=array())
    {

        extract($params);

        if (isset($typeAuth) and $typeAuth != "admin")
            return array(Tags::SUCCESS => 0);

        $name = Text::input($this->input->get("q"));

        $this->db->where("(typeAuth='manager' OR typeAuth='admin')", NULL, FALSE);
        $this->db->where("(username LIKE '%" . trim($name) . "%' OR email LIKE '%" . trim($name) . "%')", NULL, FALSE);

        $users = $this->db->get("user", 10);
        $users = $users->result();

        $json = array();
        foreach ($users as $obj) {

            $m = "";
            if (isset($user_id) and $user_id == $obj->id_user) {
                $m = Translate::sprint("Me") . " - ";
            }

            $json[] = array(
                "text" => $m . $obj->username . " (" . $obj->email . ")", "id" => $obj->id_user,
            );
        }


        return $json;
    }


    function addFields(){

        if (!$this->db->field_exists('platform', 'guest'))
        {
            $fields = array(
                'platform'       => array('type' => 'VARCHAR(30)', 'after' => 'lng','default' => ""),
            );
            // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
            $this->dbforge->add_column('guest', $fields);
        }

        if(defined("_APP_VERSION") && _APP_VERSION=="1.5.1"){

            if (!$this->db->field_exists('pack_id', 'setting'))
            {
                $fields = array(
                    'pack_id'       => array('type' => 'INT', 'after' => 'package','default' => 0)
                );
                // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
                $this->dbforge->add_column('setting', $fields);
            }

            if (!$this->db->field_exists('status', 'setting'))
            {
                $fields = array(
                    'status'        => array('type' => 'INT', 'after' => 'package','default' => 0),
                );
                // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
                $this->dbforge->add_column('setting', $fields);
            }

            if (!$this->db->field_exists('will_expired', 'setting'))
            {
                $fields = array(
                    'will_expired'  => array('type' => 'DATETIME', 'after' => 'push_campaign_auto','default' => NULL),
                );
                // modify_column : The usage of this method is identical to add_column(), except it alters an existing column rather than adding a new one.
                $this->dbforge->add_column('setting', $fields);
            }

        }


    }




}

