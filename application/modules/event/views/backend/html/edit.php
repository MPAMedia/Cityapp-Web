<?php

$event = $dataEvents[Tags::RESULT][0];

if ($event['user_id'] != $this->mUserBrowser->getData("id_user")
    && GroupAccess::isGranted('user', USER_ADMIN)) {
    $disabled = "disabled='true'";
} else {
    $disabled = "";
}


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

                <form id="form" role="form">
                    <div class="col-md-6">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b><?= Translate::sprint("Edit event") ?></b></h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                <!-- text input -->

                                <input type="hidden" id="id" value="<?= $event['id_event'] ?>">
                                <div class="form-group required">

                                    <?php

                                    $images = $event['images'];

                                    ?>

                                    <?php

                                    $upload_plug = $this->uploader->plugin(array(
                                        "limit_key" => "aEvFiles",
                                        "token_key" => "SzYUjEsS-4555",
                                        "limit" => MAX_EVENT_IMAGES,
                                        "cache" => $images
                                    ));

                                    echo $upload_plug['html'];
                                    TemplateManager::addScript($upload_plug['script']);

                                    ?>


                                </div>


                                <div class="form-group">
                                    <label><?= Translate::sprint("Store", "") ?></label>
                                    <select class="form-control select2 selectStore"
                                            style="width: 100%;" <?= $disabled ?>>
                                        <option value="0"><?= Translate::sprint("Select store", "") ?></option>
                                        <?php

                                        if (isset($myStores[Tags::RESULT])) {
                                            foreach ($myStores[Tags::RESULT] as $st) {

                                                if ($event['store_id'] != $st['id_store'])
                                                    echo '<option adr="' . $st['address'] . '" 
                                        lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                        value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                                else {
                                                    echo '<option selected adr="' . $st['address'] . '" 
                                        lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                        value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                                }
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Event name", "") ?> : </label>
                                    <input <?= $disabled ?> type="text" class="form-control"
                                                            value="<?= $event['name'] ?>"
                                                            placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                            name="name" id="name">

                                </div>


                                <!-- textarea -->
                                <div class="form-group">
                                    <label><?= Translate::sprint("Description", "") ?> :</label>
                                    <textarea <?= $disabled ?> id="editable-textarea" class="form-control"
                                                               style="height: 300px"><?= $event['description'] ?></textarea>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6"><label><?= Translate::sprint("Date Begin", "") ?>
                                                : </label>
                                            <input <?= $disabled ?> class="form-control" data-provide="datepicker"
                                                                    value="<?php $dat_1 = date_create($event['date_b']);
                                                                    echo date_format($dat_1, 'd-m-Y') ?>"
                                                                    placeholder="DD-MM-YYYY"
                                                                    type="text" name="date_b" id="date_b"/></div>
                                        <div class="col-md-6"><label><?= Translate::sprint("Date End ", "") ?>
                                                : </label>
                                            <input <?= $disabled ?> class="form-control" data-provide="datepicker"
                                                                    value="<?php $dat_2 = date_create($event['date_e']);
                                                                    echo date_format($dat_2, 'd-m-Y') ?>" type="text"
                                                                    placeholder="DD-MM-YYYY" name="date_e" id="date_e"/>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label><?= Translate::sprint("Phone Number", "") ?> </label>
                                    <input <?= $disabled ?> type="text" class="form-control"
                                                            value="<?= $event['tel'] ?>"
                                                            placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                            name="tel" id="tel">
                                </div>


                                <div class="form-group">
                                    <label><?= Translate::sprint("WebSite", "") ?></label>
                                    <br>
                                    <sup><span><?=Translate::sprint("Enter a valid URL with http or https")?></sup>
                                    <input <?= $disabled ?> type="text" class="form-control"
                                                            value="<?= $event['website'] ?>"
                                                            placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                            name="web" id="web">
                                </div>


                            </div>
                            <!-- /.box-body -->
                        </div>


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
                                    if (intval($event['featured']) == 0)
                                        $checked0 = " checked='checked'";

                                    $checked = "";
                                    if (intval($event['featured']) == 1)
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
                    <div class="col-md-6">


                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <b><?= Translate::sprint("Drag the marker to get the exact position", "") ?> :</b>
                                </h3>
                            </div>

                            <div class="box-body">

                                <div class="form-group">
                                    <label> <?= Translate::sprint("Search", "") ?> :</label>
                                    <input <?= $disabled ?> type="text" class="form-control"
                                                            placeholder="<?= Translate::sprint("Search") ?> ..."
                                                            name="places" id="places">
                                </div>
                                <div id="somecomponent" style="width:100%;height:500px;margin-bottom: 10px;"></div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Address") ?> :</label>
                                    <input <?= $disabled ?> type="text" class="form-control"
                                                            value="<?= trim($event['address']) ?>"
                                                            placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                            name="address" id="address">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6"><label><?= Translate::sprint("Lat", "") ?> : </label>
                                            <input
                                                <?= $disabled ?> class="form-control" value="<?= $event['lat'] ?>"
                                                                 type="text" name="lat"
                                                                 id="lat"/></div>
                                        <div class="col-md-6"><label><?= Translate::sprint("Lng", "") ?> : </label>
                                            <input
                                                <?= $disabled ?> class="form-control" value="<?= $event['lng'] ?>"
                                                                 type="text" name="long"
                                                                 id="lng"/></div>

                                    </div>
                                </div>

                            </div>
                            <div class="box-footer">
                                <?php if ($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                    <button type="button" class="btn  btn-primary" id="btnSave"><span
                                                class="glyphicon glyphicon-check"></span>
                                        <?= Translate::sprint("update", "") ?> </button>
                                    <button type="reset" class="btn  btn-default"><span
                                                class="glyphicon glyphicon-remove"></span>
                                        <?= Translate::sprint("Clear", "") ?> </button>
                                <?php } ?>
                            </div>

                        </div>

                    </div>
                </form>
        </section>

    </div>
<?php


$data['event'] = $event;
$data['uploader_variable'] = $upload_plug['var'];

$script = $this->load->view('backend/html/scripts/edit-script', $data, TRUE);
TemplateManager::addScript($script);

?>