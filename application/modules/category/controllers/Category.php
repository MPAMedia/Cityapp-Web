<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Category extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model
        $this->load->model("category/category_model","mCategoryModel");

        /////// register module ///////
        $data = array(
            "version_code"  =>1,
            "version_name"  => "1.0.1 Beta",
            "order"     =>8
        );
        $this->appcore->register($this,$data);


    }

	public function index()
	{

	}

}

/* End of file CategoryDB.php */