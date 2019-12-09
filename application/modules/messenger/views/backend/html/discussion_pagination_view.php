<div class="pull-right">

    <?php



        if(!isset($username) and Text::checkUsernameValidate(  $this->input->get("username")   )){
            $username = $this->input->get("username");
        }

        echo $pagination->links(array(
            "username"    => $username,
        ),admin_url("messenger/messages"));


    ?>

</div>




