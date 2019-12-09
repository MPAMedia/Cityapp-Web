<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends CI_Model {




    // (iOS) Private key's passphrase.
    private static $passphrase = 'joashp';
    // (Windows Phone 8) The name of our push channel.
    private static $channelName = "xxx";

    // Change the above three vriables as per your app.
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param string $platform
     * @param array $params
     */
    public function send_notification($platform="android", $params=array()) {
        // include config

        $errors = array();
        extract($params);



        if(!isset($regIds) OR $regIds==""){
            $errors['registerId'] = Translate::sprint(Messages::REGISTER_ID_ERRORS);
        }

        if ($platform=="android"){

            if (isset($body) and isset($regIds)){
                $result = $this->android($body,$regIds);
                return $result;
            }

        }else if ($platform=="ios"){

            if (isset($body) and isset($regIds)){
                $result = $this->fbSend($platform,$body,$regIds);
                return $result;
            }

        }else{

            if (isset($body) and isset($regIds)){
                $result = $this->android($body,$regIds);
                return $result;
            }

        }

        return array();
    }


    // Sends Push notification for Android users
    public function android($data, $reg_id) {

        $url = 'https://fcm.googleapis.com/fcm/send';


        $headers = array(
            'Authorization: key=' .FCM_KEY,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => array($reg_id),
            'data' => $data,
        );

        $result = $this->useCurl($url, $headers, json_encode($fields));
        return $result;
    }

    // Sends Push notification for Android users
    public function fbSend($platform="android",$data, $reg_id) {

        $url = 'https://fcm.googleapis.com/fcm/send';


        $headers = array(
            'Authorization: key=' .FCM_KEY,
            'Content-Type: application/json'
        );

        if ($platform == 'android') {

            $fields = array(
                "to" => $reg_id,
                "data" => $data,
                "priority" => 10,
                "content_available" => true,
            );

        }else if ($platform == 'ios') {


            //use this body when the app running on forground
            $body_foreground = array(
                'title' => APP_NAME,
                'text' => $data,
                'sound' => '',
                'badge' => '0',
                "alert" => ""
            );

            //use this body when the app running in background
            $body_background = array(
                'title' => APP_NAME ,
                'text' => Translate::sprint("You have new notification"),
                'sound' => '',
                'badge' => 1,
                "alert" => "default",
            );

            //This array contains, the token and the notification. The 'to' attribute stores the token.
            //$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');


            $fields = array(
                "to" => $reg_id,
                "notification" => $body_background,
                "data" => $body_foreground,
                "content_available" => true,
                "priority" => "high",
            );

        }else{

            $body = array(
                'title' =>"hi every one! using any other platform" ,
                'text' => $data,
                'sound' => 'default',
                'badge' => '1'
            );

            $fields = array(
                "to" => $reg_id,
                "data" => $body,
                //"priority" => 10
            );

        }


        $result = $this->useCurl($url, $headers, json_encode($fields));
        return $result;

        /*
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = "";
        $serverKey = '';
        $title = "Title";
        $body = "Body of the message";
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        */
    }

    // Sends Push's toast notification for Windows Phone 8 users
    public function WP($data, $uri) {
        $delay = 2;
        $msg =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<wp:Notification xmlns:wp=\"WPNotification\">" .
            "<wp:Toast>" .
            "<wp:Text1>".htmlspecialchars($data['mtitle'])."</wp:Text1>" .
            "<wp:Text2>".htmlspecialchars($data['mdesc'])."</wp:Text2>" .
            "</wp:Toast>" .
            "</wp:Notification>";

        $sendedheaders =  array(
            'Content-Type: text/xml',
            'Accept: application/*',
            'X-WindowsPhone-Target: toast',
            "X-NotificationClass: $delay"
        );

        $response = $this->useCurl($uri, $sendedheaders, $msg);

        $result = array();
        foreach(explode("\n", $response) as $line) {
            $tab = explode(":", $line, 2);
            if (count($tab) == 2)
                $result[$tab[0]] = trim($tab[1]);
        }

        return $result;
    }

    // Sends Push notification for iOS users
    public function iOS($data, $devicetoken) {
        $deviceToken = $devicetoken;
        $ctx = stream_context_create();
        // ck.pem is your certificate file
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => "HiHi",
                'body' => $data,
            ),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        if (!$result)
            return 'Message not delivered' . PHP_EOL;
        else
            return 'Message successfully delivered' . PHP_EOL;
    }

    // Curl
    private function useCurl($url, $headers, $fields = null) {
        // Open connection
        $ch = curl_init();
        if ($url) {

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($fields) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);

            return $result;
        }
    }






    
  
}

