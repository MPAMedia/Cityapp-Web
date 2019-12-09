<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Hasan Ali
 * Date: {date}
 * Time: {time}
 */

class Place extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("place/Place_model","mPlaceModel");

        /////// register module ///////
        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>10
        );
        $this->appcore->register($this,$data);


    }

	public function index()
	{

	}

}

/* End of file PlaceDB.php */