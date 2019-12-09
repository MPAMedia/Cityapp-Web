<?php


$events = $data[Tags::RESULT];

$pagination = $data['pagination'];

$status = intval($this->input->get("status"));
$typeAuth =$this->mUserBrowser->getData("typeAuth");
if ($status > 0 && $typeAuth == "admin" )
    $statusName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-green'>&nbsp;" . Translate::sprint("My events") . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . admin_url("event/events") . "'>x</a>&nbsp;</span>";
else
    $statusName = "";


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12">

                <div class="box  box-solid">
                    <div class="box-header">
                        <div class="box-title" style="width : 100%;">
                            <div class=" row ">
                                <div class="pull-left col-md-8">
                                    <b><?= Translate::sprint("Events") ?></b><?= $statusName ?>
                                </div>
                                <div class="pull-right col-md-4">

                                    <a href="<?= admin_url("event/create") ?>">
                                        <button type="button" title="<?= Translate::sprint("Create new store", "") ?>"
                                                class="btn btn-primary btn-sm pull-right"><span
                                                class="glyphicon glyphicon-plus"></span></button>
                                    </a>

                                    <form method="get" action="<?= admin_url("event/events") ?>">

                                        <div class="input-group input-group-sm">
                                            <input class="form-control" size="30" name="search" type="text"
                                                   placeholder="<?= Translate::sprint("Search") ?>"
                                                   value="<?= htmlspecialchars($this->input->get("search")) ?>">
                                            <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-flat"><i
                                            class="mdi mdi-magnify"></i></button>
                                </span>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body  table-responsive">
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <!--<th>ID</th>-->
                                    <th><?= Translate::sprint("Image") ?></th>
                                    <th><?= Translate::sprint("Name") ?></th>
                                    <th><?= Translate::sprint("Name AR") ?></th>
                                    <th><?= Translate::sprint("Owner") ?></th>
                                    <th><?= Translate::sprint("Date") ?></th>
                                    <th><?= Translate::sprint("Status") ?></th>
                                    <th><?= Translate::sprint("Action") ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if (!empty($events)) { ?>

                                    <?php foreach ($events AS $event) { ?>


                                        <?php

                                            $token = $this->mUserBrowser->setToken(Text::encrypt($event['id_event']));

                                        ?>
                                        <tr id="offre" class="store_<?= $token ?>" role="row" class="odd">

                                            <td>
                                                <?php

                                                try {

                                                    if (!is_array($event['images']))
                                                        $images = json_decode($event['images'], JSON_OBJECT_AS_ARRAY);
                                                    else
                                                        $images = $event['images'];


                                                    if (isset($images[0])) {
                                                        $images = $images[0];
                                                        if (isset($images['100_100']['url'])) {
                                                            echo '<img src="' . $images['100_100']['url'] . '"width="50" height="50" alt="Product Image">';
                                                        } else {
                                                            echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                                        }
                                                    } else {
                                                        echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                                    }

                                                } catch (Exception $e) {
                                                    $e->getMessage();
                                                    echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                                }

                                                ?>
                                            </td>


                                            <td>
                                                <span style="font-size: 14px"><?=Text::output($event['name'])?></span>
                                                <?php if($event['featured']==1): ?>
                                                    &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                                <?php endif;?><br>
                                                <i class="mdi mdi-map-marker"></i>&nbsp;&nbsp;
                                                <span style="font-size: 11px"><?=  Text::output($event['address'])?></span>
                                            </td>
                                               <td>
                                                <span style="font-size: 14px"><?=Text::output($event['name_ar'])?></span>
                                                <?php if($event['featured']==1): ?>
                                                    &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                                <?php endif;?><br>
                                                <i class="mdi mdi-map-marker"></i>&nbsp;&nbsp;
                                                <span style="font-size: 11px"><?=  Text::output($event['address'])?></span>
                                            </td>

                                            <td>
                                                <a style="font-size: 11px"><u><?= ucfirst($this->mUserModel->getUserNameById($event['user_id'])) ?></u></a>
                                            </td>
                                            <td>
                                                <span style="font-size: 12px;">
                                                    <?php


                                                    $current = date("Y-m-d H:i:s",time());
                                                    $currentData = $current;
                                                    $event['date_b'] = MyDateUtils::convert($event['date_b'],"UTC",TIME_ZONE,"Y-m-d H:i:s");
                                                    $event['date_e'] = MyDateUtils::convert($event['date_e'],"UTC",TIME_ZONE,"Y-m-d H:i:s");

                                                    $currentData = date_create($currentData);
                                                    $dateStart = date_create($event['date_b']);
                                                    $dateEnd = date_create($event['date_e']);

                                                    $differenceStart = $currentData->diff($dateStart);
                                                    $differenceEnd = $currentData->diff($dateEnd);

                                                    $diff_millseconds_start = strtotime($event['date_b']) - strtotime($current);
                                                    $diff_millseconds_end = strtotime($event['date_e']) - strtotime($current);


                                                    echo Translate::sprint("Start").": ".$event['date_b']."<br>";
                                                    echo Translate::sprint("End").": ".$event['date_e']."<br>";
                                                    if($diff_millseconds_start>0){
                                                        echo "<i class=\"mdi mdi-history\"></i> ".Translate::sprint("Start after").": ".MyDateUtils::format_interval($differenceStart);
                                                    }else if($diff_millseconds_start<0 && $diff_millseconds_end>0){
                                                        echo "<i class=\"mdi mdi-history\"></i> ".Translate::sprint("End after").": ".MyDateUtils::format_interval($differenceEnd);

                                                    }

                                                    ?>
                                                </span>

                                            </td>
                                            <td>


                                                <?php

                                                if ($event['status'] == 0)
                                                    echo '<span class="badge bg-red"><i class="mdi mdi-history"></i> &nbsp;'.Translate::sprint("Disabled").'&nbsp;&nbsp;</span>';
                                                else if ($event['status'] == 1) {

                                                    if ($diff_millseconds_start>0) {
                                                        echo '<span class="badge bg-green"><i class="mdi mdi-history"></i> &nbsp;'.Translate::sprint("Published","").'&nbsp;&nbsp;</span>';
                                                    } else if($diff_millseconds_start<0 && $diff_millseconds_end>0) {
                                                        echo '<span class="badge bg-green"><i class="mdi mdi-check"></i> &nbsp;'.Translate::sprint("Started","").'&nbsp;&nbsp;</span>';
                                                    }else {
                                                        echo '<span class="badge bg-red"><i class="mdi mdi-close"></i> &nbsp;'.Translate::sprint("Finished","").'&nbsp;&nbsp;</span>';
                                                    }
                                                }


                                                ?>


                                            </td>


                                            <td align="right">
                                                <?php


                                                if ($this->mUserBrowser->getData("typeAuth") == "admin") {
                                                    if ($event['status'] == 1) {
                                                        echo ' <a href="' . site_url("ajax/event/statusEvent?event=" . $event['id_event']) . '"  class="linkAccess" onclick="return false;"><button type="button" title="enabled" class="btn btn-sm" onclick="return false;"><i style="color:#29910d  !important" class="fa fa-check" aria-hidden="true"></i></button></a>&nbsp;&nbsp;';
                                                    } else if ($event['status'] == 0) {
                                                        echo ' <a href="' . site_url("ajax/event/statusEvent?event=" . $event['id_event']) . '"  class="linkAccess" onclick="return false;"><button type="button" title="disableds" class="btn btn-sm"  onclick="return false;"><i style="color:#c60f0f !important" class="fa fa-times" aria-hidden="true"></i></button></a>&nbsp;&nbsp; ';
                                                    }
                                                }

                                                ?>

                                                <?php if ($event['status'] == 1 || $this->mUserBrowser->getData("typeAuth") == "admin") { ?>

                                                    <a href="#" data-toggle="modal"
                                                       data-target="#modal-default-<?= md5($event['id_event']) ?>">
                                                        <button type="button" data-toggle="tooltip" title="Delete" class="btn btn-sm"><span
                                                                class="glyphicon glyphicon-trash"></span></button>
                                                    </a>
                                                    <div class="modal fade"
                                                         id="modal-default-<?= md5($event['id_event']) ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span></button>


                                                                </div>
                                                                <div class="modal-body">

                                                                    <div class="row">

                                                                        <div style="text-align: center">
                                                                            <p class="text-red"><?= Translate::sprint("Are you sure you want to delete") ?> <?= $event['name'] . " ?" ?></p>
                                                                        </div>


                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-default pull-left"
                                                                            data-dismiss="modal"><?= Translate::sprint("Cancel", "Cancel") ?></button>
                                                                    <button type="button" id="_delete"
                                                                            data="<?= ($event['id_event']) ?>"
                                                                            class="btn btn-flat btn-primary"><?= Translate::sprint("Delete", "Delete") ?></button>
                                                                </div>
                                                            </div>

                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                <?php } ?>

                                                <?php if ($event['user_id'] != $this->mUserBrowser->getData("id_user") && $this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                                                    <a href="<?= admin_url("event/edit?id=" . $event['id_event']) ?>">
                                                        <button type="button" title="detail" class="btn btn-sm"><i
                                                                class="fa fa-eye"></i></button>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="<?= admin_url("event/edit?id=" . $event['id_event']) ?>">
                                                        <button type="button" title="update" class="btn btn-sm"><span
                                                                class="glyphicon glyphicon-edit"></span></button>
                                                    </a>
                                                <?php } ?>

                                            </td>


                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4">
                                            <div
                                                style="text-align: center"><?= Translate::sprint("No data found", "") ?></div>
                                        </td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                                <!-- <tfoot>
                                 <tr>
                                   <th>Rendering engine</th>
                                   <th>Browser</th>
                                   <th>Platform(s)</th>
                                   <th>Engine version</th>
                                   <th>CSS grade</th>
                                 </tr>
                                 </tfoot>-->
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                                </div>

                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                                    <?php

                                    echo $pagination->links(array(
                                        "search" => $this->input->get("search"),
                                        "store_id" => intval($this->input->get("store_id")),
                                        "status" => intval($this->input->get("status"))
                                    ), admin_url("event/events"));

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->


                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- jQuery 2.1.4 -->
<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<!-- page script -->
<script>

    $('a.linkAccess').on('click', function () {
        var url = ($(this).attr('href'));
        var cat = getURLParameter(url, 'event');

        //calling the ajax function
        pop(cat);
    });


    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
    }

    function pop(id) {

        $.ajax({
            type: 'post',
            url: "<?=  site_url("ajax/event/status")?>",
            dataType: 'json',
            data: {'id': id},
            beforeSend: function (xhr) {
                $(".linkAccess").attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                $(".linkAccess").attr("disabled", false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                $(".linkAccess").attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload();
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


<script>


    $("div #_delete").on('click', function () {

        var selector = $(this);
        var id = $(this).attr("data");

        $.ajax({
            url: "<?=  site_url("ajax/event/delete")?>",
            data: {"id": id},
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

                console.log(data);

                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload();
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
