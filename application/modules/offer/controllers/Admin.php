<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by Console.
 * User: Amine Maagoul
 * Date: {date}
 * Time: {time}
 */

class Admin extends MY_Controller {

    public function __construct(){
        parent::__construct();
        //load model

        $this->load->model("offer/offer_model","mOfferModel");
        $this->load->model("store/store_model","mStoreModel");
        $this->load->model("user/user_model","mUserModel");
        $this->load->model("user/user_browser","mUserBrowser");

        // hide the offer if the date
        if(ENABLE_AUTO_HIDDEN_OFFERS)
            $this->hiddenOfferOutOfDate();
    }

	public function index()
	{

	}

    public function edit(){

        if ($this->mUserBrowser->isLogged()) {

            $data = array();

            $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
                "user_id" => $this->mUserBrowser->getData("id_user")
            ));

            $data['offerToEdit'] = $this->getOffers(
                array(
                    "offer_id" => $this->input->get("id")
                )
            );



            if (isset($data['offerToEdit'][Tags::RESULT]) and count($data['offerToEdit'][Tags::RESULT]) == 1) {

                $this->load->view("backend/header", $data);
                $this->load->view("backend/html/edit");
                $this->load->view("backend/footer");
            }

        } else {
            redirect(admin_url("error404"));
        }




    }


    public function offers(){

        $uri = $this->uri->segment(4);

        if($this->mUserBrowser->isLogged()){


            $data = array();

            $params =array(
                "offer_id" => $this->input->get("offer_id"),
                "store_id" => $this->input->get("store_id"),
                "date_end" => $this->input->get("date_end"),
                "page" => $this->input->get("page"),
                "status" => $this->input->get("status")
            );

            $data['offers'] = $this->getOffers($params);

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/offers");
            $this->load->view("backend/footer");

        }else {
            redirect(admin_url("user/login"));
        }


    }

    public function add(){

        if($this->mUserBrowser->isLogged()) {

            $data["myStores"] = $this->mStoreModel->getMyAllStores(array(
                "user_id"   => $this->mUserBrowser->getData("id_user")
            ));

            $this->load->view("backend/header",$data);
            $this->load->view("backend/html/add");
            $this->load->view("backend/footer");

        }else {
            redirect(admin_url("user/login"));
        }

    }


    private function getOffers($params=array()){

        $vToExtract = array_key_whitelist($params, [
            'offer_id',
            'store_id',
            'page'
        ]);
        extract($vToExtract,EXTR_SKIP);

        $user_id =  intval($this->mUserBrowser->getData("id_user"));
        $userType =  ($this->mUserBrowser->getData("typeAuth"));


        $params = array(
            "offer_id"  => $offer_id,
            "store_id"  => $store_id,
           // "date_end"  => $date_end,
            "page"      => $page,
            "limit"     => NO_OF_ITEMS_PER_PAGE
        );




        $authType = $this->mUserBrowser->getData("typeAuth");
        $s = intval($this->input->get("status"));
        if($authType=="admin" and $s==1){
            $params['user_id'] = $this->mUserBrowser->getData("id_user");
        }

        if($authType!='admin')
            $params['user_id'] = $this->mUserBrowser->getData('id_user');


        if($userType=="manager")
            $params['user_id'] = $user_id;

        if($userType=="admin")
            $params['is_super'] = TRUE;



        return $this->mOfferModel->getOffers($params);

    }

    public function hiddenOfferOutOfDate()
    {
      $this->mOfferModel->hiddenOfferOutOfDate();
    }


}

/* End of file OfferDB.php */