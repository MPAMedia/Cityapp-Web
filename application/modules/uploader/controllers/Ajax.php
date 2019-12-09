<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Ajax extends AJAX_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("uploader/uploader_model");
        $this->load->model("user/user_model");
        $this->load->model("user/user_browser");

    }

    public function clearByKey(){

        $key = $this->input->post('key');
        $list = $this->uploader->getUploaderSession('loaded-images'.$key);


        foreach ($list as $value){
            $this->delete($key,$value);
        }

        echo json_encode(array(Tags::SUCCESS=>1));
    }

    public function delete($key=NULL,$id=NULL){


        $errors = array();

        if($key==NULL)
            $key = $this->input->post('key');

        if($id==NULL)
            $id = $this->input->post('id');


        if(!preg_match('#^([a-zA-Z]+)$#i',$key)){
            $errors['error'] = Translate::sprint('Invalidate Upload Function');
        }

        if(!preg_match('#^([0-9]+)$#i',$id)){
            $errors['error'] = Translate::sprint('Invalidate image ID');
        }


        if(empty($errors)){

            $user_id = $this->user_browser->getData('user_id');

            //remove image from database
            $this->db->where('user_id',$user_id);
            $this->db->where('image',$id);
            $this->db->delete('image');

            //remove image folder
            @_removeDir($id);

            //less saved number
            $limit_saved = $this->uploader->getUploaderSession('saved-'.$key);

            $limit_saved--;
            if($limit_saved<0)
                $limit_saved = 0;

            $this->uploader->setUploaderSession('saved-'.$key,$limit_saved);

            //remove image from session
            $list = $this->uploader->getUploaderSession('loaded-images'.$key);
            if(isset($list[$id]))
                unset($list[$id]);
            $this->uploader->setUploaderSession('loaded-images'.$key,$list);

            $s = array(Tags::SUCCESS=>1);
            echo json_encode($s);return;
        }


        $s = array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
        echo json_encode($s);return;
    }

    public function uploadImage64($data64=NULL){

        $errors = array();


        $key = $this->input->post('key');
        if(!preg_match('#^([a-zA-Z]+)$#i',$key)){
            $errors['error'] = Translate::sprint('Invalidate Upload Function');
        }else{

            $limit = $this->uploader->getUploaderSession($key);
            $limit_saved = $this->uploader->getUploaderSession('saved-'.$key);

            if($limit==$limit_saved){
                $errors[] = Translate::sprint("You have exceeded the maximum number of files");
            }
        }

        $r = array();

        if(empty($errors)){

            $Upoader = new UploaderHelper($data64);

            $r = $Upoader->start64();

            if(empty($Upoader->getErrors())){

                $id = $r['image'];
                $type = $r['type'];

                $user_id = intval($this->user_browser->getData("id_user"));

                if($user_id==0){
                    $user_id = 1;
                }

                $this->db->insert('image',array(
                    "image"     =>  $id,
                    "type"      =>  $type,
                    "user_id"      =>  $user_id,
                ));

                $limit_saved++;
                $this->uploader->setUploaderSession('saved-'.$key,$limit_saved);

                $list = $this->uploader->getUploaderSession('loaded-images'.$key);
                $list[] = $id;

                $this->uploader->setUploaderSession('loaded-images'.$key,$list);

            }

            $er = $Upoader->getErrors();
            if(!empty($er))
                $errors = $er;
        }

        return $r;

    }

    public function uploadImage64_admin($data64=NULL){

        $errors = array();

        $r = array();

        if(empty($errors)){

            $Upoader = new UploaderHelper($data64);

            $r = $Upoader->start64();

            if(empty($Upoader->getErrors())){

                $id = $r['image'];
                $type = $r['type'];

                $user_id = intval($this->user_browser->getData("id_user"));

                if($user_id==0){
                    $user_id = 1;
                }

                $this->db->insert('image',array(
                    "image"     =>  $id,
                    "type"      =>  $type,
                    "user_id"      =>  $user_id,
                ));

            }

            $er = $Upoader->getErrors();
            if(!empty($er))
                $errors = $er;
        }

        return $r;

    }


    public function uploadImage(){

        $errors = array();

        $key = $this->input->post('key');
        if(!preg_match('#^([a-zA-Z]+)$#i',$key)){
            $errors['error'] = Translate::sprint('Invalidate Upload Function');
        }else{

            $limit = $this->uploader->getUploaderSession($key);
            $limit_saved = $this->uploader->getUploaderSession('saved-'.$key);

            if($limit==$limit_saved){
                $errors[] = Translate::sprint("You have exceeded the maximum number of files");
            }
        }

        $r = array();

        if(empty($errors)){

            $Upoader = new UploaderHelper($_FILES['addimage']);

            $r = $Upoader->start();

            if(empty($Upoader->getErrors())){

                $id = $r['image'];
                $type = $r['type'];

                $user_id = intval($this->user_browser->getData("id_user"));

                if($user_id==0){
                    $user_id = 1;
                }

                $this->db->insert('image',array(
                    "image"     =>  $id,
                    "type"      =>  $type,
                    "user_id"      =>  $user_id,
                ));

                $limit_saved++;
                $this->uploader->setUploaderSession('saved-'.$key,$limit_saved);

                $list = $this->uploader->getUploaderSession('loaded-images'.$key);
                $list[] = $id;

                $this->uploader->setUploaderSession('loaded-images'.$key,$list);

            }

            $er = $Upoader->getErrors();
            if(!empty($er))
                $errors = $er;
        }

        echo json_encode(array("errors"=>$errors,"results"=>$r));
        exit();

    }





    public function uploadURLs(){

        $urls = $this->input->post('URLs');

        if(is_string($urls))
            $urls = json_decode($urls,JSON_OBJECT_AS_ARRAY);

        $data = array();

        if(!empty($urls)){

            foreach ($urls as $url){

                $imageDATA = file_get_contents($url);
                $imageDATA64 = base64_encode($imageDATA);
                $result = $this->uploadImage64($imageDATA64);
                $data[] = $result;

            }

            if(!empty($data)){
                echo json_encode(array(Tags::SUCCESS=>1,Tags::RESULT=>$data));return;
            }

        }


        echo json_encode(array(Tags::SUCCESS=>0));return;

    }

    public function uploadURLs_admin(){

        $urls = $this->input->post('URLs');

        if(is_string($urls))
            $urls = json_decode($urls,JSON_OBJECT_AS_ARRAY);

        $data = array();

        if(!empty($urls)){

            foreach ($urls as $url){

                $imageDATA = file_get_contents($url);
                $imageDATA64 = base64_encode($imageDATA);
                $result = $this->uploadImage64_admin($imageDATA64);
                $data[] = $result;

            }

            if(!empty($data)){
                echo json_encode(array(Tags::SUCCESS=>1,Tags::RESULT=>$data));return;
            }

        }


        echo json_encode(array(Tags::SUCCESS=>0));return;

    }



}

/* End of file UploaderDB.php */