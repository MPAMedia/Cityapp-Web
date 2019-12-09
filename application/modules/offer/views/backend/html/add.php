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
                            <b><?= Translate::sprint("Add new offer", "") ?></b>
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
                                           placeholder="Ex: black friday">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Description", "") ?></label>
                                    <textarea class="form-control" rows="7" id="editable-textarea"
                                              placeholder="<?= Translate::sprint("Enter") ?> ..."></textarea>
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

                                    $upload_plug = $this->uploader->plugin(array(
                                        "limit_key"     => "aOhFiles",
                                        "token_key"     => "SzYjEsS-4555",
                                        "limit"         => MAX_OFFER_IMAGES,
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
                                                           placeholder="<?= Translate::sprint("Enter price of your offer") ?>">
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
                                                    <input type="number" class="form-control" id="percentInput"
                                                           placeholder="Exemple : -50 %">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label> <?= Translate::sprint("Date Begin", "") ?> </label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </div>
                                                            <input class="form-control" data-provide="datepicker"
                                                                   placeholder="YYYY-MM-DD" type="text"
                                                                   name="date_b"
                                                                   id="date_b"
                                                                   value="<?= date("Y-m-d", time()) ?>"/>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6"><label>
                                                            <?= Translate::sprint("Date End") ?> </label>

                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </div>
                                                            <input class="form-control" data-provide="datepicker"
                                                                   type="text" placeholder="YYYY-MM-DD"
                                                                   name="date_e"
                                                                   id="date_e"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="box-footer">
                                <?php

                                    $usr_id = $this->mUserBrowser->getData('id_user');
                                    $nbr_offers = UserSettingSubscribe::getUDBSetting($usr_id,KS_NBR_OFFERS_MONTHLY);

                                ?>

                                <?php if($nbr_offers>0 or $nbr_offers==-1): ?>
                                <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                            class="glyphicon glyphicon-check"></span>
                                    <?= Translate::sprint("Create") ?> </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary" id="btnCreate" disabled><span
                                                class="glyphicon glyphicon-check"></span>
                                        <?= Translate::sprint("Create") ?> </button>
                                &nbsp;&nbsp;
                                    <span class="text-red font-size12px"><i class="mdi mdi-information-outline"></i>&nbsp;<?=Translate::sprint(Messages::EXCEEDED_MAX_NBR_OFFERS).$nbr_offers?></span>
                                <?php endif; ?>
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
<?php


$data['uploader_variable'] = $upload_plug['var'];

$script = $this->load->view('backend/html/scripts/add-script',$data,TRUE);
TemplateManager::addScript($script);

?>
