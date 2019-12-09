<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Droideve Technology
 * Date: {date}
 * Time: {time}
 */

class Admin extends ADMIN_Controller {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

        $this->load->helper('url');

        // hide the offer if the date
        if(ENABLE_AUTO_HIDDEN_OFFERS)
            $this->hiddenOfferOutOfDate();
    }

	public function index()
	{

	}

    public function edit(){

        if (!GroupAccess::isGranted('offer',EDIT_OFFER))
            redirect("error?page=permission");

        $data = array();

        $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
            "user_id" => $this->mUserBrowser->getData("id_user")
        ));

        $data['offer'] = $this->getOffers(
            array(
                "offer_id" => $this->input->get("id")
            ),TRUE
        );

        if (isset($data['offer'][Tags::RESULT]) and count($data['offer'][Tags::RESULT]) == 1) {
            $this->load->view("backend/header", $data);
            $this->load->view("backend/html/edit");
            $this->load->view("backend/footer");
        }


    }

    public function all_offers(){


        if (!GroupAccess::isGranted('user',USER_ADMIN)  )
            redirect("error?page=permission");


        $data = array();

        $params =array(
            "offer_id" => $this->input->get("offer_id"),
            "store_id" => $this->input->get("store_id"),
            "date_end" => $this->input->get("date_end"),
            "page" => $this->input->get("page"),
            "search" => $this->input->get("search"),
            "limit"     => NO_OF_ITEMS_PER_PAGE,
            "is_super"     => TRUE,
            "status"     => $this->input->get("status"), // filter offer by status
            "filterBy"     => $this->input->get("filterBy"),
        );

        $data['offers'] = $this->mOfferModel->getOffers($params);

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/offers");
        $this->load->view("backend/footer");


    }

    public function offers(){


        if (!GroupAccess::isGranted('offer'))
            redirect("error?page=permission");


        $data = array();

        $params =array(
            "offer_id" => $this->input->get("offer_id"),
            "store_id" => $this->input->get("store_id"),
            "date_end" => $this->input->get("date_end"),
            "page" => $this->input->get("page"),
            "search" => $this->input->get("search"),
            "status" => $this->input->get("status"), // filter by status
            "filterBy"  => $this->input->get("filterBy"),
        );
        //print_r($params); die();

        $data['offers'] = $this->getOffers($params);

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/offers");
        $this->load->view("backend/footer");


    }

    public function add(){

        if (!GroupAccess::isGranted('offer',ADD_OFFER))
            redirect("error?page=permission");

        $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
            "user_id"   => $this->mUserBrowser->getData("id_user")
        ));

        $this->load->view("backend/header",$data);
        $this->load->view("backend/html/add");
        $this->load->view("backend/footer");

    }


    private function getOffers($params=array(),$editable=FALSE){



        $vToExtract = array_key_whitelist($params, [
            'offer_id',
            'store_id',
            'page',
            'search',
            'status',
            'filterBy'
        ]);
        extract($vToExtract,EXTR_SKIP);

        $user_id =  intval($this->mUserBrowser->getData("id_user"));

        $params = array(
            "offer_id"  => $offer_id,
            "store_id"  => $store_id,
            "status"=> $status,
            "filterBy"=> $filterBy,
            "page"      => $page,
            "search"      => $search,
            "limit"     => NO_OF_ITEMS_PER_PAGE
        );



        if(!$editable){
            $s = intval($this->input->get("status"));
            if(GroupAccess::isGranted('user',USER_ADMIN and $s==1)){
                $params['user_id'] = $this->mUserBrowser->getData("id_user");
            }else  if($user_id >0)  $params['user_id'] = $user_id;
        }

        if(GroupAccess::isGranted('user',USER_ADMIN)) $params['is_super'] = TRUE;


        return $this->mOfferModel->getOffers($params);

    }

    public function hiddenOfferOutOfDate()
    {
      $this->mOfferModel->hiddenOfferOutOfDate();
    }


    public function verify()
    {

        $status = $this->input->get('status');


        if ($this->mUserBrowser->isLogged()) {

            if (!GroupAccess::isGranted('offer',VALIDATE_OFFERS))
                redirect("error?page=permission");


            $id = intval($this->input->get('id'));
            $accept = intval($this->input->get('accept'));

            $this->db->where('id_offer',$id);
            $this->db->update('offer',array(
                'verified' => 1,
                'status'   => $accept,
            ));


        }
        ($status == 1) ? redirect(admin_url("offer/offers")) : redirect(admin_url("offer/all_offers"));
    }


}

/* End of file OfferDB.php */