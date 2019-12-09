<?php


$list = $offers[Tags::RESULT];
$pagination = $offers["pagination"];

// this fields serve to filter offers by status
$status = $this->input->get("status");
$filterBy = $this->input->get("filterBy");

/*if ($status == 1)
    $statusName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-green'>&nbsp;" . Translate::sprint("My Offers") . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . admin_url("offer/offers") . "'>x</a>&nbsp;</span>";
else
    $statusName = "";*/


if (isset($filterBy))
    $filerN = "&nbsp;&nbsp;&nbsp;<span class='badge bg-red-active'>&nbsp;" . Translate::sprint("Clear filter") . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . current_url() . "'>x</a>&nbsp;</span>";
else
    $filerN = "";

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

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <div class="box-title" style="width : 100%;">
                            <div class="row">
                                <div class="pull-left col-md-8">
                                    <b><?= Translate::sprint("Offers") ?></b>  <?= $filerN ?>
                                </div>
                                <div class="pull-right col-md-4">
                                    <?php if (GroupAccess::isGranted('offer', ADD_OFFER)) { ?>
                                        <a href="<?= admin_url("offer/add") ?>">
                                            <button type="button" data-toggle="tooltip"
                                                    title="<?= Translate::sprint("Create new offer", "") ?> "
                                                    class="btn btn-primary btn-sm pull-right"><span
                                                        class="glyphicon glyphicon-plus"></span></button>
                                        </a>
                                    <?php } ?>

                                    <form method="get"
                                          action="<?php echo current_url(); ?>">

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
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <!--    <th>ID</th>-->
                                <th><?= Translate::sprint("Image", "") ?></th>
                                <th><?= Translate::sprint("Name", "") ?></th>
                                <th><?= Translate::sprint("Owner", "") ?></th>
                                <th><?= Translate::sprint("Status", "") ?></th>
                                <th><?= Translate::sprint("Offer", "") ?></th>
                                <th><?= Translate::sprint("Date", "") ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if (count($list)) { ?>
                                <?php foreach ($list as $offer) { ?>

                                    <?php


                                    $current = date("Y-m-d H:i:s", time());
                                    $currentData = $current;
                                    $offer['date_start'] = MyDateUtils::convert($offer['date_start'], "UTC", "UTC", "Y-m-d");
                                    $offer['date_end'] = MyDateUtils::convert($offer['date_end'], "UTC", "UTC", "Y-m-d");

                                    $currentData = date_create($currentData);
                                    $dateStart = date_create($offer['date_start']);
                                    $dateEnd = date_create($offer['date_end']);

                                    $differenceStart = $currentData->diff($dateStart);
                                    $differenceEnd = $currentData->diff($dateEnd);

                                    $diff_millseconds_start = strtotime($offer['date_start']) - strtotime($current);
                                    $diff_millseconds_end = strtotime($offer['date_end']) - strtotime($current);

                                    ?>

                                    <tr>
                                        <td>
                                            <?php

                                            try {

                                                if (!is_array($offer['images']))
                                                    $images = json_decode($offer['images'], JSON_OBJECT_AS_ARRAY);
                                                else
                                                    $images = $offer['images'];

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
                                            <span style="font-size: 14px"><?= Text::output($offer['name']) ?></span>
                                            <?php if ($offer['featured'] == 1): ?>
                                                &nbsp;&nbsp;<span class="badge bg-blue-active"
                                                                  style="font-size: 10px;text-transform: uppercase"><i
                                                            class="mdi mdi-check"></i>&nbsp;<?= Translate::sprint("Featured") ?></span>
                                            <?php endif; ?><br>
                                            <span style="font-size: 12px;">
                                                <?php
                                                echo '<i class="mdi mdi-map-marker"></i>&nbsp;<a href="' . admin_url("store/edit?id=" . $offer['store_id']) . '"> ' . $this->mStoreModel->getStoreName($offer['store_id']) . '</a>';
                                                ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?php if (GroupAccess::isGranted('offer', EDIT_OFFER)): ?>
                                                <a style="font-size: 11px"
                                                   href="<?= admin_url("user/edit?id=" . $offer['user_id']) ?>"><u><?= ucfirst($this->mUserModel->getUserNameById($offer['user_id'])) ?></u></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>

                                            <?php if ($offer['status'] == 0) : ?>
                                                <a href="<?php echo current_url(). "?status=" . $offer['status']  . "&filterBy=Unpublished" ; ?>">
                                                    <span class="badge bg-yellow"><i class="mdi mdi-history"></i> &nbsp; <?php echo Translate::sprint("Unpublished") ?>  &nbsp;&nbsp;</span>
                                                </a>
                                            <?php elseif ($offer['status'] == 1)  : {
                                                if ($diff_millseconds_start > 0) { ?>
                                                        <a href="<?php echo current_url(). "?status=" . $offer['status']  . "&filterBy=Published"; ?>">
                                                        <span class="badge bg-green"><i class="mdi mdi-history"></i> &nbsp;  <?php echo Translate::sprint("Published", "") ?> &nbsp;&nbsp;</span>
                                                    </a>
                                                    <?php
                                                } else if ($diff_millseconds_start < 0 && $diff_millseconds_end > 0) { ?>
                                            <a href="<?php echo current_url(). "?status=" . $offer['status']  . "&filterBy=Started"; ?>">
                                                        <span class="badge bg-blue"><i class="mdi mdi-check"></i> &nbsp;  <?php echo Translate::sprint("Started", "") ?>  &nbsp;&nbsp;</span>
                                                    </a>
                                                <?php } else { ?>
                                            <a href="<?php echo current_url(). "?status=" . $offer['status']  . "&filterBy=Finished"; ?>">
                                                 <span class="badge bg-red"><i class="mdi mdi-close"></i> &nbsp;  <?php echo Translate::sprint("Finished", "") ?>   &nbsp;&nbsp;</span>
                                                    </a>
                                                <?php }
                                            }

                                            endif; ?>
                                        </td>

                                        <td>

                                            <?php

                                            if (is_array($offer['currency']))
                                                $offer['currency'] = $offer['currency']['code'];

                                            if ($offer['value_type'] == 'price') {
                                                echo '<span class="badge bg-red">&nbsp;' . Currency::parseCurrencyFormat($offer['offer_value'], $offer['currency']) . '&nbsp;&nbsp;</span>';
                                            } else if ($offer['value_type'] == 'percent') {
                                                echo '<span class="badge bg-red">&nbsp;' . intval($offer['offer_value']) . '% &nbsp;&nbsp;</span>';
                                            } else {
                                                echo '<span class="badge bg-red">&nbsp;' . Translate::sprint("Promotion") . '&nbsp;&nbsp;</span>';
                                            }

                                            ?>


                                        </td>


                                        <td>
                                        <span style="font-size: 12px;">

                                            <?php

                                            echo Translate::sprint("Start") . ": " . $offer['date_start'] . "<br>";
                                            echo Translate::sprint("End") . ": " . $offer['date_end'] . "<br>";

                                            if ($diff_millseconds_start > 0) {
                                                echo "<i class=\"mdi mdi-history\"></i> " . Translate::sprint("Start after") . ": " . MyDateUtils::format_interval($differenceStart);
                                            } else if ($diff_millseconds_start < 0 && $diff_millseconds_end > 0) {
                                                echo "<i class=\"mdi mdi-history\"></i> " . Translate::sprint("End after") . ": " . MyDateUtils::format_interval($differenceEnd);

                                            }

                                            ?>
                                        </span>
                                        </td>
                                        <td align="center">
                                            <?php if ($offer['status'] == 1 && GroupAccess::isGranted('offer', VALIDATE_OFFERS)) { ?>

                                                <a href="<?= site_url("ajax/offer/changeStatus?id=" . $offer['id_offer']) ?>"
                                                   class="linkAccess" onclick="return false;">
                                                    <button type="button" class="btn btn-sm">
                                                        <i class="color-green text-green fa fa-check"></i>
                                                    </button>
                                                </a>

                                            <?php } else if ($offer['status'] == 0 && GroupAccess::isGranted('offer', VALIDATE_OFFERS)) { ?>

                                                <?php if ($offer['verified'] == 1): ?>
                                                    <a href="<?= site_url("ajax/offer/changeStatus?id=" . $offer['id_offer']) ?>"
                                                       class="linkAccess" onclick="return false;">
                                                        <button type="button" class="btn btn-sm">
                                                            <i class="color-red text-red fa fa-close"></i>
                                                        </button>

                                                    </a>
                                                <?php else: ?>

                                                    <?php
                                                    echo ' <a href="' . admin_url("offer/verify?status=" . $status . "&id=" . $offer['id_offer']) . '&accept=1"><button type="button"  data-toggle="tooltip" title="Accept" class="btn btn-sm bg-green" ><i class="text-white mdi mdi-thumb-up" aria-hidden="true"></i></button></a> ';
                                                    echo ' <a href="' . admin_url("offer/verify?status=" . $status . "&id=" . $offer['id_offer']) . '&accept=0"><button type="button"  data-toggle="tooltip" title="Decline" class="btn btn-sm  bg-red" ><i class="text-white fa fa-times" aria-hidden="true"></i></button></a>';
                                                    ?>

                                                <?php endif; ?>


                                            <?php } ?>



                                            <?php if ($offer['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                                <a href="<?= admin_url("offer/edit?id=" . $offer['id_offer']) ?>">
                                                    <button type="button" data-toggle="tooltip" title="Update"
                                                            class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-edit"></span>
                                                    </button>
                                                </a>
                                            <?php } else if (GroupAccess::isGranted('offer', EDIT_OFFER)) { ?>
                                                <a href="<?= admin_url("offer/edit?id=" . $offer['id_offer']) ?>">
                                                    <button type="button" data-toggle="tooltip" title="Update"
                                                            class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </a>
                                            <?php } ?>


                                            <?php if (GroupAccess::isGranted('offer', DELETE_OFFER)): ?>
                                            <a href="<?= site_url("ajax/offer/delete?id=" . $offer['id_offer']) ?>"
                                               class="linkAccess" onclick="return false;">
                                                <button data-toggle="tooltip" title="Delete" type="button"
                                                        class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                    </tr>

                                <?php } ?>


                            <?php } else { ?>
                                <tr>
                                    <td colspan="3"><?= Translate::sprint("No Offers", "") ?></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12 pull-right">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                                    <?php

                                    echo $pagination->links(array(
                                        "search" => $this->input->get("search"),
                                        "status" => $this->input->get("status"),
                                    ), current_url());

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">

                            <div class="row">

                                <div style="text-align: center">
                                    <p class="text-red"><?= Translate::sprint("Are you sure?") ?></p>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left"
                                    data-dismiss="modal"><?= Translate::sprint("Cancel", "Cancel") ?></button>
                            <button type="button" id="_delete"
                                    class="btn btn-flat btn-primary"><?= Translate::sprint("OK") ?></button>
                        </div>
                    </div>

                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


            <?php if (GroupAccess::isGranted('offer', DELETE_OFFER)): ?>
                <!-- jQuery 2.1.4 -->
                <script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
                <!-- page script -->
                <script>

                    $('a.linkAccess').on('click', function () {
                        var url = ($(this).attr('href'));
                        executeURL(url);
                    });


                    function executeURL(url) {

                        $.ajax({
                            type: 'get',
                            url: url,
                            dataType: 'json',
                            beforeSend: function (xhr) {
                                $(".linkAccess").attr("disabled", true);
                            }, error: function (request, status, error) {
                                alert(request.responseText);
                                $(".linkAccess").attr("disabled", false);
                            },
                            success: function (data, textStatus, jqXHR) {

                                $('#modal-default').modal('hide');
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
            <?php endif; ?>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



