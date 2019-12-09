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
                        <h3 class="box-title"><b><?php echo Translate::sprint("Type & Subscription"); ?></b></h3>
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
                                    <label><?= Translate::sprint("Default Group Access Registration") ?></label>
                                    <br><sup><i class="mdi mdi-information-outline"></i> <?=Translate::sprint('Associate a user to the access group on registration')?></sup>
                                    <select id="DEFAULT_USER_GRPAC" name="DEFAULT_USER_GRPAC"
                                            class="form-control select2 DEFAULT_USER_GRPAC">
                                        <option value="0"><?= Translate::sprint("Select default type") ?></option>
                                        <?php foreach ($grp_accesses as $grp): ?>
                                            <option value="<?= $grp['id'] ?>"><?= $grp['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>



                                <div class="form-group">
                                    <label><?= Translate::sprint("User Mobile Default Group Access") ?> <sup></sup></label>
                                    <br><sup><i class="mdi mdi-information-outline"></i> <?=Translate::sprint('Associate a user to the access group on creating new account from mobile app')?></sup>
                                    <select id="DEFAULT_USER_MOBILE_GRPAC" name="DEFAULT_USER_MOBILE_GRPAC"
                                            class="form-control select2 DEFAULT_USER_MOBILE_GRPAC">
                                        <option value="0"><?= Translate::sprint("Select type") ?></option>
                                        <?php foreach ($grp_accesses as $grp): ?>
                                            <option value="<?= $grp['id'] ?>"><?= $grp['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                                <strong class="uppercase title margin-top15px"><?=Translate::sprint("Subscription")?></strong>
                                <sup><i class="mdi mdi-information-outline"></i> <?=Translate::sprint('Limit a user to manage a certain amount of content on the dashboard')?></sup>

                                <?php foreach ($user_subscribe_fields as $field): ?>

                                    <?php
                                        if($field['_display']==0)
                                            continue;
                                    ?>
                                    <div class="form-group">
                                        <label><?=Translate::sprint($field['field_label'])?>
                                            <?php if($field['field_sub_label']!=""): ?>
                                                &nbsp;<span class="font-size10px text-grey2"><?=Translate::sprint($field['field_sub_label'])?></span>
                                            <?php endif; ?>
                                        </label>

                                        <?php if($field['field_comment']): ?>
                                            <br><sup><i class="mdi mdi-information-outline"></i> <?=Translate::sprint($field['field_comment'])?></sup>
                                        <?php endif; ?>

                                                <?php if($field['field_type']==UserSettingSubscribeTypes::INT
                                                    OR $field['field_type']==UserSettingSubscribeTypes::DOUBLE):?>

                                                    <input type="number" min="-1" max="100" class="form-control"
                                                           placeholder="<?= Translate::sprint($field['field_placeholder']) ?>" name="<?=$field['config_key']?>"
                                                           id="<?=$field['config_key']?>" value="<?= $config[$field['config_key']] ?>">

                                               <?php elseif($field['field_type']==UserSettingSubscribeTypes::BOOLEAN): ?>

                                                    <select class="form-control select2" id="<?=$field['config_key']?>">
                                                        <?php if($field['field_placeholder']!=""): ?>
                                                        <option value="0"><?= Translate::sprint($field['field_placeholder']) ?></option>
                                                        <?php endif; ?>
                                                        <option value="true" <?php if($config[$field['config_key']]==1) echo 'selected'?>><?=Translate::sprint('Enabled')?></option>
                                                        <option value="false" <?php if($config[$field['config_key']]==0) echo 'selected'?>><?=Translate::sprint('Disabled')?></option>
                                                    </select>

                                               <?php elseif($field['field_type']==UserSettingSubscribeTypes::VARCHAR): ?>

                                                    <input type="text" min="-1" max="100" class="form-control"
                                                           placeholder="<?= Translate::sprint("Enter") ?>" name="<?=$field['config_key']?>"
                                                           id="<?=$field['config_key']?>" value="<?= $config[$field['config_key']] ?>">

                                               <?php endif; ?>


                                    </div>
                                <?php endforeach; ?>

                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn  btn-primary btnSave"><span
                                    class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?php echo Translate::sprint("Mail & Registration", ""); ?></b></h3>
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
                                    <label><?php echo Translate::sprint("Email_verificaion", "Email verification"); ?>   </label>
                                    <br><sup class="text-blue"><i class="mdi mdi-information-outline"></i> <?=Translate::sprint('You can customize the mail template inside this file "mailing/templates/emailconfirm.html"')?></sup>
                                    <select id="EMAIL_VERIFICATION" name="EMAIL_VERIFICATION"
                                            class="form-control select2 EMAIL_VERIFICATION">
                                        <?php
                                        if ($config['EMAIL_VERIFICATION']) {
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
                                    <label><?php echo Translate::sprint("Welcome message"); ?> <span
                                                style="color: grey;font-size: 11px">
                                  <?= Translate::sprint("Optional field") ?></span></label>
                                    <textarea id="MESSAGE_WELCOME" class="form-control" rows="3"
                                              placeholder="<?= Translate::sprint("Enter") ?> ..."><?= $config['MESSAGE_WELCOME'] ?></textarea>
                                </div>


                                <div class="form-group">
                                    <label><?php echo Translate::sprint("Enable user (owner) registration"); ?>   </label>
                                    <select id="USER_REGISTRATION" name="USER_REGISTRATION"
                                            class="form-control select2 USER_REGISTRATION">
                                        <?php
                                        if ($config['USER_REGISTRATION']) {
                                            echo '<option value="true" selected>true</option>';
                                            echo '<option value="false" >false</option>';
                                        } else {
                                            echo '<option value="true"  >true</option>';
                                            echo '<option value="false"  selected>false</option>';
                                        }
                                        ?>
                                    </select>

                                </div>


                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn  btn-primary btnSave"><span
                                    class="glyphicon glyphicon-check"></span><?php echo Translate::sprint("Save", "Save"); ?>
                        </button>
                    </div>
                </div>
            </div>
    </section>

</div>

<?php

    $data['user_subscribe_fields'] = $user_subscribe_fields;
    $script = $this->load->view('backend/html/scripts/user-setting-script',$data,TRUE);
    TemplateManager::addScript($script);

?>

