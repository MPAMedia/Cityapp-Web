<?php

$timezones =  DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$languages =  Translate::getLangsCodes();


?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

 <section class="content">

     <div class="row">
         <!-- Message Error -->
         <div class="col-sm-12">
             <?php $this->load->view("backend/include/messages");?>
         </div>

     </div>

     <div class="row">

          <div class="col-sm-6">

              <div class="box box-solid">
                  <div class="box-header with-border">
                      <h3 class="box-title">  <b><?=Translate::sprint("Information","")?></b></h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <form class="form" role="form">

                          <div class="col-sm-12">

                              <div class="form-group">
                                  <label><?=Translate::sprint("App name","")?>  <sup>*</sup> </label>
                                  <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..." name="APP_NAME" id="APP_NAME" value="<?=$config['APP_NAME']?>">
                              </div>

                              <div class="form-group">
                                  <label>  <?php echo Translate::sprint("Default_email","Default email"); ?></label>
                                  <?php

                                    $defEmail = $config['DEFAULT_EMAIL'];
                                    if($defEmail==""){
                                        $defEmail = $this->mUserBrowser->getData("email");
                                    }

                                  ?>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="DEFAULT_EMAIL" id="DEFAULT_EMAIL" value="<?=$defEmail?>">
                              </div>

                              <div class="form-group required">
                                  <label for="name"><?php echo Translate::sprint("Back-office_logo","Back-office logo"); ?> </label>

                                  <label class="msg-error-form image-data"></label>
                                  <input type="file" name="addimage" id="fileupload"><br>
                                  <div class="clear"></div>
                                  <div id="progress" class="hidden">
                                      <div class="percent" style="width: 0%"></div>
                                  </div>
                                  <div class="clear"></div>



                                  <div id="image-previews">

                                      <?php

                                        $imagesData = _openDir(APP_LOGO);

                                      ?>

                                      <?php if(!empty($imagesData)){ ?>
                                              <?php

                                              $item = "item_".$imagesData['name'];
                                              $idata = $imagesData['name'];

                                              ?>

                                              <div class="image-uploaded <?=$item?>">
                                                  <a id="image-preview">
                                                      <img src="<?=$imagesData['200_200']['url']?>" alt="">
                                                  </a>

                                                  <div class="clear"></div>
                                                  <a href="#" data="<?=$idata?>" id="delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?php echo Translate::sprint("Delete","Delete"); ?> </a>
                                              </div>

                                      <?php } ?>
                                  </div>

                              </div>


                              <div class="form-group">
                                  <button type="button" class="btn  btn-primary btnSave"> <span class="glyphicon glyphicon-check"></span>&nbsp;<?php echo Translate::sprint("Save","Save"); ?>   </button></div>

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
                                  <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="<?=site_url()?>" disabled>
                              </div>

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("BASE_URL_API"); ?> </label>
                                  <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="<?=site_url("api")?>" disabled>
                              </div>
                              <div class="form-group hidden">
                                  <label><?php echo Translate::sprint("CRYPTO_KEY"); ?> <span style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy_this_key_your_android_res","Copy this key in your android resource file \"app_config.xml\""); ?></span></label>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." value="<?=CRYPTO_KEY?>" disabled>
                              </div>


                              <?php if(defined("ANDROID_PURCHASE_ID") and defined("ANDROID_API")): ?>
                              <div class="form-group">
                                  <label><?php echo Translate::sprint("ANDROID API"); ?> <span style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy_this_key_your_android_res","Copy this key in your android resource file \"app_config.xml\""); ?></span></label>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." value="<?=ANDROID_API?>" disabled>
                              </div>
                              <?php endif; ?>


                              <?php if(defined("IOS_PURCHASE_ID") and defined("IOS_API")): ?>
                              <div class="form-group">
                                  <label><?php echo Translate::sprint("IOS API"); ?> <span style="color: grey;font-size: 11px;"><BR>NB: <?php echo Translate::sprint("Copy this key your ios config file \"AppConfig.swift\""); ?></span></label>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." value="<?=IOS_API?>" disabled>
                              </div>
                              <?php endif; ?>


                          </div>

                  </div>
                  <!-- /.box-body -->
              </div>

              <div class="box box-solid">


                  <div class="box-header with-border">
                      <h3 class="box-title"><b><?=Translate::sprint("App Licences")?></b></h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">

                          <div class="col-sm-12">

                              <div class="form-group">
                                  <label><?=Translate::sprint("Purchase ID for android")?> <sup>*</sup> </label>
                                  <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="<?=$config['ANDROID_PURCHASE_ID']?>" disabled>
                              </div>


                              <div class="form-group">
                                  <label><?=Translate::sprint("Purchase ID for iOS")?> <sup>*</sup> </label>
                                  <?php if(defined("IOS_PURCHASE_ID")): ?>
                                      <input type="text" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="<?=IOS_PURCHASE_ID?>" disabled>
                                  <?php else: ?>
                                      <input style="width: 80%;display: inline" type="text" class="form-control" id="SPID"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..."  value="">
                                        <button style="width: 19%;"  class="btn btn-primary" id="second_verify"><?=Translate::sprint("Verify")?></button>
                                  <?php endif; ?>
                              </div>

                          </div>

                  </div>
                  <!-- /.box-body -->
              </div>


              <div class="box box-solid">


                  <div class="box-header with-border">
                      <h3 class="box-title"><b><?php echo Translate::sprint("Default Location",""); ?></b></h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <form class="form" role="form">

                          <div class="col-sm-12">

                              <div class="form-group">
                                  <label> <?php echo Translate::sprint("Latitude",""); ?>  </label>
                                  <input type="number" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..." name="MAP_DEFAULT_LATITUDE" id="MAP_DEFAULT_LATITUDE"   value="<?=$config['MAP_DEFAULT_LATITUDE']?>" >
                              </div>

                          </div>

                          <div class="col-sm-12">

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Longitude",""); ?>  </label>
                                  <input type="number" class="form-control"  required="required"  placeholder="<?=Translate::sprint("Enter")?> ..." name="MAP_DEFAULT_LONGITUDE" id="MAP_DEFAULT_LONGITUDE"  value="<?=$config['MAP_DEFAULT_LONGITUDE']?>">
                              </div>

                          </div>

                          <div class="col-sm-12">
                              <div class="form-group">
                                  <button type="button" class="btn  btn-primary btnSave"> <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>   </button>
                              </div>
                          </div>

                      </form>
                  </div>
                  <!-- /.box-body -->
              </div>





              <div class="box box-solid ">
                  <div class="box-header with-border">
                      <h3 class="box-title"><b><?php echo Translate::sprint("Dashborad_config"); ?></b></h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <div class="col-sm-12">
                          <form class="form" role="form">

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Firebase key"); ?>  (FCM) <sup>*</sup></label>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="FCM_KEY" id="FCM_KEY" value="<?=$config['FCM_KEY']?>">
                              </div>

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Maps api key"); ?> <sup>*</sup></label>
                                  <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="MAPS_API_KEY" id="MAPS_API_KEY" value="<?=$config['MAPS_API_KEY']?>">
                              </div>


                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Enable default front-end"); ?>   </label>
                                  <select id="ENABLE_FRONT_END" name="ENABLE_FRONT_END" class="form-control select2 ENABLE_FRONT_END">
                                      <?php
                                      if($config['ENABLE_FRONT_END']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>

                              </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-sm-6">
                                          <label><?php echo Translate::sprint("Number items per page",""); ?>   <sup>*</sup></label>
                                          <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="NO_OF_ITEMS_PER_PAGE" id="NO_OF_ITEMS_PER_PAGE" value="<?=$config['NO_OF_ITEMS_PER_PAGE']?>">
                                      </div>
                                      <div class="col-sm-6">
                                          <label><?php echo Translate::sprint("Number stores per page",""); ?>   <sup>*</sup></label>
                                          <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="NO_OF_STORE_ITEMS_PER_PAGE" id="NO_OF_STORE_ITEMS_PER_PAGE" value="<?=$config['NO_OF_STORE_ITEMS_PER_PAGE']?>">
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group">
                                  <div class="row">
                                      <div class="col-sm-6">
                                          <label><?php echo Translate::sprint("Dashboard analytics",""); ?>   <sup>*</sup></label>
                                          <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="DASHBOARD_ANALYTICS" id="DASHBOARD_ANALYTICS" value="<?=$config['DASHBOARD_ANALYTICS']?>">
                                      </div>

                                      <div class="col-sm-6">
                                          <label><?php echo Translate::sprint("Dashboard Color"); ?>   </label>
                                          <input type="text" class="form-control colorpicker1" placeholder="<?=Translate::sprint("Enter")?> ..." name="DASHBOARD_COLOR" id="DASHBOARD_COLOR" value="<?=$config['DASHBOARD_COLOR']?>">
                                      </div>
                                  </div>
                              </div>


                              <div class="form-group hidden">
                                  <div class="row">
                                      <div class="col-sm-6">
                                          <label><?php echo Translate::sprint("Upload limitation"); ?> <sup>*</sup>   <span style="color: grey;font-size: 11px;"><?=Translate::sprint("Number uploaded images per stores & events")?></span></label>

                                          <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="IMAGES_LIMITATION" id="IMAGES_LIMITATION" value="<?=$config['IMAGES_LIMITATION']?>">
                                      </div>
                                  </div>
                              </div>


                              <input type="number" class="form-control hidden" placeholder="<?=Translate::sprint("Enter")?> ..." name="NO_OF_ITEMS_PER_PAGE_HOME" id="NO_OF_ITEMS_PER_PAGE_HOME" value="<?=$config['NO_OF_ITEMS_PER_PAGE_HOME']?>">


                              <div class="form-group">
                                  <label> <?php echo Translate::sprint("Enable_store","Enable store"); ?> <span style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_store_auto","Customer can publish own store auto"); ?>)</span></label>
                                  <select id="ENABLE_STORE_AUTO" name="ENABLE_STORE_AUTO" class="form-control select2 ENABLE_STORE_AUTO">
                                      <?php
                                      if($config['ENABLE_STORE_AUTO']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label> <?php echo Translate::sprint("Enable Offer","Enable offer"); ?> <span style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_store_auto","Customer can publish own offer auto"); ?>)</span></label>
                                  <select id="ENABLE_OFFER_AUTO" name="ENABLE_OFFER_AUTO" class="form-control select2 ENABLE_OFFER_AUTO">
                                      <?php
                                      if($config['ENABLE_OFFER_AUTO']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label> <?php echo Translate::sprint("Enable auto hidden offers","Enable auto hidden offers"); ?> </label>
                                  <select id="ENABLE_AUTO_HIDDEN_OFFERS" name="ENABLE_AUTO_HIDDEN_OFFERS" class="form-control select2 ENABLE_AUTO_HIDDEN_OFFERS">
                                      <?php
                                      if($config['ENABLE_AUTO_HIDDEN_OFFERS']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label> <?php echo Translate::sprint("Enable Event","Enable event"); ?> <span style="color: grey;font-size: 11px;">( <?php echo Translate::sprint("Customer_can_publish_own_event_auto","Customer can publish own event auto"); ?>)</span></label>
                                  <select id="ENABLE_EVENT_AUTO" name="ENABLE_EVENT_AUTO" class="form-control select2 ENABLE_EVENT_AUTO">
                                      <?php
                                      if($config['ENABLE_EVENT_AUTO']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>
                              </div>


                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Default_timezone","Default timezone"); ?> <sup>*</sup></label>
                                  <select id="TIME_ZONE" name="TIME_ZONE" class="form-control select2 TIME_ZONE">
                                      <option value='0'>-- Timezones</option>
                                      <?php

                                      $def_val = 0;
                                      foreach ($timezones as $tz){
                                          if($config['TIME_ZONE']
                                              ==$tz){
                                              echo '<option value="'.$tz.'" selected>'.$tz.'</option>';
                                              $def_val = $tz;
                                          }else{
                                              echo '<option value="'.$tz.'">'.$tz.'</option>';
                                          }

                                      }
                                      ?>
                                  </select>

                              </div>

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Default_language","Default language"); ?>  <sup>*</sup></label>
                                  <select id="DEFAULT_LANG" name="DEFAULT_LANG" class="form-control select2 DEFAULT_LANG">
                                      <option value='0'>-- Languages</option>
                                      <?php

                                      foreach ($languages as $key => $lng){
                                          if($config['DEFAULT_LANG']
                                              ==$key){
                                              echo '<option value="'.$key.'" selected>'.$lng['name'].'</option>';
                                          }else{
                                              echo '<option value="'.$key.'">'.$lng['name'].'</option>';
                                          }

                                      }
                                      ?>
                                  </select>

                              </div>




                              <div class="form-group">
                                  <button type="button" class="btn  btn-primary btnSave" > <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>  </button></div>


                          </form>
                      </div>
                  </div>
                  <!-- /.box-body -->
              </div>

          </div>

          <div class="col-sm-6">

            <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo Translate::sprint("User subscription",""); ?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form class="form" role="form">

                  <div class="col-sm-12">

                      <div class="form-group">
                          <label> <?php echo Translate::sprint("Number_max_of_stores","Number max of stores"); ?>   <span style="color: grey">(-1 = unlimited)</span></label>
                          <input type="number" min="-1" max="100" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="LIMIT_NBR_STORES" id="LIMIT_NBR_STORES" value="<?=$config['LIMIT_NBR_STORES']?>">
                      </div>

                      <div class="form-group">
                          <label>  <?php echo Translate::sprint("Number_events_max_monthly","Number events max monthly"); ?> <span style="color: grey">(-1 = unlimited)</span></label>

                          <input type="number" min="-1" max="100" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="LIMIT_NBR_EVENTS_MONTHLY" id="LIMIT_NBR_EVENTS_MONTHLY" value="<?=$config['LIMIT_NBR_EVENTS_MONTHLY']?>">
                      </div>

                      <div class="form-group">
                          <label> <?php echo Translate::sprint("Number_campaigns_max_monthly","Number campaigns max monthly"); ?>  <span style="color: grey">(-1 = unlimited)</span> </label>
                          <input type="number" min="-1" max="100" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="LIMIT_NBR_COMPAIGN_MONTHLY" id="LIMIT_NBR_COMPAIGN_MONTHLY" value="<?=$config['LIMIT_NBR_COMPAIGN_MONTHLY']?>">
                      </div>

                      <div class="form-group">
                          <label><?php echo Translate::sprint("Number_offers_max_monthly","Number offers max monthly "); ?>  <span style="color: grey">(-1 = unlimited)</span></label>
                          <input type="number" min="-1" max="100" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="LIMIT_NBR_OFFERS_MONTHLY" id="LIMIT_NBR_OFFERS_MONTHLY" value="<?=$config['LIMIT_NBR_OFFERS_MONTHLY']?>">
                      </div>

                      <div class="form-group">
                          <label><?php echo Translate::sprint("Email_verificaion","Email verification"); ?>   </label>
                          <select id="EMAIL_VERIFICATION" name="EMAIL_VERIFICATION" class="form-control select2 EMAIL_VERIFICATION">
                              <?php
                              if($config['EMAIL_VERIFICATION']){
                                  echo '<option value="true" selected>true</option>';
                                  echo '<option value="false" >false</option>';
                              }else{
                                  echo '<option value="true"  >true</option>';
                                  echo '<option value="false"  selected>false</option>';
                              }
                              ?>
                          </select>

                      </div>



                      <div class="form-group">
                          <label><?php echo Translate::sprint("Welcome message"); ?> <span style="color: grey;font-size: 11px">
                                  <?=Translate::sprint("Optional field")?></span></label>
                          <textarea id="MESSAGE_WELCOME" class="form-control" rows="3" placeholder="<?=Translate::sprint("Enter")?> ..."><?=$config['MESSAGE_WELCOME']?></textarea>
                      </div>



                      <div class="form-group">
                          <label><?php echo Translate::sprint("Enable user (owner) registration"); ?>   </label>
                          <select id="USER_REGISTRATION" name="USER_REGISTRATION" class="form-control select2 USER_REGISTRATION">
                              <?php
                              if($config['USER_REGISTRATION']){
                                  echo '<option value="true" selected>true</option>';
                                  echo '<option value="false" >false</option>';
                              }else{
                                  echo '<option value="true"  >true</option>';
                                  echo '<option value="false"  selected>false</option>';
                              }
                              ?>
                          </select>

                      </div>



                      <div class="form-group">
                              <button type="button" class="btn  btn-primary btnSave"> <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>   </button></div>

                  </div>

              </form>
            </div>
            <!-- /.box-body -->
          </div>
      </div>





          <div class="col-sm-6">

              <div class="box box-solid ">
                  <div class="box-header with-border">
                      <h3 class="box-title"><b><?=Translate::sprint("Campaign config")?></b></h3>
                      <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <div class="col-sm-12">
                          <form class="form" role="form">

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Enable campaigns for owners"); ?></label>
                                  <select id="ENABLE_CAMPAIGNS_FOR_OWNER" name="ENABLE_CAMPAIGNS_FOR_OWNER" class="form-control select2 ENABLE_CAMPAIGNS_FOR_OWNER">
                                      <?php
                                      if($config['ENABLE_CAMPAIGNS_FOR_OWNER']){
                                          echo '<option value="true" selected>true</option>';
                                          echo '<option value="false" >false</option>';
                                      }else{
                                          echo '<option value="true"  >true</option>';
                                          echo '<option value="false"  selected>false</option>';
                                      }
                                      ?>
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Push_campaign_auto","Push campaign auto"); ?>   <span style="color: grey;font-size: 11px;">(<?php echo Translate::sprint("Without_validation_by_admin","Without validation by admin"); ?>)</span></label>
                                  <select id="PUSH_COMPAIGN_AUTO" name="PUSH_COMPAIGN_AUTO" class="form-control select2 PUSH_COMPAIGN_AUTO">
                                      <?php
                                            if($config['PUSH_COMPAIGN_AUTO']){
                                                echo '<option value="true" selected>true</option>';
                                                echo '<option value="false" >false</option>';
                                            }else{
                                                echo '<option value="true"  >true</option>';
                                                echo '<option value="false"  selected>false</option>';
                                            }
                                      ?>
                                  </select>
                              </div>



                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Target_raduis","Target raduis"); ?>  <sup>KM</sup></label>
                                  <input type="number" min="0" max="100" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="RADUIS_TRAGET" id="RADUIS_TRAGET" value="<?=$config['RADUIS_TRAGET']?>">
                              </div>

                              <div class="form-group">
                                  <label> <?=Translate::sprint("Number max pushes per campaign",""); ?> </label>
                                  <input type="number" min="0" max="1000" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="LIMIT_PUSHED_GUESTS_PER_CAMPAIGN" id="LIMIT_PUSHED_GUESTS_PER_CAMPAIGN" value="<?=$config['LIMIT_PUSHED_GUESTS_PER_CAMPAIGN']?>">
                              </div>

                              <div class="form-group">
                                  <label>

                                      <?php

                                      echo Translate::sprint("Use_campaign_with_crontab","Use campaign with crontab"); ?>
                                      <span style="color: grey;font-size: 11px;">(Ex: <?php echo Translate::sprint("you_can_push_10_campaigns_for_every_10_minutes","you can push 10 campaigns for every 10 minutes"); ?>  )</span>

                                  </label>


                                  <select id="PUSH_CAMPAIGNS_WITH_CRON" name="PUSH_CAMPAIGNS_WITH_CRON" class="form-control select2 PUSH_CAMPAIGNS_WITH_CRON">
                                      <?php

                                          if($config['PUSH_CAMPAIGNS_WITH_CRON']){
                                              echo '<option value="true" selected>true</option>';
                                              echo '<option value="false" >false</option>';
                                          }else{
                                              echo '<option value="true"  >true</option>';
                                              echo '<option value="false"  selected>false</option>';
                                          }

                                      ?>
                                  </select>

                                  <BR>
                                  <label>
                                       <span style="color: grey;font-size: 11px;">
                                  <?php
                                            echo  Translate::sprint("set this command in your cronjob")." <BR><CODE> /usr/bin/php -q ".FCPATH."cronjob.php</CODE>";
                                  ?>
                                      </span>
                                  </label>
                              </div>

                              <div class="form-group">
                                  <label><?php echo Translate::sprint("Number_max_pushes_per_campaign_with_cronjob_in_every_execute","Maximum number of pushes  per campaign with crontab in each run"); ?> </label>
                                  <input type="number" min="0" max="100"  class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="NBR_PUSHS_FOR_EVERY_TIME" id="NBR_PUSHS_FOR_EVERY_TIME" value="<?=$config['NBR_PUSHS_FOR_EVERY_TIME']?>">
                              </div>



                              <div class="form-group">
                                  <button type="button" class="btn  btn-primary btnSave" > <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>  </button>
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
                     <h3 class="box-title"><b><?=Translate::sprint("Messages Config")?></b></h3>
                     <div class="box-tools pull-right">
                         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                         </button>
                         <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                     </div>
                 </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                     <div class="col-sm-12">
                         <form class="form" role="form">

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("Enable Messages From Dashboard"); ?> </label>
                                 <select id="ENABLE_MESSAGES" name="ENABLE_MESSAGES" class="form-control select2 ENABLE_MESSAGES">
                                     <?php
                                     if($config['ENABLE_MESSAGES']){
                                         echo '<option value="true" selected>true</option>';
                                         echo '<option value="false" >false</option>';
                                     }else{
                                         echo '<option value="true"  >true</option>';
                                         echo '<option value="false"  selected>false</option>';
                                     }
                                     ?>
                                 </select>
                             </div>

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("Enable chat with firebase"); ?> <span style="color: grey;font-size: 11px;"><?=Translate::sprint("Should be enabled from app client")?></span> </label>
                                 <select id="CHAT_WITH_FIREBASE" name="CHAT_WITH_FIREBASE" class="form-control select2 CHAT_WITH_FIREBASE">
                                     <?php
                                     if($config['CHAT_WITH_FIREBASE']){
                                         echo '<option value="true" selected>true</option>';
                                         echo '<option value="false" >false</option>';
                                     }else{
                                         echo '<option value="true"  >true</option>';
                                         echo '<option value="false"  selected>false</option>';
                                     }
                                     ?>
                                 </select>
                             </div>


                             <div class="form-group">
                                 <label><?php echo Translate::sprint("Allow dashboard messenger to owners"); ?></label>
                                 <select id="ALLOW_DASHBOARS_MESSENGER_TO_OWNERS" name="ALLOW_DASHBOARS_MESSENGER_TO_OWNERS" class="form-control select2 ALLOW_DASHBOARS_MESSENGER_TO_OWNERS">
                                     <?php
                                     if($config['ALLOW_DASHBOARS_MESSENGER_TO_OWNERS']){
                                         echo '<option value="true" selected>true</option>';
                                         echo '<option value="false" >false</option>';
                                     }else{
                                         echo '<option value="true"  >true</option>';
                                         echo '<option value="false"  selected>false</option>';
                                     }
                                     ?>
                                 </select>
                             </div>



                             <div class="form-group">
                                 <button type="button" class="btn  btn-primary btnSave"> <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>  </button>
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
                     <h3 class="box-title"><b><?=Translate::sprint("SMTP Config")?></b></h3>
                     <div class="box-tools pull-right">
                         <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                         </button>
                         <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                     </div>
                 </div>
                 <!-- /.box-header -->
                 <div class="box-body">
                     <div class="col-sm-12">
                         <form class="form" role="form">

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("Enable SMTP SERVER"); ?> </label>
                                 <select id="SMTP_SERVER_ENABLED" name="SMTP_SERVER_ENABLED" class="form-control select2 SMTP_SERVER_ENABLED">
                                     <?php
                                     if($config['SMTP_SERVER_ENABLED']){
                                         echo '<option value="true" selected>true</option>';
                                         echo '<option value="false" >false</option>';
                                     }else{
                                         echo '<option value="true"  >true</option>';
                                         echo '<option value="false"  selected>false</option>';
                                     }
                                     ?>
                                 </select>
                             </div>

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("SMTP Host"); ?></label>
                                 <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="SMTP_HOST" id="SMTP_HOST" value="<?=$config['SMTP_HOST']?>">
                             </div>

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("SMTP Port"); ?></label>
                                 <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="SMTP_PORT" id="SMTP_PORT" value="<?=$config['SMTP_PORT']?>">
                             </div>


                             <div class="form-group">
                                 <label><?php echo Translate::sprint("SMTP user"); ?></label>
                                 <input type="text" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="SMTP_USER" id="SMTP_USER" value="<?=$config['SMTP_USER']?>">
                             </div>

                             <div class="form-group">
                                 <label><?php echo Translate::sprint("SMTP pass"); ?></label>
                                 <input type="password" class="form-control" placeholder="<?=Translate::sprint("Enter")?> ..." name="SMTP_PASS" id="SMTP_PASS" value="<?=$config['SMTP_PASS']?>">
                             </div>


                             <div class="form-group">
                                 <button type="button" class="btn  btn-primary btnSave"> <span class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save","Save"); ?>  </button>
                             </div>

                         </form>
                     </div>
                 </div>
                 <!-- /.box-body -->
             </div>

         </div>



 </section>
    
</div>


<script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js")?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js")?>"></script>


<script>

 $("#addCurrency").on('click',function(){

     var symbol_currency = $(".form #symbol_currency").val();
     var name_currency = $(".form #name_currency").val();

     var dataSet = {
         "symbol_currency":symbol_currency,
         "name_currency":name_currency,
     };

     $.ajax({
         url:"<?=  site_url("ajax/setting/addNewCurrency")?>",
         data:dataSet,
         dataType: 'json',
         type: 'POST',
         beforeSend: function (xhr) {
             $(".form #addCurrency").attr("disabled",true);

         },error: function (request, status, error) {
             alert(request.responseText);
             $(".form #addCurrency").attr("disabled",false);

             console.log(request.responseText);

         },
         success: function (data, textStatus, jqXHR) {
             $(".form #addCurrency").attr("disabled",false);
             if(data.success===1){
                 document.location.reload();
             }
         }
     });

     return false;

 });


    </script>


<script>

    $('.TIME_ZONE').select2();
    $('.PUSH_COMPAIGN_AUTO').select2();
    $('.ENABLE_STORE_AUTO').select2();
    $('.ENABLE_OFFER_AUTO').select2();
    $('.ENABLE_EVENT_AUTO').select2();
    $('.ENABLE_AUTO_HIDDEN_OFFERS').select2();
    $('.PUSH_CAMPAIGNS_WITH_CRON').select2();
    $('.DEFAULT_CURRENCY').select2();
    $('.DEFAULT_LANG').select2();
    $('.EMAIL_VERIFICATION').select2();
    $('.USER_REGISTRATION').select2();
    $('.ENABLE_FRONT_END').select2();

    $('.ENABLE_CAMPAIGNS_FOR_OWNER').select2();

    $('.ENABLE_MESSAGES').select2();
    $('.CHAT_WITH_FIREBASE').select2();

    $('.ALLOW_DASHBOARS_MESSENGER_TO_OWNERS').select2();

    $('.SMTP_SERVER_ENABLED').select2();


    $('.IMAGES_LIMITATION').select2();

    $(".content .btnSave").on('click',function () {

        var selector = $(this);

        var dataSet = {
            <?php

                foreach ($config as $key => $value){

                    if($key!="APP_LOGO")
                        echo '"'.$key.'" : $(".content #'.$key.'").val(),';
                    else
                        echo '"'.$key.'" : fileUploaded,';

                }

            ?>
            "token":""
        };

        $.ajax({
            url:"<?=  site_url("ajax/setting/saveAppConfig")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled",true);
            },error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled",false);

                console.log(request.responseText);

            },
            success: function (data, textStatus, jqXHR) {


                console.log(data);

                selector.attr("disabled",false);
                if(data.success===1){
                    document.location.reload();
                }else if(data.success===0){
                    var errorMsg = "";
                    for(var key in data.errors){
                        errorMsg = errorMsg+data.errors[key]+"\n";
                    }
                    if(errorMsg!==""){
                        alert(errorMsg);
                    }
                }
            }
        });


        return false;
    });



</script>




<script>


    <?php

    $token = $this->mUserBrowser->setToken("SIMAGES-4555");

    ?>

    Uploader(true);

    var fileUploaded = {};
    <?php

        $imagesData = _openDir(APP_LOGO);

        if(!empty($imagesData)){

                $key = $imagesData['name'];
                $item = "item_".$key;
                $data = $imagesData;
                echo "fileUploaded[$key]=$key ;";
        }

    ?>


    $(".image-uploaded #delete").on('click',function(){
        var nameDir = $(this).attr("data");
        delete fileUploaded[nameDir];
        $(".image-uploaded.item_"+nameDir).remove();
        return false;
    });


    
    function Uploader(singleFile){

        $('#fileupload').fileupload({
            url: "<?=site_url("uploader/ajax/uploadImage")?>",
            sequentialUploads: true,
            loadImageFileTypes:/^image\/(gif|jpeg|png|jpg)$/,
            maxFileSize: 28187,
            singleFileUploads: singleFile,
            formData     : {
                'token'     : "<?=$token?>",
                'ID'        : "<?=sha1($token)?>"
            },
            dataType: 'json',
            done: function (e, data) {

                console.log(data)
                var results = data._response.result.results;

                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width":"0%"});
                $(".image-uploaded").removeClass("hidden");

                if(singleFile==true){
                    fileUploaded = {};
                    $("#image-previews").html(results.html);
                }else
                    $("#image-previews").append(results.html);

                fileUploaded[results.image] = results.image;
                //$("#image-data").val(results.image_data);

                $(".image-uploaded #delete").on('click',function(){
                    var nameDir = $(this).attr("data");
                    delete fileUploaded[nameDir];
                    $(".image-uploaded.item_"+nameDir).remove();
                    return false;
                });

            },
            fail:function (e, data) {

                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width":"0%"});

                console.log(data);

            },
            progressall: function (e, data) {

                var progress = parseInt(data.loaded / data.total * 100, 10);

                $("#progress").removeClass("hidden");
                $("#progress .percent").animate({"width":progress+"%"},"linear");

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


<script src="<?=  base_url("views/skin/backend/plugins/colorpicker/bootstrap-colorpicker.js")?>"></script>
<script>
    $('.colorpicker1').colorpicker();
</script>
<?php if(!defined("IOS_PURCHASE_ID") && !defined("IOS_API")): ?>
<script>


    $("#second_verify").on("click",function () {


        var selector = $(this);
        var pid = $("#SPID").val();

        if(pid!=""){

            $.ajax({
                url:"<?=  site_url("ajax/setting/sverify")?>",
                data:{
                    pid: pid
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {
                    selector.attr("disabled",true);
                },error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled",false);
                    console.log(request.responseText);
                },
                success: function (data, textStatus, jqXHR) {
                    <?php if(ENVIRONMENT=="development"): ?>
                    console.log(data);
                    <?php endif; ?>
                    selector.attr("disabled",false);
                    if(data.success===1){
                        document.location.reload();
                    }else if(data.success===0){
                        var errorMsg = "";
                        for(var key in data.errors){
                            errorMsg = errorMsg+data.errors[key]+"\n";
                        }
                        if(errorMsg!==""){
                            alert(errorMsg);
                        }else if(data.error){
                            alert(data.error);
                        }
                    }
                }
            });



        }

        return true;
    });

</script>
<?php endif; ?>




