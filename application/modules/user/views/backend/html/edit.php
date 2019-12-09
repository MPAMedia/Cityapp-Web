<?php

$user_settings_package = $user->user_settings_package;

if (!is_array($user_settings_package))
    $user_settings_package = json_decode($user_settings_package, JSON_OBJECT_AS_ARRAY);


$user_settings_balance = array();

$callback = admin_url("user/edit?id=".$user->id_user);
$this->session->set_userdata(array(
        'user_edit_callback' => $callback
));

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
                                <h3 class="box-title"><strong><?= Translate::sprint("Edit user information") ?></strong></h3>
                            </div>
                            <!-- /.box-header -->
                            <form id="form1">
                                <div class="box-body">
                                    <div class="col-sm-12">


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

                                                <?php

                                                    $cache = array();

                                                    if (!empty($images)) {

                                                        foreach ($images as $value) {
                                                            $item = "item_" . $value;
                                                            $imagesData = _openDir($value);
                                                            $cache = array($imagesData);
                                                            break;
                                                        }

                                                    }


                                                    $upload_plug = $this->uploader->plugin(array(
                                                        "limit_key"     => "uAhFiles",
                                                        "token_key"     => "SzqYjES-4555",
                                                        "limit"         => 1,
                                                        "cache"         => $cache
                                                    ));

                                                    echo $upload_plug['html'];
                                                    TemplateManager::addScript($upload_plug['script']);

                                                ?>

                                            </div>

                                            <div class="form-group">
                                                <label><?= Translate::sprint("Full name") ?> <sup class="text-red">*</sup> :</label>
                                                <input type="text" class="form-control" placeholder="Enter ..."
                                                       name="name" id="name" value="<?= $user->name ?>">
                                            </div>

                                            <div class="form-group">
                                                <label><?= Translate::sprint("Email") ?>  <sup class="text-red">*</sup> :</label>
                                                <input type="text" id="email" class="form-control" placeholder="Enter ..."
                                                       value="<?= $user->email ?>">
                                            </div>


                                            <div class="form-group">
                                                <label><?= Translate::sprint("Username") ?> <sup class="text-red">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="Enter ..."
                                                       name="username" id="username" value="<?= $user->username ?>">
                                            </div>

                                            <div class="form-group">
                                                <label><?= Translate::sprint("Phone") ?> :</label>
                                                <input type="text" class="form-control" id="phone" placeholder="Enter ..."
                                                       value="<?= trim($user->telephone) ?>">
                                            </div>

                                            <strong class="uppercase title margin-top15px"><?=Translate::sprint("Change Password")?></strong>

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
                                    </div>
                                </div>
                                <div class="box-footer">
                                    

                                <a type="button" class="btn btn-flat btn-primary pull-right btnSave">
                                    <i class="mdi mdi-content-save-outline"></i> <?= Translate::sprint("Save") ?>
                                </a>

                                    <?php if($user->confirmed==0): ?>
                                        <a class="pull-right btn btn-flat btn-default linkAccess" href="<?= site_url("ajax/user/confirm?id=" . $user->id_user) ?>"
                                           onclick="return false;">
                                            <i class="mdi mdi-check"></i>&nbsp;&nbsp;<?=Translate::sprint("Confirm")?>
                                        </a>
                                        <a class="pull-right btn btn-flat btn-default" href="<?= admin_url("user/resendMail?id=" . $user->id_user) ?>">
                                            <i class="mdi mdi-check"></i>&nbsp;&nbsp;<?=Translate::sprint("Re-send Confirmation Mail")?>
                                        </a>
                                    <?php endif; ?>

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

                            <form id="form2">
                                <div class="box-body margin">
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Access Role") ?> <sup
                                                    class="text-red">*</sup></label>
                                        <select id="typeAuth" name="typeAuth" class="form-control select2">
                                            <option value="0"><?= Translate::sprint("Select user type") ?></option>
                                            <?php foreach ($grp_accesses as $grp): ?>
                                                <option value="<?= $grp['id'] ?>"><?= Translate::sprint($grp['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <?php if (!ModulesChecker::isEnabled("pack")): ?>


                                        <strong class="uppercase title margin-top15px"><?= Translate::sprint("Consumption") ?></strong>


                                            <?php foreach ($user_settings as $field): ?>


                                                    <?php

                                                    if ($field['_display'] == 0){
                                                        continue;
                                                    }


                                                    echo ' <div class="form-group">';

                                                    if (isset($user_settings_package[$field['field_name']])) {


                                                        $val_pack_setting = $user_settings_package[$field['field_name']];
                                                        $val_user_setting = $user->{$field['field_name']};

                                                        $user_settings_balance[$field['field_name']] = $val_user_setting;

                                                        if ($field['field_type'] == UserSettingSubscribeTypes::INT) {


                                                            echo '<div class="progress-group">';
                                                            echo '<span class="progress-text">' . ucfirst(Translate::sprint($field['field_name'])) . '</span>';

                                                            if(!ModulesChecker::isEnabled($field['module'])
                                                                OR !GroupAccess::isGrantedUser($user->id_user,$field['module'])){
                                                                echo '<br>
                                                                        <sup class="text-orange"><i class="mdi mdi-alert"></i>&nbsp;&nbsp;'.Translate::sprint('this user doesn\'t have privilege to use this option').'</sup>';
                                                            }

                                                            if ($val_user_setting == -1) {

                                                                echo '<span class="progress-number">∞</span>';
                                                                echo '<div class="progress sm">
                                                                                <div class="progress-bar progress-bar-green" style="width: 100%"></div>
                                                                            </div>';

                                                            } else {


                                                                if ($val_user_setting > 0)
                                                                    $t = $val_user_setting / $val_pack_setting;
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

                                                                echo '<span class="progress-number"><b>' . $val_user_setting . '</b>' . '/' . $val_pack_setting . '</span>';
                                                                echo '<div class="progress sm">
                                                                            <div class="progress-bar progress-bar-' . $color . '" style="width: ' . $progress . '%"></div>
                                                                        </div>';

                                                            }

                                                            echo '</div>';


                                                        } else if ($field['field_type'] == UserSettingSubscribeTypes::BOOLEAN) {
                                                            if ($val_user_setting == 1) {
                                                                echo '<label>' . ucfirst(Translate::sprint($field['field_name'])) . '</label>: ' . '<i class="mdi mdi-check-circle text-green">&nbsp;' . Translate::sprint("Enabled") . '</i>';
                                                            } else {
                                                                echo '<label>' . ucfirst(Translate::sprint($field['field_name'])) . '</label>: ' . '<i class="mdi mdi-close-circle text-red">&nbsp;' . Translate::sprint("Disabled") . '</i>';
                                                            }
                                                        }


                                                    } else {
                                                        echo "N/A This field doesn't displayed";
                                                    }
                                                    echo '</div>';

                                                    ?>


                                            <?php endforeach; ?>


                                        <label id="customize_subscription" class="uppercase title margin-top15px">
                                            <input type="checkbox" id="is_free_check" class="minimal">
                                            &nbsp;&nbsp;<strong><?= Translate::sprint("Customize Subscription") ?></strong>
                                            &nbsp;&nbsp;
                                        </label>

                                        <?php foreach ($user_settings as $field): ?>

                                            <?php
                                            if ($field['_display'] == 0)
                                                continue;
                                            ?>
                                            <div class="form-group customize_subscription">
                                                <label><?= Translate::sprint($field['field_label']) ?>
                                                    <?php if ($field['field_sub_label'] != ""): ?>
                                                        &nbsp;<span
                                                                class="font-size10px text-grey2"><?= Translate::sprint($field['field_sub_label']) ?></span>
                                                    <?php endif; ?>
                                                </label>

                                                <?php if(!ModulesChecker::isEnabled($field['module'])
                                                    OR !GroupAccess::isGrantedUser($user->id_user,$field['module'])): ?>
                                                <br>
                                                <sup class="text-orange"><i class="mdi mdi-alert"></i>&nbsp;&nbsp;<?=Translate::sprint('this user doesn\'t have privilege to use this option')?></sup>
                                                <?php endif; ?>

                                                <?php if ($field['field_comment']): ?>
                                                    <br>
                                                    <sup><i class="mdi mdi-information-outline"></i> <?= Translate::sprint($field['field_comment']) ?>
                                                    </sup>
                                                <?php endif; ?>

                                                <?php if ($field['field_type'] == UserSettingSubscribeTypes::INT
                                                    OR $field['field_type'] == UserSettingSubscribeTypes::DOUBLE): ?>

                                                    <input type="number" min="-1" max="100" class="form-control"
                                                           placeholder="<?= Translate::sprint($field['field_placeholder']) ?>"
                                                           name="<?= $field['config_key'] ?>"
                                                           id="<?= $field['config_key'] ?>"
                                                           value="<?= $config[$field['config_key']] ?>" disabled>

                                                <?php elseif ($field['field_type'] == UserSettingSubscribeTypes::BOOLEAN): ?>

                                                    <select class="form-control select2"
                                                            id="<?= $field['config_key'] ?>" disabled>
                                                        <?php if ($field['field_placeholder'] != ""): ?>
                                                            <option value="0"><?= Translate::sprint($field['field_placeholder']) ?></option>
                                                        <?php endif; ?>
                                                        <option value="true" <?php if ($config[$field['config_key']] == 1) echo 'selected' ?>><?= Translate::sprint('Enabled') ?></option>
                                                        <option value="false" <?php if ($config[$field['config_key']] == 0) echo 'selected' ?>><?= Translate::sprint('Disabled') ?></option>
                                                    </select>

                                                <?php elseif ($field['field_type'] == UserSettingSubscribeTypes::VARCHAR): ?>

                                                    <input type="text" min="-1" max="100" class="form-control"
                                                           placeholder="<?= Translate::sprint("Enter") ?>"
                                                           name="<?= $field['config_key'] ?>"
                                                           id="<?= $field['config_key'] ?>"
                                                           value="<?= $config[$field['config_key']] ?>">

                                                <?php endif; ?>


                                            </div>
                                        <?php endforeach; ?>


                                    <?php endif; ?>

                                </div>

                                <div class="box-footer">
                                    <a type="button" class="btn btn-flat btn-primary pull-right btnSave">
                                        <i class="mdi mdi-content-save-outline"></i> <?= Translate::sprint("Save") ?>
                                    </a>
                                </div>
                            </form>

                        </div>
                        <!-- /.box-body -->
                        <?php if (ModulesChecker::isEnabled("pack")): ?>
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><strong><?= Translate::sprint("Subscription Pack") ?></strong>
                                    </h3>
                                </div>

                                <div class="box-body">

                                    <div class="form-group margin">

                                        <?php if ($this->mUserBrowser->getData("manager") == 0): ?>

                                            <?php

                                                $this->load->model("pack/pack_model");
                                                $pack = $this->pack_model->getAccountPack();


                                                if ($pack != NULL) {

                                                    echo '<label>' . Translate::sprint("Pack name") . '</label>';
                                                    echo "<br><span class='badge bg-yellow'>" . $pack->name . "</span>";

                                                    if ($this->mPack->canUpgrade()) {
                                                        echo "&nbsp;&nbsp;-&nbsp;&nbsp;";
                                                        echo "<u><a href='" . site_url("pack/pickpack?req=upgrade") . "'>" . Translate::sprint("Upgrade") . "</a></u>";
                                                    }


                                                    if (!$this->mPack->isRenewal()) {

                                                        $expired_date = $this->mUserBrowser->getData('will_expired');
                                                        $days = MyDateUtils::getDays($expired_date);

                                                        if ($days < 7) {
                                                            echo Translate::sprintf("Your pack will be expired soon after %s days", array($days));
                                                            echo '&nbsp;<a href="' . admin_url("pack/renew") . '">' . Translate::sprint("Renew") . '</a>';
                                                        } else {
                                                            echo "&nbsp;&nbsp;" . Translate::sprintf("Will be expired at ( %s )", array(
                                                                    MyDateUtils::convert($user->will_expired, "UTC", TIME_ZONE, "d, M Y")
                                                                ));
                                                        }

                                                    } else {
                                                        echo "<strong class='text-red'>" . Translate::sprint("Your account was expired") . "</strong>&nbsp;&nbsp;&nbsp;";
                                                        echo "<strong class='text-red'><u><a href='" . admin_url("pack/renew") . "'>" . Translate::sprint("Renew now") . "</a></u></strong>";
                                                    }

                                                } else {

                                                    echo '<label><i class="text-orange mdi mdi-alert"></i>&nbsp;&nbsp;' . Translate::sprint("Don't have pack") . '</label>';
                                                    echo '<br><a href="' . site_url("pack/pickpack") . '"><u>' . Translate::sprint("Select a pack") . '</u></a>';

                                                }

                                            ?>


                                        <?php elseif ($this->mUserBrowser->getData("manager") == 1): ?>

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


    <?php if (ModulesChecker::isEnabled("pack")): ?>

        <div class="modal fade" id="modal-default-pack">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= Translate::sprint("Pack Confirmation") ?></h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group pull-right">
                                    <label><?= Translate::sprint('Duration') ?></label>:&nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select id="confirm_pack" name="confirm_pack"
                                            class="form-control select2">
                                        <option value="1"><?= Translate::sprint("1 Month") ?></option>
                                        <option value="12"><?= Translate::sprint("1 Year") ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="_select"
                                class="btn btn-flat btn-primary pull-right"><?= Translate::sprint("Confirm & Save") ?></button>
                        <button type="button" class="btn btn-flat btn-default pull-right"
                                data-dismiss="modal"><?= Translate::sprint("Cancel") ?></button>
                    </div>
                </div>

                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    <?php endif; ?>


</div>

<?php

    $data['user'] = $user;

    $data['user_settings'] = $user_settings;
    $data['user_settings_balance'] = $user_settings_balance;

    $data['uploader_variable'] = $upload_plug['var'];

    $script = $this->load->view('backend/html/scripts/edit-script',$data,TRUE);
    TemplateManager::addScript($script);

?>


