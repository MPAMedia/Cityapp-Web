<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Pages extends MY_Controller {

    private $templateName = "apex_app_template";
    public function __construct(){
        parent::__construct();


        define("FRONTEND_TEMPLATE_NAME",$this->templateName);


        //load model
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");
        $this->load->model("store/store_model","mStoreModel");

        $this->load->model("setting/config_model","mConfigModel");
        $this->load->model("setting/update_model","mUpdateModel");

    }


    public function error404(){
        $this->load->view("backend/header");
        $this->load->view("backend/error404");
        $this->load->view("backend/footer");
    }


    public function index(){


        if(ENABLE_FRONT_END==TRUE){
            $this->load->view("frontend/".$this->templateName."/include/header");
            $this->load->view("frontend/".$this->templateName."/home");
            $this->load->view("frontend/".$this->templateName."/include/footer");
        }else{
            redirect(admin_url("user/login"));
        }

    }

    public function fpassword(){


        redirect(admin_url("user/fpassword"));

    }


    public function myPortal(){


        $this->load->model("User/mUserModel");

        redirect(admin_url("user/login"));

    }

    public function webdashboard(){


        $this->load->model("User/mUserModel");

        $this->session->set_userdata(array(
            "agent" => "mobile"
        ));

        redirect(admin_url("user/login"));

    }


    public function mail(){

        // My modifications to mailer script from:
        // http://blog.teamtreehouse.com/create-ajax-contact-form
        // Added input sanitizing to prevent injection

        // Only process POST reqeusts.
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form fields and remove whitespace.
            $name = strip_tags(trim($_POST["name"]));
            $name = str_replace(array("\r","\n"),array(" "," "),$name);
            $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
            // $cont_subject = trim($_POST["subject"]);
            $message = trim($_POST["message"]);

            // Check that data was sent to the mailer.
            if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Set a 400 (bad request) response code and exit.
                http_response_code(400);
                echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                exit;
            }

            // Set the recipient email address.
            // FIXME: Update this to your desired email address.
            $recipient = DEFAULT_EMAIL;

            // Set the email subject.
            $subject = "New contact from $name";

            // Build the email content.
            $email_content = "Name: $name\n";
            $email_content .= "Email: $email\n\n";
            // $email_content .= "Subject: $cont_subject\n";
            $email_content .= "Message:\n$message\n";

            // Build the email headers.
            $email_headers = "From: $name <$email>";


            $mailer = new Mailer();
            $mailer->setFrom($email);
            $mailer->setFrom_name($name);
            $mailer->setDistination($recipient);
            $mailer->setSubjet($subject);
            $mailer->setMessage($email_content);
            $mailer->setType("plain");

            // Send the email.
            if ($mailer->send()) {
                // Set a 200 (okay) response code.
                http_response_code(200);
                echo "Thank You! Your message has been sent.";
            } else {
                // Set a 500 (internal server error) response code.
                http_response_code(500);
                echo "Oops! Something went wrong and we couldn't send your message.";
            }

        } else {
            // Not a POST request, set a 403 (forbidden) response code.
            http_response_code(403);
            echo "There was a problem with your submission, please try again.";
        }

    }

    public function version(){

        echo "Current Version: <B>"._APP_VERSION."</B><br>";

        if(_APP_VERSION!=APP_VERSION){
            echo "Ready for: <B>".APP_VERSION."</B><br><br>";
            echo '<a href="'.base_url("update?id=".CRYPTO_KEY).'">Run the update</a>';
        }

    }

    public function recoverSettingsFile(){

        $this->mUpdateModel->prepareDemagedSettingFile();

    }



}

/* End of file CmsDB.php */