<?php

$list = $campaigns[Tags::RESULT];
$pagination = $campaigns["pagination"];


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-5">
                <form id="form">
                    <div class="box box-solid">
                        <div class="box-header">

                            <div class="box-title">
                                <b><i class="mdi mdi-bullseye"></i>&nbsp;&nbsp;
                                    <?=Translate::sprint("Create new campaign","")?></b>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="callout callout-info">
                              <p> <?=Translate::sprint("Alert_compaign","")?></p>
                            </div>


                            <div class="form-group">
                                <label><?=Translate::sprint("Type campaign","")?> <sup>*</sup></label>
                                <select class="form-control select2 selectCType" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select campaign type","")?></option>
                                    <?php

                                        foreach (json_decode(CAMPAIGN_TYPES,JSON_OBJECT_AS_ARRAY) as $value){
                                            echo " <option value=\"$value\">".Translate::sprint(ucfirst($value))."</option>";
                                        }

                                    ?>

                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-store hidden">
                                <label><?=Translate::sprint("Store","")?></label>
                                <select class="form-control select2 selectStore" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select store","")?></option>
                                    <?php

                                        if(isset($myStores[Tags::RESULT])){
                                            foreach ($myStores[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_store'].'">'.$st['name'].'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-event hidden">
                                <label><?=Translate::sprint("Event","")?></label>
                                <select class="form-control select2 selectEvent" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select event","")?></option>
                                    <?php

                                        if(isset($myEvents[Tags::RESULT])){
                                            foreach ($myEvents[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_event'].'">'.$st['name'].'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group drop-box drop-box-offer hidden">
                                <label><?=Translate::sprint("Offer","")?></label>


                                <select class="form-control select2 selectOffer" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select offer","")?></option>
                                    <?php

                                        if(isset($myOffers[Tags::RESULT])){
                                            foreach ($myOffers[Tags::RESULT] as $st){
                                                echo '<option value="'.$st['id_offer'].'">'.$st['name']." - ".$this->mStoreModel->getStoreName($st['store_id']).'</option>';
                                            }
                                        }

                                    ?>
                                </select>
                            </div>


                            <div class="form-group box-estimation hidden">
                                <label>
                                    <?=Translate::sprint("Targeting estimation","")?></label>
                                <br>
                                <span> <?=Translate::sprintf("This campaign will be displayed to %s customers within %s KM",array("<span class=\"target_value\">0</span>",RADUIS_TRAGET),"")?> </span>

                            </div>


                            <div class="form-group name hidden">

                                <label> <?=Translate::sprint("Title","")?></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Ex: campaign_for_black_friday">

<!--
                                <?php if(kdefined("IOS_API") and kdefined("ANDROID_API")): ?>
                                    <br>
                                    <label> <?=Translate::sprint("Specify the platforms")?></label>
                                    <br>
                                    <label><input class="checkplatform" value="android" type="checkbox" checked/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label><input type="checkbox" class="checkplatform" value="ios" checked/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>
                                <?php else: ?>

                                    <?php if(kdefined("IOS_API")): ?>

                                        <br>
                                        <label> <?=Translate::sprint("Specify the platforms")?></label>
                                        <br>
                                        <label><input type="checkbox" class="checkplatform" value="android" disabled/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="checkbox" class="checkplatform" value="ios" checked/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>

                                    <?php else: ?>


                                        <br>
                                        <label> <?=Translate::sprint("Specify the platforms")?></label>
                                        <br>
                                        <label><input type="checkbox" class="checkplatform" value="android" checked/>&nbsp;&nbsp;<?=Translate::sprint("Android")?></label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="checkbox" class="checkplatform" value="ios" disabled/>&nbsp;&nbsp;<?=Translate::sprint("iOS")?></label>


                                    <?php endif; ?>

                                <?php endif; ?>

                                -->
                            </div>


                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">

                            <?php

                            $usr_id = $this->mUserBrowser->getData('id_user');
                            $nbr_campaign_monthly = UserSettingSubscribe::getUDBSetting($usr_id,KS_NBR_CAMPAIGN_MONTHLY);

                            ?>

                            <?php if($nbr_campaign_monthly>0 or $nbr_campaign_monthly==-1): ?>
                                <button type="button" class="btn  btn-primary" id="btnCreate" > <span class="fa fa-paper-plane-o"></span>
                                <?=Translate::sprint("Push","")?> </button>
                            <?php elseif($nbr_campaign_monthly==0): ?>
                                <button type="button" class="btn  btn-primary" id="btnCreate" disabled> <span class="fa fa-paper-plane-o"></span>
                                    <?=Translate::sprint("Push","")?> </button>
                                &nbsp;&nbsp;
                                <span class="text-red font-size12px"><i class="mdi mdi-information-outline"></i>&nbsp;<?=Translate::sprint(Messages::EXCEEDED_MAX_NBR_CAMPAIGNS)?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
                <?php if(GroupAccess::isGranted('user',USER_ADMIN)): ?>
                <form id="test">
                    <div class="box box-solid">
                        <div class="box-header">
                            <div class="box-title">
                                <b><i class="mdi mdi-bullseye"></i>&nbsp;&nbsp;
                                    <?=Translate::sprint("Test campaigns","")?></b>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="callout callout-warning">
                                <p><?=Translate::sprint("To test the campaign in debug mode, you must be sure that at least one shop, event or offer is created")?></p>
                            </div>
                            <div class="form-group">
                                <label><?=Translate::sprint("Type campaign","")?> <sup>*</sup></label>


                                <select class="form-control select2 selectTestCType" style="width: 100%;">
                                    <option selected="selected" value="0">
                                        <?=Translate::sprint("Select campaign type","")?></option>

                                    <?php

                                        foreach (json_decode(CAMPAIGN_TYPES,JSON_OBJECT_AS_ARRAY) as $value){
                                            echo " <option value=\"$value\">".Translate::sprint(ucfirst($value))."</option>";
                                        }

                                    ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label> <?=Translate::sprint("Guest IDs","")?></label>
                                <input type="text" class="form-control" name="gids" id="gids" placeholder="Ex: 1,2,3...">
                            </div>



                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" class="btn  btn-primary" id="btnTest" > <span class="fa fa-paper-plane-o"></span>
                                <?=Translate::sprint("Push","")?> </button>
                        </div>

                    </div>
                    <!-- /.box -->
                </form>
                <?php endif; ?>
            </div>



            <?php

                $data['list'] = $list;
                $data['pagination'] = $pagination;
                $this->load->view("backend/html/campaigns",$data);

            ?>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

    $script = $this->load->view('backend/html/scripts/add-script',$data,TRUE);
    TemplateManager::addScript($script);

?>

