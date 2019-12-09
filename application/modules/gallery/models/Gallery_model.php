<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Gallery_model extends CI_Model
{

    public $maxfiles = 20;

    public function __construct()
    {
        parent::__construct();
        $this->initTables();
    }


    public function loadHtml($tag,$galley=array(),$uid=0){
        $data["tag"] =  $tag;
        $data["images"] =  $galley;
        $data["uid"] =  $uid;
        $this->load->view("gallery/html",$data);
    }

    public function loadJs($tag,$galley=array(),$uid=0){
        $data["tag"] =  $tag;
        $data["images"] =  $galley;
        $data["uid"] =  $uid;
        $this->load->view("gallery/js",$data);
        return "fileUploaded_".$tag;
    }


    public function getGallery($params=array()){

        extract($params);

        $data = array();
        $errors = array();

        if(!isset($page))
            $page = 1;

        if($page==0)
            $page = 1;

        if(!isset($limit))
            $limit = 20;


        if(!isset($type)){
            $errors[] = Translate::sprint("The type is missing");
        }else if($type!="store" AND $type!="offer" AND $type!="event"){
            $errors[] = Translate::sprint("The type is invalid!");
        }

        if(!isset($int_id)){
            $errors[] = Translate::sprint("The ID is not valid!");
        }


        if(!empty($errors)){
            return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
        }


        $this->db->where("type",$type);
        $this->db->where("int_id",$int_id);

        $count = $this->db->count_all_results("gallery");

        $pagination = new Pagination();
        $pagination->setCount($count);
        $pagination->setCurrent_page($page);
        $pagination->setPer_page($limit);
        $pagination->calcul();


        $this->db->where("type",$type);
        $this->db->where("int_id",$int_id);

        $this->db->from("gallery");
        $this->db->limit($pagination->getPer_page(),$pagination->getFirst_nbr());
        $gallery = $this->db->get();
        $gallery = $gallery->result_array();



        $new_gallery_results = array();
        foreach ($gallery as $key => $image){

            $img_id = $image["image_id"];

            $this->db->select("image");
            $this->db->where("id_image",$img_id);
            $img = $this->db->get("image",1);
            $img = $img->result();


            if(count($img)>0){
                $new_gallery_results[] = _openDir($img[0]->image);
            }else{
                $count--;
            }

        }


        return array(Tags::SUCCESS=>1,"pagination"=>$pagination,  Tags::COUNT=>$count,  Tags::RESULT=>$new_gallery_results);

    }

    public function saveGallery($type,$int_id,$images=array()){

        $errors = array();

        if($type=="store" OR $type=="offer" OR $type=="event"){

            $this->db->where("int_id",intval($int_id));
            $this->db->where("type",$type);
            $nbrfiles = $this->db->count_all_results("gallery");

            if($nbrfiles>=$this->maxfiles){

                $errors[] = Translate::sprintf("You have exceeded the maximum number of images (Max: %s)",array($this->maxfiles));

            }else if($int_id>0){


                $this->db->where("id_".$type,intval($int_id));
                $c = $this->db->count_all_results($type);

                if($c>0){

                    if(!empty($images)){

                        $object = array(
                            "int_id"    => $int_id,
                            "type"      => $type,
                        );

                        $ids = array();
                        foreach ($images as $value){

                            if($nbrfiles>=$this->maxfiles){
                                break;
                            }

                            //get image object
                            $this->db->select("id_image");
                            $this->db->where("image",$value);
                            $img = $this->db->get("image",1);
                            $img = $img->result();

                            if(count($img)==0){
                                continue;
                            }

                            $this->db->where("image_id",$img[0]->id_image);
                            $this->db->where("type",$type);
                            $this->db->where("int_id",intval($int_id));
                            $i = $this->db->count_all_results("gallery");

                            if($i==0){
                                $object['image_id'] = intval($img[0]->id_image);
                                $object['updated_at'] = date("Y-m-d H:i:s",time());
                                $object['created_at'] = date("Y-m-d H:i:s",time());
                                $this->db->insert("gallery",$object);
                                $nbrfiles++;
                            }

                            $ids[] = intval($img[0]->id_image);

                        }

                        if(!empty($ids)){
                            $this->db->where_not_in('image_id', $ids);
                            $this->db->where('type', $type);
                            $this->db->where('int_id', $int_id);
                            $this->db->delete("gallery");
                        }else{
                            $this->db->where('type', $type);
                            $this->db->where('int_id', $int_id);
                            $this->db->delete("gallery");
                        }


                        return array(Tags::SUCCESS=>1);

                    }else{
                        $errors[] = Translate::sprint("You should select some images");
                    }

                }else{
                    $errors[] = Translate::sprintf("The %s is not exists!",array($type));
                }

            }else{
                $errors[] = Translate::sprint("The ID is not valid!");
            }

        }



        return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors);
    }

    public function initTables(){

        // create new table gallery
        $this->load->dbforge();
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'int_id' => array(
                'type' => 'INT',
                'default' => NULL
            ),
            'type' => array(
                'type' => 'VARCHAR(30)',
                'default' => NULL
            ),
            'image_id' => array(
                'type' => 'INT',
                'default' => NULL
            ),
            'updated_at' => array(
                'type' => 'DATETIME'
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            ),
        ));

        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('gallery', TRUE, $attributes);

    }


}