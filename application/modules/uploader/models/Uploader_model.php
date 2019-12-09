<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Uploader_model extends CI_Model
{


    public function delete($dirID){

        $this->db->where("image",$dirID);
        $this->db->delete("image");

        @_removeDir($dirID);

        return TRUE;
    }

    public function uploadImage64($files=NULL){



        if($files!=NULL and isset($files) AND is_string($files)){

            //$_FILES['image']['type']="image/jpg";
            $Upoader = new UploaderHelper($files);

            $r = $Upoader->start64();
            //echo json_encode(array("errors"=>$Upoader->getErrors(),"results"=>$r));

            $errors = array();
            $errors = $Upoader->getErrors();

            if(empty($errors)){

                if(isset($r['image']) AND $r['image']!=""){

                    $imageData = array("type"=>$r['type'],"image"=>$r['image']);
                    $this->db->insert("image",$imageData);
                    $id = $this->db->insert_id();

                    if(isset($imageData['image'])){
                        $imageData['images'] = _openDir($imageData['image']);
                    }


                    return array(Tags::SUCCESS=>1,Tags::RESULT=>$imageData,"image_id"=>$id);

                }else{
                    return array(Tags::SUCCESS=>0,  Tags::ERRORS=>array("add"=>Translate::sprint("Error")));
                }

            }else{


                return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors,"results"=>$r);
            }


        }else{

            return array(Tags::SUCCESS=>0,Tags::ERRORS=>array("select"=>Translate::sprint("Please Select image")));

        }
    }


    public function uploadImage($files=NULL){



        if($files!=NULL and isset($files)){

            //$_FILES['image']['type']="image/jpg";
            $Upoader = new UploaderHelper($files);

            $r = $Upoader->start();
            //echo json_encode(array("errors"=>$Upoader->getErrors(),"results"=>$r));



            $errors = array();
            $errors = $Upoader->getErrors();

            if(empty($errors)){

                if(isset($r['image']) AND $r['image']!=""){

                    $imageData = array("type"=>$r['type'],"image"=>$r['image']);
                    $this->db->insert("image",$imageData);
                    $id = $this->db->insert_id();

                    if(isset($imageData['image'])){
                        $imageData['images'] = _openDir($imageData['image']);
                    }



                    return array(Tags::SUCCESS=>1,"data"=>$imageData,"image_id"=>$id);

                }else{
                    return array(Tags::SUCCESS=>0,  Tags::ERRORS=>array("add"=>"Erreur dans l'ajout votre image de votre marque"));
                }

            }else{


                return array(Tags::SUCCESS=>0,Tags::ERRORS=>$errors,"results"=>$r);
            }


        }else{

            return array(Tags::SUCCESS=>0,Tags::ERRORS=>array("select"=>Translate::sprint("Please Select image")));

        }
    }


}