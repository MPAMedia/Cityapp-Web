<?php


$typeAuth = $this->mUserBrowser->getData("typeAuth");


if (isset($dataUser[Tags::RESULT][0])) {

    $user = $dataUser[Tags::RESULT][0];

    $package = $user->package;

    if (!is_array($package))
        $package = json_decode($user->package, JSON_OBJECT_AS_ARRAY);

} else {


    $user_id = $this->mUserBrowser->getData("id_user");

    $this->db->select("user.*,setting.*");
    $this->db->from("user");
    $this->db->join("setting", "setting.user_id=user.id_user", "INNER");
    $this->db->where("id_user", $user_id);
    $user = $this->db->get();
    $user = $user->result();


    $user = $user[0];

    $package = $user->package;

    if (!is_array($package))
        $package = json_decode($user->package, JSON_OBJECT_AS_ARRAY);


}

$itsMe = "";
if ($this->mUserBrowser->getData("id_user") == $user->id_user) {
    $itsMe = "disabled=\"$itsMe\"";
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content profle">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="row">


                    <div class="col-md-6">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><strong><?= Translate::sprint("Profile") ?></strong></h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="col-sm-12">

                                    <form id="form1">
                                        <input type="hidden" name="id" id="id" value="<?= $user->id_user ?>">
                                        <!-- text input -->

                                        <?php

                                        $images = $user->images;


                                        if (!is_array($images)) {
                                            // $images = json_encode($images,JSON_FORCE_OBJECT);

                                            $images = json_decode($images, JSON_OBJECT_AS_ARRAY);
                                            // print_r($images);

                                        }

                                        $dc = $images;
                                        if (!is_array($images) and $dc != "") {
                                            $images = array();
                                            $images[] = $dc;
                                        }


                                        ?>

                                        <div class="form-group required">
                                            <label for="name"><?= Translate::sprint("Photo") ?><sup>*</sup> <span>100x100</span>
                                            </label>

                                            <label class="msg-error-form image-data"></label>
                                            <!--                    <div id="addimage"><i class="fa fa-camera fa-3"></i>


                                                                </div>-->

                                            <input type="file" name="addimage" id="fileupload"><br>

                                            <div class="clear"></div>


                                            <div id="progress" class="hidden">
                                                <div class="percent" style="width: 0%"></div>
                                            </div>


                                            <div class="clear"></div>


                                            <div id="image-previews">


                                                <?php if (!empty($images)) { ?>

                                                    <?php foreach ($images as $value) { ?>

                                                        <?php

                                                        $item = "item_" . $value;
                                                        $data = $value;

                                                        $imagesData = _openDir($value);


                                                        ?>


                                                        <div class="image-uploaded <?= $item ?>">
                                                            <a id="image-preview">
                                                                <img src="<?= $imagesData['200_200']['url'] ?>" alt="">
                                                            </a>

                                                            <div class="clear"></div>
                                                            <a href="#" data="<?= $data ?>" id="delete"><i
                                                                    class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a></div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>


                                        </div>


                                        <div class="row">


                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?= Translate::sprint("Full name") ?> :</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..."
                                                           name="name" id="name" value="<?= $user->name ?>">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?= Translate::sprint("Email") ?> :</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..."
                                                           value="<?= $user->email ?>" disabled>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label><?= Translate::sprint("Username") ?> :</label>
                                            <input type="text" class="form-control" placeholder="Enter ..."
                                                   name="username" id="username" value="<?= $user->username ?>"
                                                   disabled>
                                        </div>

                                        <div class="form-group">
                                            <label><?= Translate::sprint("New password") ?> :</label>
                                            <input type="password" class="form-control" placeholder="Enter ..."
                                                   name="password" id="password">
                                        </div>
                                        <div class="form-group">
                                            <label> <?= Translate::sprint("Confirm Password") ?> :</label>
                                            <input type="password" class="form-control" placeholder="Enter ..."
                                                   name="confirm" id="confirm">
                                        </div>


                                        <div class="form-group pull-left">

                                            <?php
                                            if ($user->confirmed == 0 && $typeAuth == "admin") {
                                                echo '<a href="' . site_url("ajax/user/confirm?id=" . $user->id_user) . '"  class="linkAccess" onclick="return false;"><button type="button" title="Enabled" class="btn  btn-primary" onclick="return false;"><i class="mdi mdi-account-check" aria-hidden="true"></i> ' . Translate::sprint("Confirm") . '</button></a>';
                                            }
                                            ?>

                                            <button type="button" class="btn  btn-primary" id="btnSave"><span
                                                    class="glyphicon glyphicon-check"></span> <?= Translate::sprint("Save") ?>
                                            </button>

                                        </div>


                                </div>
                            </div>

                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>

                    <div class="col-md-6">


                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><strong><?= Translate::sprint("User configuration") ?></strong>
                                </h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <form id="form2">


                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label><?= Translate::sprint("Access Role") ?></label>

                                            <?php if ($this->mUserBrowser->getData("typeAuth") == "admin"): ?>
                                                <select id="typeAuth" name="typeAuth"
                                                        class="form-control select2" <?= $itsMe ?>>

                                                    <?php
                                                    $a = "";
                                                    $m = "";
                                                    $c = "";

                                                    if ($user->typeAuth == "manager")
                                                        $m = "selected";
                                                    else if ($user->typeAuth == "admin")
                                                        $a = "selected";
                                                    else if ($user->typeAuth == "customer")
                                                        $c = "selected";

                                                    ?>
                                                    <option value="0">-- <?= Translate::sprint("Select") ?></option>
                                                    <option
                                                        value="customer" <?= $c ?>><?= Translate::sprint("Customer") ?></option>
                                                    <option
                                                        value="admin" <?= $a ?>><?= Translate::sprint("Admin") ?></option>
                                                    <option
                                                        value="manager" <?= $m ?>><?= Translate::sprint("Owner") ?></option>

                                                </select>
                                            <?php else:


                                                if ($user->typeAuth == "manager") {
                                                    echo '<br><span class="badge bg-blue">' . Translate::sprint("Owner", "") . '</span>';
                                                } else if ($user->typeAuth == "admin") {
                                                    echo '<br><span class="badge bg-green">' . Translate::sprint("Admin", "") . '</span>';
                                                } else if ($user->typeAuth == "customer") {
                                                    echo '<br><span class="badge bg-yellow">' . Translate::sprint("Customer", "") . '</span>';
                                                }

                                            endif;
                                            ?>
                                        </div>


                                        <?php if ($typeAuth=="manager" && $itsMe!=""): ?>
                                            <div class="form-group">
                                                <?php if ($itsMe == ""): ?>
                                                    <label><?= Translate::sprint("Number of stores") ?></label>
                                                    <input type="number" class="form-control" placeholder="10"
                                                           name="nbr_stores" id="nbr_stores"
                                                           value="<?= $package['nbr_stores'] ?>" <?= $itsMe ?>>
                                                <?php else: ?>
                                                    <div class="progress-group">

                                                        <?php

                                                        echo '<span class="progress-text">' . Translate::sprint("Number of stores") . '</span>';
                                                        if ($package['nbr_stores'] == -1) {

                                                            echo '<span class="progress-number">∞</span>';
                                                            echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';

                                                        } else {

                                                            if($user->nbr_stores>0)
                                                                $t = $user->nbr_stores / $package['nbr_stores'];
                                                            else
                                                                $t = 0;

                                                            $progress = ($t * 100);
                                                            $color = "aqua";
                                                            if ($progress >= 100)
                                                                $color = "aqua";
                                                            else if ($progress > 50 && $progress < 100)
                                                                $color = "yellow";
                                                            else
                                                                $color = "red";

                                                            echo '<span class="progress-number"><b>' . $user->nbr_stores . '</b>' . '/' . $package['nbr_stores'] . '</span>';
                                                            echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                        }

                                                        ?>


                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">

                                                <?php if ($itsMe == ""): ?>
                                                    <label> <?= Translate::sprint("Number of events monthly") ?></label>
                                                    <input type="number" class="form-control" placeholder="10"
                                                           name="nbr_events_monthly" id="nbr_events_monthly"
                                                           value="<?= $package['nbr_events_monthly'] ?>" <?= $itsMe ?>>
                                                <?php else: ?>
                                                    <div class="progress-group">

                                                        <?php

                                                        echo '<span class="progress-text">' . Translate::sprint("Number of events monthly") . '</span>';

                                                        if ($package['nbr_events_monthly'] == -1) {

                                                            echo '<span class="progress-number">∞</span>';
                                                            echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                        } else {



                                                            if($user->nbr_events_monthly>0)
                                                                $t = $user->nbr_events_monthly / $package['nbr_events_monthly'];
                                                            else
                                                                $t = 0;

                                                            $progress = ($t * 100);
                                                            $color = "aqua";

                                                            if ($progress >= 100)
                                                                $color = "aqua";
                                                            else if ($progress > 50 && $progress < 100)
                                                                $color = "yellow";
                                                            else
                                                                $color = "red";

                                                            echo '<span class="progress-number"><b>' . $user->nbr_events_monthly . '</b>' . '/' . $package['nbr_events_monthly'] . '</span>';
                                                            echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                        }

                                                        ?>


                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <?php if ($itsMe == ""): ?>
                                                    <label><?= Translate::sprint("Campaigns monthly") ?></label>
                                                    <input type="number" class="form-control" placeholder="10"
                                                           name="nbr_campaign_monthly" id="nbr_campaign_monthly"
                                                           value="<?= $package['nbr_campaign_monthly'] ?>" <?= $itsMe ?>>
                                                <?php else: ?>
                                                    <div class="progress-group">

                                                        <?php

                                                        echo '<span class="progress-text">' . Translate::sprint("Campaigns monthly") . '</span>';
                                                        if ($package['nbr_campaign_monthly'] == -1) {


                                                            echo '<span class="progress-number">∞</span>';
                                                            echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                        } else {

                                                            if($user->nbr_campaign_monthly>0)
                                                                $t = $user->nbr_campaign_monthly / $package['nbr_campaign_monthly'];
                                                            else
                                                                $t = 0;

                                                            $progress = ($t * 100);
                                                            $color = "aqua";

                                                            if ($progress >= 100)
                                                                $color = "aqua";
                                                            else if ($progress > 50 && $progress < 100)
                                                                $color = "yellow";
                                                            else
                                                                $color = "red";

                                                            echo '<span class="progress-number"><b>' . $user->nbr_campaign_monthly . '</b>' . '/' . $package['nbr_campaign_monthly'] . '</span>';
                                                            echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                        }

                                                        ?>


                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <?php if ($itsMe == ""): ?>
                                                    <label><?= Translate::sprint("Number of offers monthly") ?></label>
                                                    <input type="number" class="form-control" placeholder="10"
                                                           name="nbr_offer_monthly" id="nbr_offer_monthly"
                                                           value="<?= $package['nbr_offer_monthly'] ?>" <?= $itsMe ?>>
                                                <?php else: ?>
                                                    <div class="progress-group">

                                                        <?php

                                                        echo '<span class="progress-text">' . Translate::sprint("Number of offers monthly") . '</span>';
                                                        if ($package['nbr_offer_monthly'] == -1) {


                                                            echo '<span class="progress-number">∞</span>';
                                                            echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                        } else {


                                                            if($user->nbr_offer_monthly>0)
                                                                $t = $user->nbr_offer_monthly / $package['nbr_offer_monthly'];
                                                            else
                                                                $t = 0;

                                                            $progress = ($t * 100);
                                                            $color = "aqua";

                                                            if ($progress >= 100)
                                                                $color = "aqua";
                                                            else if ($progress > 50 && $progress < 100)
                                                                $color = "yellow";
                                                            else
                                                                $color = "red";

                                                            echo '<span class="progress-number"><b>' . ($user->nbr_offer_monthly) . '</b>' . '/' . $package['nbr_offer_monthly'] . '</span>';
                                                            echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                        }

                                                        ?>


                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>


                                    </div>


                            </div>
                            </form>
                        </div>
                        <!-- /.box-body -->

                        <?php if (ModulesChecker::isRegistred("pack") && $user->typeAuth == "manager"): ?>
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><strong><?= Translate::sprint("Pack") ?></strong></h3>
                                </div>

                                <div class="box-body">

                                    <div class="form-group margin">

                                        <?php if ($this->mUserBrowser->getData("typeAuth") == "manager"): ?>


                                            <?php

                                            $this->load->model("pack/pack_model");
                                            $pack = $this->pack_model->getAccountPack();


                                            if ($pack != NULL) {

                                                echo  '<label>'.Translate::sprint("Pack name").'</label>';
                                                echo "<br><span class='badge bg-yellow'>" . $pack->name . "</span>";

                                                if ($this->mPack->canUpgrade() && !$this->mPack->isRenewal()) {
                                                    echo "&nbsp;&nbsp;-&nbsp;&nbsp;";
                                                    echo "<u><a href='" . site_url("pack/pickpack?req=upgrade") . "'>" . Translate::sprint("Upgrade") . "</a></u>";
                                                }

                                                echo '<br>';
                                                if (!$this->mPack->isRenewal()) {
                                                    echo Translate::sprintf("Will be expired at ( %s )", array(
                                                        MyDateUtils::convert($user->will_expired, "UTC", TIME_ZONE, "d, M Y H:i:s")
                                                    ));
                                                } else {
                                                    echo "<strong class='text-red'>" . Translate::sprint("Your account was expired") . "</strong>&nbsp;&nbsp;&nbsp;";
                                                    echo "<strong class='text-red'><u><a href='" . admin_url("pack/renew") . "'>" . Translate::sprint("Renew now") . "</a></u></strong>";
                                                }

                                            }else{

                                                echo '<label><i class="text-orange mdi mdi-alert"></i>&nbsp;&nbsp;'.Translate::sprint("Don't have pack").'</label>';
                                                echo '<br><a href="'.site_url("pack/pickpack").'"><u>'.Translate::sprint("Select a pack").'</u></a>';

                                            }


                                            ?>


                                        <?php elseif ($this->mUserBrowser->getData("typeAuth") == "admin"): ?>
                                            <label><?= Translate::sprint("Pack name") ?></label>:

                                            <?php

                                            $this->load->model("pack/pack_model");
                                            $packs = $this->pack_model->getPacks();

                                            echo '<br><select class="select2 select_pack" id="select_pack">';
                                            echo '<option value="0">' . Translate::sprint("Select pack") . '</option>';
                                            foreach ($packs as $value) {

                                                if ($value->id == $user->pack_id)
                                                    echo '<option value="' . $value->id . '" selected>' . $value->name . '</option>';
                                                else
                                                    echo '<option value="' . $value->id . '">' . $value->name . '</option>';
                                            }
                                            echo '</select>';

                                            ?>


                                            <br><br>
                                            <div class="form-group">
                                                <div class="progress-group">

                                                    <?php

                                                    echo '<span class="progress-text">' . Translate::sprint("Number of stores") . '</span>';
                                                    if ($package['nbr_stores'] == -1) {

                                                        echo '<span class="progress-number">∞</span>';
                                                        echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';

                                                    } else {


                                                        if($package['nbr_stores']>0){
                                                            $t = $user->nbr_stores / $package['nbr_stores'];
                                                        }else{
                                                            $t = 0;
                                                            $user->nbr_stores = 0;
                                                        }


                                                        $progress = ($t * 100);
                                                        $color = "aqua";
                                                        if ($progress >= 100)
                                                            $color = "aqua";
                                                        else if ($progress > 50 && $progress < 100)
                                                            $color = "yellow";
                                                        else
                                                            $color = "red";

                                                        echo '<span class="progress-number"><b>' . $user->nbr_stores . '</b>' . '/' . $package['nbr_stores'] . '</span>';
                                                        echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                    }

                                                    ?>


                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="progress-group">

                                                    <?php

                                                    echo '<span class="progress-text">' . Translate::sprint("Number of events monthly") . '</span>';

                                                    if ($package['nbr_events_monthly'] == -1) {

                                                        echo '<span class="progress-number">∞</span>';
                                                        echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                    } else {

                                                        if($package['nbr_events_monthly']>0){
                                                            $t = $user->nbr_events_monthly / $package['nbr_events_monthly'];
                                                        }else{
                                                            $t = 0;
                                                            $user->nbr_events_monthly = 0;
                                                        }

                                                        $progress = ($t * 100);
                                                        $color = "aqua";

                                                        if ($progress >= 100)
                                                            $color = "aqua";
                                                        else if ($progress > 50 && $progress < 100)
                                                            $color = "yellow";
                                                        else
                                                            $color = "red";

                                                        echo '<span class="progress-number"><b>' . $user->nbr_events_monthly . '</b>' . '/' . $package['nbr_events_monthly'] . '</span>';
                                                        echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                    }

                                                    ?>


                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="progress-group">

                                                    <?php

                                                    echo '<span class="progress-text">' . Translate::sprint("Campaigns monthly") . '</span>';
                                                    if ($package['nbr_campaign_monthly'] == -1) {


                                                        echo '<span class="progress-number">∞</span>';
                                                        echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                    } else {

                                                        if($package['nbr_campaign_monthly']>0){
                                                            $t = $user->nbr_campaign_monthly / $package['nbr_campaign_monthly'];
                                                        }else{
                                                            $t = 0;
                                                            $user->nbr_campaign_monthly = 0;
                                                        }

                                                        $progress = ($t * 100);
                                                        $color = "aqua";

                                                        if ($progress >= 100)
                                                            $color = "aqua";
                                                        else if ($progress > 50 && $progress < 100)
                                                            $color = "yellow";
                                                        else
                                                            $color = "red";

                                                        echo '<span class="progress-number"><b>' . $user->nbr_campaign_monthly . '</b>' . '/' . $package['nbr_campaign_monthly'] . '</span>';
                                                        echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                    }

                                                    ?>


                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="progress-group">

                                                    <?php

                                                    echo '<span class="progress-text">' . Translate::sprint("Number of offers monthly") . '</span>';
                                                    if ($package['nbr_offer_monthly'] == -1) {


                                                        echo '<span class="progress-number">∞</span>';
                                                        echo '<div class="progress sm">
                                                            <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                        </div>';
                                                    } else {

                                                        if($package['nbr_offer_monthly']>0){
                                                            $t = $user->nbr_offer_monthly / $package['nbr_offer_monthly'];
                                                        }else{
                                                            $t = 0;
                                                            $user->nbr_offer_monthly = 0;
                                                        }


                                                        $progress = ($t * 100);
                                                        $color = "aqua";

                                                        if ($progress >= 100)
                                                            $color = "aqua";
                                                        else if ($progress > 50 && $progress < 100)
                                                            $color = "yellow";
                                                        else
                                                            $color = "red";

                                                        echo '<span class="progress-number"><b>' . ($user->nbr_offer_monthly) . '</b>' . '/' . $package['nbr_offer_monthly'] . '</span>';
                                                        echo '<div class="progress sm">
                                                                <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                            </div>';
                                                    }

                                                    ?>


                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <strong>
                                                    <?php
                                                    echo Translate::sprintf("Will be expired at ( %s )", array(
                                                        MyDateUtils::convert($user->will_expired, "UTC", TIME_ZONE, "d, M Y H:i:s")
                                                    ));
                                                    ?>
                                                </strong>
                                            </div>


                                        <?php endif; ?>

                                    </div>


                                </div>

                            </div>
                        <?php endif; ?>
                    </div>


                </div>


            </div>

            <div class="col-md-6">

            </div>
        </div>
    </section>


    <script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
    <script>


        <?php

        $token = $this->mUserBrowser->setToken("S0XsNOi");

        ?>



        $(".profle #btnSave").on('click', function () {

            var selector = $(this);

            var password = $("#form1 #password").val();
            var confirm = $("#form1 #confirm").val();
            var name = $("#form1 #name").val();
            var username = $("#form1 #username").val();


            var typeAuth = $("#form2 #typeAuth").val();


            var dataSet = {
                "id": "<?=$user->id_user?>"/*,"old":old*/,
                "name": name,
                "password": password,
                "username": username,
                "typeAuth": typeAuth,
                "confirm": confirm,
                "image": fileUploaded,
                "token": "<?=$token?>"
            };

            <?php if(ModulesChecker::isRegistred("pack")): ?>
            dataSet.nbr_stores = $("#form2 #nbr_stores").val();
            dataSet.nbr_campaign_monthly = $("#form2 #nbr_campaign_monthly").val();
            dataSet.nbr_events_monthly = $("#form2 #nbr_events_monthly").val();
            dataSet.nbr_offers_monthly = $("#form2 #nbr_offer_monthly").val();
            dataSet.push_campaign_auto = $("#form2 #push_campaign_auto").val();
            <?php endif; ?>


            $.ajax({
                url: "<?=  site_url("ajax/user/edit")?>",
                data: dataSet,
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {

                    selector.attr("disabled", true);

                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled", false);
                    console.log(request);
                },
                success: function (data, textStatus, jqXHR) {

                    selector.attr("disabled", false);
                    console.log(data);
                    if (data.success === 1) {

                        <?php if($itsMe == ""):?>
                        document.location.href = "<?=admin_url("user/users")?>";
                        <?php else: ?>
                        document.location.href = "<?=admin_url("user/edit")?>";
                        <?php endif;?>

                    } else if (data.success === 0) {

                        var errorMsg = "";
                        for (var key in data.errors) {
                            errorMsg = errorMsg + data.errors[key] + "\n";
                        }
                        if (errorMsg !== "") {
                            alert(errorMsg);
                        }
                    }
                }
            });

            return false;

        });


    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
    <script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>

    <script>


        Uploader(true);

        <?php

        $token = $this->mUserBrowser->setToken("SzYjES-4555");

        ?>
        var fileUploaded = {};


        <?php
        if (!empty($images)) {

            foreach ($images as $value) {

                $item = "item_" . $value;
                $data = $value;
                echo "fileUploaded[$value]=$value ;";

            }
        }
        ?>

        $(".image-uploaded #delete").on('click', function () {

            var nameDir = $(this).attr("data");

            delete fileUploaded[nameDir];

            console.log(fileUploaded);
            $(".image-uploaded.item_" + nameDir).remove();

            return false;
        });

        function Uploader(singleFile) {

            $('#fileupload').fileupload({
                url: "<?=site_url("uploader/ajax/uploadImage")?>",
                sequentialUploads: true,
                loadImageFileTypes: /^image\/(gif|jpeg|png|jpg)$/,
                loadImageMaxFileSize: 10000,
                singleFileUploads: singleFile,
                formData: {
                    'token': "<?=$token?>",
                    'ID': "<?=sha1($token)?>"
                },
                dataType: 'json',
                done: function (e, data) {


                    var results = data._response.result.results;
                    $("#progress").addClass("hidden");
                    $("#progress .percent").animate({"width": "0%"});
                    $(".image-uploaded").removeClass("hidden");

                    if (singleFile == true) {
                        fileUploaded = {};
                        $("#image-previews").html(results.html);
                    } else
                        $("#image-previews").append(results.html);

                    fileUploaded[results.image] = results.image;
                    //$("#image-data").val(results.image_data);

                    $(".image-uploaded #delete").on('click', function () {
                        var nameDir = $(this).attr("data");
                        delete fileUploaded[nameDir];
                        $(".image-uploaded.item_" + nameDir).remove();
                        return false;
                    });

                },
                fail: function (e, data) {

                    $("#progress").addClass("hidden");
                    $("#progress .percent").animate({"width": "0%"});


                },
                progressall: function (e, data) {

                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    $("#progress").removeClass("hidden");
                    $("#progress .percent").animate({"width": progress + "%"}, "linear");

                },
                progress: function (e, data) {

                    var progress = parseInt(data.loaded / data.total * 100, 10);

                },
                start: function (e) {

                    $("#fileupload").removeClass("input-error");
                    $(".image-data").text("");

                }
            });


        }


    </script>


    <?php if (ModulesChecker::isRegistred("pack")
        and $this->mUserBrowser->getData('typeAuth') == "admin" && $itsMe == ""
    ): ?>


        <div class="modal fade" id="modal-default-pack">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div style="text-align: center">
                                <h3 class="text-red"><?= Translate::sprint("Are you sure?") ?></h3>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="_select"
                                class="btn btn-flat btn-primary pull-right"><?= Translate::sprint("Yes") ?></button>
                        <button type="button" class="btn btn-flat btn-default pull-right"
                                data-dismiss="modal"><?= Translate::sprint("No") ?></button>
                    </div>
                </div>

                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    <?php endif; ?>


</div>

<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script>


    $('#typeAuth').select2();

    <?php if(ModulesChecker::isRegistred("pack")
    and $this->mUserBrowser->getData('typeAuth') == "admin" && $itsMe == ""): ?>
    $('#select_pack').select2();
    var pack_id = 0;
    $('#select_pack').on('select2:select', function (e) {
        var data = e.params.data;

        pack_id = data.id;

        $('#modal-default-pack').modal('show');

        $("#_select").on('click', function () {

            var selector = $(this);
            $.ajax({
                type: 'post',
                url: "<?=site_url("pack/ajax/changeOwnerPack")?>",
                data: {
                    'pack_id': data.id,
                    'user_id': "<?=$user->id_user?>"
                },
                dataType: 'json',
                beforeSend: function (xhr) {
                    selector.attr("disabled", true);
                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled", false);
                    $('#modal-default-pack').modal('hide');
                },
                success: function (data, textStatus, jqXHR) {

                    $('#modal-default-pack').modal('hide');
                    selector.attr("disabled", false);
                    if (data.success === 1) {
                        document.location.reload()
                    } else if (data.success === 0) {
                        var errorMsg = "";
                        for (var key in data.errors) {
                            errorMsg = errorMsg + data.errors[key] + "\n";
                        }
                        if (errorMsg !== "") {
                            alert(errorMsg);
                        }
                    }
                }

            });

            return false;
        });


        return true;

    });
    <?php endif;?>


    $('a.linkAccess').on('click', function () {
        var url = ($(this).attr('href'));
        var selector = $(this);
        pop(url, selector);

    });


    function pop(url, selector) {

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);
                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');
            },
            success: function (data, textStatus, jqXHR) {

                $('#switcher').modal('hide');
                $('#modal-default').modal('hide');

                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload()
                } else if (data.success === 0) {
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }

        });

        return false;
    }


</script>



