<?php

$timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$languages = Translate::getLangsCodes();


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content">

        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-6">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?= Translate::sprint("Information", "") ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="form" role="form">

                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label><?= Translate::sprint("App name", "") ?> <sup>*</sup> </label>
                                    <input type="text" class="form-control" required="required"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="APP_NAME"
                                           id="APP_NAME" value="<?= $config['APP_NAME'] ?>">
                                </div>

                                <div class="form-group">
                                    <label>  <?php echo Translate::sprint("Default_email", "Default email"); ?></label>
                                    <?php

                                    $defEmail = $config['DEFAULT_EMAIL'];
                                    if ($defEmail == "") {
                                        $defEmail = $this->mUserBrowser->getData("email");
                                    }

                                    ?>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="DEFAULT_EMAIL"
                                           id="DEFAULT_EMAIL" value="<?= $defEmail ?>">
                                </div>

                                <div class="form-group required">

                                    <?php

                                    if (!is_array(APP_LOGO))
                                        $images = json_decode(APP_LOGO, JSON_OBJECT_AS_ARRAY);
                                    if(preg_match('#^([a-zA-Z0-9]+)$#',APP_LOGO)){
                                        $images = array(APP_LOGO=>APP_LOGO);
                                    }


                                    $imagesData = array();

                                    if (count($images) > 0) {
                                        foreach ($images as $key => $value)
                                            $imagesData = _openDir($value);
                                        if(!empty($imagesData))
                                            $imagesData = array($imagesData);
                                    }

                                    ?>


                                    <?php

                                    $upload_plug = $this->uploader->plugin(array(
                                        "limit_key" => "aUvFiles",
                                        "token_key" => "SzsYUjEsS-4555",
                                        "limit" => 1,
                                        "cache" => $imagesData
                                    ));

                                    echo $upload_plug['html'];
                                    TemplateManager::addScript($upload_plug['script']);

                                    ?>
                                </div>


                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary btnSave"><span
                                                class="glyphicon glyphicon-check"></span>&nbsp;<?php echo Translate::sprint("Save", "Save"); ?>
                                    </button>
                                </div>

                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>


                <div class="box box-solid">


                    <div class="box-header with-border">
                        <h3 class="box-title"><b> <?php echo Translate::sprint("APP Client APIs"); ?></b></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-12">

                            <div class="form-group">
                                <label><?php echo Translate::sprint("BASE_URL"); ?> </label>
                                <input type="text" class="form-control" required="required"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." value="<?= site_url() ?>"
                                       disabled>
                            </div>

                            <div class="form-group">
                                <label><?php echo Translate::sprint("BASE_URL_API"); ?> </label>
                                <input type="text" class="form-control" required="required"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..."
                                       value="<?= site_url("api") ?>" disabled>
                            </div>
                            <div class="form-group hidden">
                                <label><?php echo Translate::sprint("CRYPTO_KEY"); ?> <span
                                            style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy_this_key_your_android_res", "Copy this key in your android resource file \"app_config.xml\""); ?></span></label>
                                <input type="text" class="form-control"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." value="<?= CRYPTO_KEY ?>"
                                       disabled>
                            </div>


                            <?php if (defined("ANDROID_PURCHASE_ID") and defined("ANDROID_API")): ?>
                                <div class="form-group">
                                    <label><?php echo Translate::sprint("ANDROID API"); ?> <span
                                                style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy_this_key_your_android_res", "Copy this key in your android resource file \"app_config.xml\""); ?></span></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           value="<?=ANDROID_API?>" disabled>
                                </div>
                            <?php endif; ?>


                            <?php if (defined("IOS_PURCHASE_ID") and defined("IOS_API")): ?>
                                <div class="form-group">
                                    <label><?php echo Translate::sprint("IOS API"); ?> <span
                                                style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy this key your ios config file \"AppConfig.swift\""); ?></span></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." value="<?=IOS_API?>"
                                           disabled>
                                </div>
                            <?php endif; ?>


                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="box box-solid">


                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?= Translate::sprint("App Licences") ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="col-sm-12">

                            <div class="form-group">
                                <label><?=Translate::sprint("Purchase ID for Android")?> <sup>*</sup> </label>
                                <?php if(defined("ANDROID_PURCHASE_ID")): ?>
                                    <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="<?=ANDROID_PURCHASE_ID?>" disabled>
                                <?php else: ?>
                                    <input style="width: 80%;display: inline" type="text" class="form-control" id="SPID"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="">
                                    <button style="width: 19%;"  class="btn btn-primary" id="second_verify"><?=Translate::sprint("Verify")?></button>
                                <?php endif; ?>
                            </div>


                            <div class="form-group">
                                <label><?= Translate::sprint("Purchase ID for iOS") ?> <sup>*</sup> </label>
                                <?php if (defined("IOS_PURCHASE_ID")): ?>
                                    <input type="text" class="form-control" required="required"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           value="<?=IOS_PURCHASE_ID?>" disabled>
                                <?php else: ?>
                                    <input style="width: 80%;display: inline" type="text" class="form-control" id="SPID"
                                           required="required" placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           value="">
                                    <button style="width: 19%;" class="btn btn-primary"
                                            id="second_verify"><?= Translate::sprint("Verify") ?></button>
                                <?php endif; ?>
                            </div>

                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>


                <div class="box box-solid">


                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?php echo Translate::sprint("Default Location", ""); ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="form" role="form">

                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Latitude", ""); ?>  </label>
                                    <input type="number" class="form-control" required="required"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           name="MAP_DEFAULT_LATITUDE" id="MAP_DEFAULT_LATITUDE"
                                           value="<?= $config['MAP_DEFAULT_LATITUDE'] ?>">
                                </div>

                            </div>

                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Longitude", ""); ?>  </label>
                                    <input type="number" class="form-control" required="required"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           name="MAP_DEFAULT_LONGITUDE" id="MAP_DEFAULT_LONGITUDE"
                                           value="<?= $config['MAP_DEFAULT_LONGITUDE'] ?>">
                                </div>

                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary btnSave"><span
                                                class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>




            </div>



            <div class="col-sm-6">
                <div class="box box-solid ">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?php echo Translate::sprint("Dashborad_config"); ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-12">
                            <form class="form" role="form">

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Firebase key"); ?> (FCM) <sup>*</sup></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="FCM_KEY"
                                           id="FCM_KEY" value="<?= $config['FCM_KEY'] ?>">
                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Maps api key"); ?> <sup>*</sup></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="MAPS_API_KEY"
                                           id="MAPS_API_KEY" value="<?= $config['MAPS_API_KEY'] ?>">
                                </div>


                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Enable default front-end"); ?>   </label>
                                    <select id="ENABLE_FRONT_END" name="ENABLE_FRONT_END"
                                            class="form-control select2 ENABLE_FRONT_END">
                                        <?php
                                        if ($config['ENABLE_FRONT_END']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label><?php echo Translate::sprint("Number items per page", ""); ?>
                                                <sup>*</sup></label>
                                            <input type="text" class="form-control"
                                                   placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                   name="NO_OF_ITEMS_PER_PAGE" id="NO_OF_ITEMS_PER_PAGE"
                                                   value="<?= $config['NO_OF_ITEMS_PER_PAGE'] ?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo Translate::sprint("Number stores per page", ""); ?>
                                                <sup>*</sup></label>
                                            <input type="text" class="form-control"
                                                   placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                   name="NO_OF_STORE_ITEMS_PER_PAGE" id="NO_OF_STORE_ITEMS_PER_PAGE"
                                                   value="<?= $config['NO_OF_STORE_ITEMS_PER_PAGE'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label><?php echo Translate::sprint("Dashboard analytics", ""); ?>
                                                <sup>*</sup></label>
                                            <input type="text" class="form-control"
                                                   placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                   name="DASHBOARD_ANALYTICS" id="DASHBOARD_ANALYTICS"
                                                   value="<?= $config['DASHBOARD_ANALYTICS'] ?>">
                                        </div>

                                        <div class="col-sm-6">
                                            <label><?php echo Translate::sprint("Dashboard Color"); ?>   </label>
                                            <input type="text" class="form-control colorpicker1"
                                                   placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                   name="DASHBOARD_COLOR" id="DASHBOARD_COLOR"
                                                   value="<?= $config['DASHBOARD_COLOR'] ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group hidden">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label><?php echo Translate::sprint("Upload limitation"); ?> <sup>*</sup>
                                                <span style="color: grey;font-size: 11px;"><?= Translate::sprint("Number uploaded images per stores & events") ?></span></label>

                                            <input type="text" class="form-control"
                                                   placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                   name="IMAGES_LIMITATION" id="IMAGES_LIMITATION"
                                                   value="<?= $config['IMAGES_LIMITATION'] ?>">
                                        </div>
                                    </div>
                                </div>


                                <input type="number" class="form-control hidden"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..."
                                       name="NO_OF_ITEMS_PER_PAGE_HOME" id="NO_OF_ITEMS_PER_PAGE_HOME"
                                       value="<?= $config['NO_OF_ITEMS_PER_PAGE_HOME'] ?>">


                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Enable_store", "Enable store"); ?> <span
                                                style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_store_auto", "Customer can publish own store auto"); ?>
                                            )</span></label>
                                    <select id="ENABLE_STORE_AUTO" name="ENABLE_STORE_AUTO"
                                            class="form-control select2 ENABLE_STORE_AUTO">
                                        <?php
                                        if ($config['ENABLE_STORE_AUTO']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Enable Offer", "Enable offer"); ?> <span
                                                style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_store_auto", "Customer can publish own offer auto"); ?>
                                            )</span></label>
                                    <select id="ENABLE_OFFER_AUTO" name="ENABLE_OFFER_AUTO"
                                            class="form-control select2 ENABLE_OFFER_AUTO">
                                        <?php
                                        if ($config['ENABLE_OFFER_AUTO']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Enable auto hidden offers", "Enable auto hidden offers"); ?> </label>
                                    <select id="ENABLE_AUTO_HIDDEN_OFFERS" name="ENABLE_AUTO_HIDDEN_OFFERS"
                                            class="form-control select2 ENABLE_AUTO_HIDDEN_OFFERS">
                                        <?php
                                        if ($config['ENABLE_AUTO_HIDDEN_OFFERS']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Show only offers starting at the current day", ""); ?> </label>
                                    <select id="OFFERS_IN_DATE" name="OFFERS_IN_DATE"
                                            class="form-control select2 OFFERS_IN_DATE">
                                        <?php
                                        if ($config['OFFERS_IN_DATE']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Enable auto hidden events", "Enable auto hidden events"); ?> </label>
                                    <select id="ENABLE_AUTO_HIDDEN_EVENTS" name="ENABLE_AUTO_HIDDEN_EVENTS"
                                            class="form-control select2 ENABLE_AUTO_HIDDEN_EVENTS">
                                        <?php
                                        if ($config['ENABLE_AUTO_HIDDEN_EVENTS']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label> <?php echo Translate::sprint("Enable Event", "Enable event"); ?> <span
                                                style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_event_auto", "Customer can publish own event auto"); ?>
                                            )</span></label>
                                    <select id="ENABLE_EVENT_AUTO" name="ENABLE_EVENT_AUTO"
                                            class="form-control select2 ENABLE_EVENT_AUTO">
                                        <?php
                                        if ($config['ENABLE_EVENT_AUTO']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Default_timezone", "Default timezone"); ?>
                                        <sup>*</sup></label>
                                    <select id="TIME_ZONE" name="TIME_ZONE" class="form-control select2 TIME_ZONE">
                                        <option value='0'>-- Timezones</option>
                                        <?php

                                        $def_val = 0;
                                        foreach ($timezones as $tz) {
                                            if ($config['TIME_ZONE']
                                                == $tz) {
                                                echo '<option value="' . $tz . '" selected>' . $tz . '</option>';
                                                $def_val = $tz;
                                            } else {
                                                echo '<option value="' . $tz . '">' . $tz . '</option>';
                                            }

                                        }
                                        ?>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Default_language", "Default language"); ?>
                                        <sup>*</sup></label>
                                    <select id="DEFAULT_LANG" name="DEFAULT_LANG"
                                            class="form-control select2 DEFAULT_LANG">
                                        <option value='0'>-- Languages</option>
                                        <?php

                                        foreach ($languages as $key => $lng) {
                                            if ($config['DEFAULT_LANG']
                                                == $key) {
                                                echo '<option value="' . $key . '" selected>' . $lng['name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $key . '">' . $lng['name'] . '</option>';
                                            }

                                        }
                                        ?>
                                    </select>

                                </div>


                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary btnSave"><span
                                                class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                                    </button>
                                </div>


                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>


            <div class="col-sm-6">

                <div class="box box-solid ">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?= Translate::sprint("Campaign config") ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-12">
                            <form class="form" role="form">


                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Target_raduis", "Target raduis"); ?>
                                        <sup>KM</sup></label>
                                    <input type="number" min="0" max="100" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="RADUIS_TRAGET"
                                           id="RADUIS_TRAGET" value="<?= $config['RADUIS_TRAGET'] ?>">
                                </div>

                                <div class="form-group">
                                    <label> <?= Translate::sprint("Number max pushes per campaign", ""); ?> </label>
                                    <input type="number" min="0" max="1000" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           name="LIMIT_PUSHED_GUESTS_PER_CAMPAIGN" id="LIMIT_PUSHED_GUESTS_PER_CAMPAIGN"
                                           value="<?= $config['LIMIT_PUSHED_GUESTS_PER_CAMPAIGN'] ?>">
                                </div>

                                <div class="form-group">
                                    <label>

                                        <?php

                                        echo Translate::sprint("Use_campaign_with_crontab", "Use campaign with crontab"); ?>
                                        <span style="color: grey;font-size: 11px;">(Ex: <?php echo Translate::sprint("you_can_push_10_campaigns_for_every_10_minutes", "you can push 10 campaigns for every 10 minutes"); ?>
                                            )</span>

                                    </label>


                                    <select id="PUSH_CAMPAIGNS_WITH_CRON" name="PUSH_CAMPAIGNS_WITH_CRON"
                                            class="form-control select2 PUSH_CAMPAIGNS_WITH_CRON">
                                        <?php

                                        if ($config['PUSH_CAMPAIGNS_WITH_CRON']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }

                                        ?>
                                    </select>

                                    <BR>
                                    <label>
                                       <span style="color: grey;font-size: 11px;">
                                  <?php
                                  echo Translate::sprint("set this command in your cronjob") . " <BR><CODE> /usr/bin/php -q " . FCPATH . "cronjob.php</CODE>";
                                  ?>
                                      </span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Number_max_pushes_per_campaign_with_cronjob_in_every_execute", "Maximum number of pushes  per campaign with crontab in each run"); ?> </label>
                                    <input type="number" min="0" max="100" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..."
                                           name="NBR_PUSHS_FOR_EVERY_TIME" id="NBR_PUSHS_FOR_EVERY_TIME"
                                           value="<?= $config['NBR_PUSHS_FOR_EVERY_TIME'] ?>">
                                </div>


                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary btnSave"><span
                                                class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>



            <div class="col-sm-6">

                <div class="box box-solid ">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?= Translate::sprint("SMTP Config") ?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-12">
                            <form class="form" role="form">

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Enable SMTP SERVER"); ?> </label>
                                    <select id="SMTP_SERVER_ENABLED" name="SMTP_SERVER_ENABLED"
                                            class="form-control select2 SMTP_SERVER_ENABLED">
                                        <?php
                                        if ($config['SMTP_SERVER_ENABLED']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("SMTP Host"); ?></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="SMTP_HOST"
                                           id="SMTP_HOST" value="<?= $config['SMTP_HOST'] ?>">
                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("SMTP Port"); ?></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="SMTP_PORT"
                                           id="SMTP_PORT" value="<?= $config['SMTP_PORT'] ?>">
                                </div>


                                <div class="form-group">
                                    <label><?php echo Translate::sprint("SMTP user"); ?></label>
                                    <input type="text" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="SMTP_USER"
                                           id="SMTP_USER" value="<?= $config['SMTP_USER'] ?>">
                                </div>

                                <div class="form-group">
                                    <label><?php echo Translate::sprint("SMTP pass"); ?></label>
                                    <input type="password" class="form-control"
                                           placeholder="<?= Translate::sprint("Enter") ?> ..." name="SMTP_PASS"
                                           id="SMTP_PASS" value="<?= $config['SMTP_PASS'] ?>">
                                </div>


                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary btnSave"><span
                                                class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>


    </section>

</div>


<?php

$data['config'] = $config;
$data['uploader_variable'] = $upload_plug['var'];


$script = $this->load->view('backend/html/scripts/config-script', $data, TRUE);
TemplateManager::addScript($script);

?>




