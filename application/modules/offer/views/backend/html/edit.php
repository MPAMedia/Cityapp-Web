<?php

$offer = $offer[Tags::RESULT][0];
$adminAccess = "";
if ($offer['user_id'] != $this->mUserBrowser->getData("id_user")) {
    $adminAccess = "disabled";
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>
        </div>

        <div class="row" id="form">
            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-header">

                        <div class="box-title">
                            <b><?= Translate::sprint("Edit Offer", "") ?></b>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label><?= Translate::sprint("Store") ?></label>
                                    <select id="selectStore" class="form-control select2 selectStore" style="width: 100%;">
                                        <option selected="selected" value="0">
                                            <?= Translate::sprint("Select store", "") ?></option>
                                        <?php

                                        if (isset($myStores[Tags::RESULT])) {
                                            foreach ($myStores[Tags::RESULT] as $st) {
                                                echo '<option adr="' . $st['address'] . '" 
                                            lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                            value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?= Translate::sprint("Name", "") ?></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Ex: black friday" value="<?= $offer['name'] ?>">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Description", "") ?></label>
                                    <textarea class="form-control" rows="7" id="editable-textarea"
                                              placeholder="<?= Translate::sprint("Enter") ?> ..."><?= $offer['description'] ?></textarea>
                                </div>

                                <!--                                <select class="form-control" id="tags"  multiple="multiple" placeholder>-->
                                <!--                                </select>-->


                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <?php if (GroupAccess::isGranted('user',USER_ADMIN)): ?>
                <div class="col-md-6">

                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>
                                    <?= Translate::sprint("Options") ?></b></h3>
                        </div>

                        <div class="box-body">

                            <?php

                            $checked0 = "";
                            if (intval($offer['featured']) == 0)
                                $checked0 = " checked='checked'";

                            $checked = "";
                            if (intval($offer['featured']) == 1)
                                $checked = " checked='checked'";

                            ?>

                            <div class="form-group">
                                <label style="cursor: pointer;">
                                    <input name="featured" type="radio" id="featured_item0" <?= $checked0 ?>>&nbsp;&nbsp;
                                    <?= Translate::sprint("Disabled Featured") ?>
                                </label><br>
                                <label style="cursor: pointer;">
                                    <input name="featured" type="radio" id="featured_item1" <?= $checked ?>>&nbsp;&nbsp;
                                    <?= Translate::sprint("Make it as featured") ?>
                                </label>
                            </div>


                        </div>

                    </div>

                </div>
            <?php endif; ?>

            <div class="col-sm-6">



                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-solid">
                            <div class="box-header">

                                <div class="box-title">
                                    <b><?= Translate::sprint("Images", "") ?></b>
                                </div>

                            </div>


                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group required">

                                    <?php

                                    $images = $offer['images'];
                                    if ($images != "" AND !is_array($images)) {
                                        $images = json_decode($images);
                                    }

                                    ?>

                                    <?php

                                    $upload_plug = $this->uploader->plugin(array(
                                        "limit_key"     => "aOhFiles",
                                        "token_key"     => "SzYjEsS-4555",
                                        "limit"         => MAX_OFFER_IMAGES,
                                        "cache"         => $images,
                                    ));

                                    echo $upload_plug['html'];
                                    TemplateManager::addScript($upload_plug['script']);

                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="box box-solid">
                            <div class="box-header">

                                <div class="box-title">
                                    <b><?= Translate::sprint("Pricing", "") ?></b>
                                    <span style="color: grey;font-size: 11px;">(  <?php echo Translate::sprint("For promotion offers, leave this field as the default ", ""); ?>)</span>
                                </div>

                            </div>

                            <div class="box-body">
                                <!-- text input -->
                                <div class="col-sm-12 pricing">

                                    <div class="form-group">
                                        <select id="value_type" class="select2">
                                            <option value="0">-- <?= Translate::sprint('Select Type') ?></option>
                                            <option value="1"><?= Translate::sprint('Price') ?></option>
                                            <option value="2"><?= Translate::sprint('Percent') ?></option>
                                        </select>
                                    </div>

                                    <div class="form-group form-price hidden">
                                        <div class="row">
                                            <div class="col-sm-6 no-margin">
                                                <?php

                                                    $currency = $this->mCurrencyModel->getCurrency(DEFAULT_CURRENCY);

                                                ?>
                                                <label><?= Translate::sprint("Offer price") ?></label>
                                                <div class="form-group">
                                                    <input type="number" class="form-control" id="priceInput"
                                                           placeholder="<?= Translate::sprint("Enter price of your offer") ?>" value="<?php if($offer['value_type']=="price") echo $offer['offer_value']?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 no-margin" style="padding-left: 0px;">
                                                <?php

                                                $currencies = $this->mCurrencyModel->getAllCurrencies();

                                                ?>
                                                <div class="form-group">
                                                    <label><?= Translate::sprint("Select offer currency") ?></label>
                                                    <select id="selectCurrency"
                                                            class="form-control select2 selectCurrency"
                                                            style="width: 100%;">
                                                        <option selected="selected"
                                                                value="0"> <?= Translate::sprint("Select") ?></option>
                                                        <?php

                                                        foreach ($currencies as $key => $value) {
                                                            if ($value['code'] == DEFAULT_CURRENCY)
                                                                echo '<option selected="selected" value="' . $value['code'] . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';
                                                            else
                                                                echo '<option value="' . $value['code'] . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-percent hidden">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label><?= Translate::sprint("Offer percent", "") ?> </label>
                                                <div class="form-group">
                                                    <input  <?= $adminAccess ?>  type="number" class="form-control" id="percentInput"
                                                           placeholder="Exemple : -50 %"  value="<?php if($offer['value_type']=="percent") echo $offer['offer_value']?>">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label> <?= Translate::sprint("Date Begin") ?>  </label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </div>
                                                            <?php

                                                            $date_start = "";
                                                            if ($offer['date_start'] != "")
                                                                $date_start = date("Y-m-d", strtotime($offer['date_start']));

                                                            ?>
                                                            <input  <?= $adminAccess ?> class="form-control" data-provide="datepicker"
                                                                   placeholder="YYYY-MM-DD" type="text" name="date_b"
                                                                   id="date_b"
                                                                   value="<?= $date_start?>"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label><?= Translate::sprint("Date End") ?> </label>

                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </div>

                                                            <?php

                                                            $date_end = "";
                                                            if ($offer['date_end'] != "")
                                                                $date_end = date("Y-m-d", strtotime($offer['date_end']));

                                                            ?>
                                                            <input <?= $adminAccess ?> class="form-control"
                                                                                       data-provide="datepicker" type="text"
                                                                                       placeholder="YYYY-MM-DD"
                                                                                       name="date_e" id="date_e"
                                                                                       value="<?= $date_end ?>"/>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="box-footer">
                                <button <?= $adminAccess ?>  type="button" class="btn  btn-primary" id="btnSave"><span
                                            class="glyphicon glyphicon-check"></span>
                                    <?= Translate::sprint("Save Changes", "") ?> </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php


$data['offer'] = $offer;
$data['uploader_variable'] = $upload_plug['var'];

$script = $this->load->view('backend/html/scripts/edit-script',$data,TRUE);
TemplateManager::addScript($script);

?>



