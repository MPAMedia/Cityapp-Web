<?php



        class Mailer{
            //from, from_name, replay, replay_name, sujet, text(plain|html), message
            protected $distination;
            protected $from;
            protected $from_name;
            protected $replay_to;
            protected $replay_to_name;
            protected $subjet;
            protected $type;
            protected $message;
            
            public function getDistination() {
                return $this->distination;
            }

            public function setDistination($email) {
                $this->distination = $email;
            }

                        
            public function __construct() {
                
                $this->from = "";
                $this->from_name = "";
                $this->replay_to = "";
                $this->replay_to_name ="";
                $this->subjet = "";
                $this->type = "";
                $this->message = "";
                
            }
            
            public function getFrom() {
                return $this->from;
            }

            public function getFrom_name() {
                return $this->from_name;
            }

            public function getReplay_to() {
                return $this->replay_to;
            }

            public function getReplay_to_name() {
                return $this->replay_to_name;
            }

            public function getSubjet() {
                return $this->subjet;
            }

            public function getType() {
                return $this->type;
            }

            public function getMessage() {
                return $this->message;
            }

            public function setFrom($from) {
                $this->from = $from;
            }

            public function setFrom_name($from_name) {
                $this->from_name = $from_name;
            }

            public function setReplay_to($replay_to) {
                $this->replay_to = $replay_to;
            }

            public function setReplay_to_name($replay_to_name) {
                $this->replay_to_name = $replay_to_name;
            }

            public function setSubjet($subjet) {
                $this->subjet = $subjet;
            }

            public function setType($type) {
                $this->type = $type;
            }

            public function setMessage($message) {
                $this->message = $message;
            }

            public function send(){


                if($this->distination!=""){

			if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#i", $this->distination))
				$passage_ligne = "\r\n";
			else
				$passage_ligne = "\n";

			$mail = $this->distination;
			$from = $this->from;//Nom <>
			$name = $this->from_name;
			$replay = $this->replay_to;
			$replay_name = $this->replay_to_name;

			//$boundary = "-----=".md5(rand());

			//=====Définition du sujet.
			$sujet = $this->subjet;
			//=========
			 
			//=====Création du header de l'e-mail.
			//$header = "From: \"".$name."\"<".$from.">".$passage_ligne;
			//$header .= 'Return-Path: <admin@wwww.com>'.$passage_ligne;
			//$header.= "Reply-to: \"".$replay_name."\" <".$replay.">".$passage_ligne;
			
			//$header .='X-Mailer: PHP/' . phpversion().$passage_ligne;
			//$header.= "MIME-Version: 1.0".$passage_ligne;
			//$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//$header .= 'Content-Type: multipart/mixed;boundary='.$boundary.$passage_ligne;
			//==========

		    $message = $this->message;
			
			
			//==========
			//$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========

            $ci = get_instance();
            $ci->load->config('config');
            $ex_smtp = $ci->config->item('email_smtp_enabled');

                    if($ex_smtp==TRUE){

                        $config = array();
                        $config['protocol']   = $ci->config->item('email_protocol');
                        $config['smtp_host']  = $ci->config->item('email_smtp_host');
                        $config['smtp_port']  = $ci->config->item('email_smtp_port');
                        $config['smtp_user']  = $ci->config->item('email_smtp_user');
                        $config['smtp_pass']  = $ci->config->item('email_smtp_pass');
                        $config['mailtype']   = $ci->config->item('email_mailtype');
                        $config['charset']    = $ci->config->item('email_charset');

                        $ci->email->initialize($config);
                    }


                    $ci->email->set_mailtype("html");
                    $ci->email->set_newline("\r\n");


                    $ci->email->to($mail);
                    $ci->email->from($from,$this->from_name);
                    $ci->email->subject($sujet);
                    $ci->email->message($message);
			
			//=====Envoi de l'e-mail.
			if($ci->email->send()){
                return TRUE;
             }else {
                return FALSE;
             }

		}else
		    return FALSE;
        }
            
            
            
            public static function templateParser($data=array(),$file=''){

                    $url = base_url("mailing/templates/".$file.".html");   
                    $content = "";
                    try{


                        $content = file_get_contents($url);
                        if(!empty($data) AND $content!=''){

                            foreach ($data AS $key => $value){


                                if(filter_var($value,FILTER_VALIDATE_EMAIL)){
                                    $content = preg_replace("#\{".$key."\}#","<a href='$value'>".$value."</a>" , $content);
                                }else{
                                    $content = preg_replace("#\{".$key."\}#",$value , $content);
                                }
                            }
                        }

                    } catch (Exception $ex) {

                    }
                    return $content;   
                }
            
            
            
            
        }
        
        
        
        
        
        
        
        
        

