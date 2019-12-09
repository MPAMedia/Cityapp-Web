<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */
class Ajax extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("place/place_model", "mPlaceModel");

    }

    public function addPlace()
    {

        //check if user have permission
        $this->checkDemoMode();

        $name = trim($this->input->post("name"));
        $name_ar = trim($this->input->post("name_ar"));
        $latitude = trim($this->input->post("latitude"));
        $longitude = trim($this->input->post("longitude"));
                $image =  $this->input->post("image");


        echo json_encode($this->mPlaceModel->addPlace(array(
            "name" => $name,
            "name_ar"=>$name_ar,
             "image" => $image,
            "latitude"=>$latitude,
            "longitude"=>$longitude,
            
        )));
        return;

    }


    public function delete()
    {

        //check if user have permission
        $this->checkDemoMode();

        $id = intval($this->input->post("id"));

        echo json_encode($this->mPlaceModel->delete(
            $id
        ));
    }


    public function editPlace()
    {

        //check if user have permission
        $this->checkDemoMode();

        $name = trim($this->input->post("name"));
        $name_ar = trim($this->input->post("name_ar"));
        $latitude = trim($this->input->post("latitude"));
        $longitude = trim($this->input->post("longitude"));
                $image =  $this->input->post("image");


        $place_id = intval(trim($this->input->post("place_id")));


        echo json_encode($this->mPlaceModel->editPlace(
            array(
                "name" => $name,
                "name_ar" => $name_ar,
                "place_id" => $place_id,
                 "image" => $image,
                "latitude"=>$latitude,
                "longitude"=>$longitude,
                
            )
        ));
    }


}

/* End of file CategoryDB.php */