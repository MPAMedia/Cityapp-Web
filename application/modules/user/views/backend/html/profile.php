<?php


$user_settings_package = $user->user_settings_package;

if (!is_array($user_settings_package))
    $user_settings_package = json_decode($user_settings_package, JSON_OBJECT_AS_ARRAY);


$user_settings_balance = array();


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
                                                "cache"         => $cache,
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
                                            <input type="text" class="form-control" id="email" placeholder="Enter ..."
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
                                        <label><?= Translate::sprint("User type") ?></label><br>
                                        <span class="badge bg-green"><?=Translate::sprint($user->typeAuth)?></span>
                                    </div>

                                    <strong class="uppercase title margin-top15px"><?= Translate::sprint("Consumption") ?></strong>


                                    <?php foreach ($user_settings as $module_name => $field): ?>


                                        <?php

                                        if ($field['_display'] == 0) {
                                            continue;
                                        }




                                        if (!ModulesChecker::isEnabled($field['module'])
                                            OR !GroupAccess::isGranted($field['module'])){
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
                                            echo "This field doesn't displayed";
                                        }
                                        echo '</div>';

                                        ?>


                                    <?php endforeach; ?>

                                </div>

                            </form>

                        </div>
                        <!-- /.box-body -->
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

$script = $this->load->view('backend/html/scripts/profile-script',$data,TRUE);
TemplateManager::addScript($script);

?>


