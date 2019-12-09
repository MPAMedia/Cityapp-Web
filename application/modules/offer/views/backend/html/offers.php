<?php


$list = $offers[Tags::RESULT];
$pagination = $offers["pagination"];


$adminAccess = "";
if($this->mUserBrowser->getData("typeAuth")=="admin"){
    $adminAccess = "disabled";
}


$status = intval($this->input->get("status"));

if($status==1)
    $statusName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-green'>&nbsp;".Translate::sprint("My Offers")."&nbsp;&nbsp;<a style='color:#fff !important;' href='".admin_url("offer/offers")."'>x</a>&nbsp;</span>";
else
    $statusName = "";

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
            <div class="col-xs-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <div class="box-title"  style="width : 100%;">
                            <div class="row">
                                <div class="pull-left col-md-8">
                                    <b><?=Translate::sprint("Offers")?></b><?=$statusName?>
                                </div>
                                <div class="pull-right col-md-4"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <!--    <th>ID</th>-->
                                <th><?=Translate::sprint("Image","")?></th>
                                <th><?=Translate::sprint("Name","")?></th>
                                <th><?=Translate::sprint("AR Name","")?></th>
                                <th><?=Translate::sprint("Owner","")?></th>
                                <th><?=Translate::sprint("Status","")?></th>
                                
                                <th><?=Translate::sprint("Date","")?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php  if(count($list)){ ?>
                                <?php foreach ($list as $offer) { ?>

                                    <?php


                                    $current = date("Y-m-d H:i:s",time());
                                    $currentData = $current;
                                    $offer['date_start'] = MyDateUtils::convert($offer['date_start'],"UTC",TIME_ZONE,"Y-m-d H:i:s");
                                    $offer['date_end'] = MyDateUtils::convert($offer['date_end'],"UTC",TIME_ZONE,"Y-m-d H:i:s");

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

                                            try{


                                                if(!is_array($offer['image']))
                                                    $images = json_decode($offer['image'],JSON_OBJECT_AS_ARRAY);
                                                else{
                                                    $images = array($offer['image']);
                                                }

                                                if(isset($images[0])){
                                                    $images = $images[0];
                                                    if(isset($images['100_100']['url'])){
                                                        echo '<img src="'.$images['100_100']['url'].'"width="50" height="50" alt="Product Image">';
                                                    }else{
                                                        echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                                    }
                                                }else{
                                                    echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                                }

                                            }catch (Exception $e){
                                                $e->getMessage();
                                                echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                            }

                                            ?>
                                        </td>
                                        <td>
                                            <span style="font-size: 14px"><?=Text::output($offer['name'])?></span>
                                            <?php if($offer['featured']==1): ?>
                                                &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                            <?php endif;?><br>
                                            <span style="font-size: 12px;">
                                                <?php
                                                echo '<i class="mdi mdi-map-marker"></i>&nbsp;<a href="'.admin_url("store/edit?id=".$offer['store_id']).'"> '.$this->mStoreModel->getStoreName($offer['store_id']).'</a>';
                                                ?>
                                            </span>
                                        </td> 
                                        <td>
                                            <span style="font-size: 14px"><?=Text::output($offer['name_ar'])?></span>
                                            <?php if($offer['featured']==1): ?>
                                                &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                            <?php endif;?><br>
                                            <span style="font-size: 12px;">
                                                <?php
                                                echo '<i class="mdi mdi-map-marker"></i>&nbsp;<a href="'.admin_url("store/edit?id=".$offer['store_id']).'"> '.$this->mStoreModel->getStoreName($offer['store_id']).'</a>';
                                                ?>
                                            </span>
                                        </td>

                                        <td>

                                            <?php
                                            $href  = "";

                                            if($adminAccess!="")
                                                $href  = "href='".admin_url("user/edit?id=".$offer['user_id'])."'";

                                            ?>

                                            <a style="font-size: 11px" <?=$href?>><u><?=ucfirst($this->mUserModel->getUserNameById($offer['user_id']))?></u></a>
                                        </td>
                                        <td>
                                            <?php

                                            if ($offer['status'] == 0)
                                                echo '<span class="badge bg-yellow"><i class="mdi mdi-history"></i> &nbsp;'.Translate::sprint("Unpublished").'&nbsp;&nbsp;</span>';
                                            else if ($offer['status'] == 1) {


                                                if ($diff_millseconds_start>0) {
                                                    echo '<span class="badge bg-green"><i class="mdi mdi-history"></i> &nbsp;'.Translate::sprint("Published","").'&nbsp;&nbsp;</span>';
                                                } else if($diff_millseconds_start<0 && $diff_millseconds_end>0) {
                                                    echo '<span class="badge bg-blue"><i class="mdi mdi-check"></i> &nbsp;'.Translate::sprint("Started","").'&nbsp;&nbsp;</span>';
                                                }else {
                                                    echo '<span class="badge bg-red"><i class="mdi mdi-close"></i> &nbsp;'.Translate::sprint("Finished","").'&nbsp;&nbsp;</span>';
                                                }
                                            }


                                            ?>


                                        </td>

                                        <td>

                                            <?php

                                            $content = json_decode($offer['content'], JSON_OBJECT_AS_ARRAY);

                                            $currency =  $content['currency'];

                                            if(!is_array($currency))
                                                $currency = json_decode($currency,JSON_OBJECT_AS_ARRAY);

                                            if (floatval($content['price']) > 0) {
                                                echo '<span class="badge bg-red">&nbsp;' .$this->mOfferModel->parseCurrencyFormat($content['price'],$content['currency']). '&nbsp;&nbsp;</span>';
                                            } else if (intval($content['percent']) != 0) {
                                                echo '<span class="badge bg-red">&nbsp;' . intval($content['percent']) . '% &nbsp;&nbsp;</span>';
                                            }

                                            ?>




                                        </td>
<!--                                         <td>

                                            <?php

                                            $content = json_decode($offer['content_ar'], JSON_OBJECT_AS_ARRAY);

                                            $currency =  $content['currency'];

                                            if(!is_array($currency))
                                                $currency = json_decode($currency,JSON_OBJECT_AS_ARRAY);

                                            if (floatval($content['price']) > 0) {
                                                echo '<span class="badge bg-red">&nbsp;' .$this->mOfferModel->parseCurrencyFormat($content['price'],$content['currency']). '&nbsp;&nbsp;</span>';
                                            } else if (intval($content['percent']) != 0) {
                                                echo '<span class="badge bg-red">&nbsp;' . intval($content['percent']) . '% &nbsp;&nbsp;</span>';
                                            }

                                            ?>




                                        </td> -->




                                        <td>
                                        <span style="font-size: 12px;">

                                            <?php

                                            echo Translate::sprint("Start").": ".$offer['date_start']."<br>";
                                            echo Translate::sprint("End").": ".$offer['date_end']."<br>";

                                            if($diff_millseconds_start>0){
                                                echo "<i class=\"mdi mdi-history\"></i> ".Translate::sprint("Start after").": ".MyDateUtils::format_interval($differenceStart);
                                            }else if($diff_millseconds_start<0 && $diff_millseconds_end>0){
                                                echo "<i class=\"mdi mdi-history\"></i> ".Translate::sprint("End after").": ".MyDateUtils::format_interval($differenceEnd);

                                            }

                                            ?>
                                        </span>
                                        </td>
                                        <td align="right">

                                            <?php if($offer['status']==0 && $this->mUserBrowser->getData("typeAuth")=="admin"){ ?>

                                                <a href="<?=site_url("ajax/offer/changeStatus?id=".$offer['id_offer'])?>"  class="linkAccess" onclick="return false;">
                                                    <button type="button" class="btn btn-sm">
                                                        <i class="color-red text-red fa fa-close"></i>
                                                    </button>
                                                </a>

                                            <?php }else if($offer['status']==1 && $this->mUserBrowser->getData("typeAuth")=="admin"){  ?>

                                                <a href="<?=site_url("ajax/offer/changeStatus?id=".$offer['id_offer'])?>" class="linkAccess" onclick="return false;">
                                                    <button type="button" class="btn btn-sm">
                                                        <i class="color-green text-green fa fa-check"></i>
                                                    </button>
                                                </a>

                                            <?php } ?>



                                            <?php if($offer['user_id']==$this->mUserBrowser->getData("id_user")){ ?>
                                                <a href="<?=admin_url("offer/edit?id=".$offer['id_offer'])?>">
                                                    <button type="button" data-toggle="tooltip" title="Update" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-edit"></span>
                                                    </button>
                                                </a>
                                            <?php }else if($this->mUserBrowser->getData("typeAuth")=="admin"){  ?>
                                                <a href="<?=admin_url("offer/edit?id=".$offer['id_offer'])?>">
                                                    <button type="button" data-toggle="tooltip" title="Update" class="btn btn-sm">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </a>
                                            <?php } ?>




                                            <a href="<?=site_url("ajax/offer/delete?id=".$offer['id_offer'])?>" class="linkAccess" onclick="return false;">
                                                <button type="button" class="btn btn-sm">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>

                                <?php } ?>


                            <?php }else{  ?>
                                <tr>
                                    <td colspan="3"><?=Translate::sprint("No Offers","")?></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12 pull-right">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                                    <?php

                                    echo $pagination->links(array(
                                        "search"    =>$this->input->get("search"),
                                        "status"    =>$this->input->get("status"),
                                    ),admin_url("offer/offers"));

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
                                    <p class="text-red"><?=Translate::sprint("Are you sure?")?></p>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=Translate::sprint("Cancel","Cancel")?></button>
                            <button type="button" id="_delete"  class="btn btn-flat btn-primary"><?=Translate::sprint("OK")?></button>
                        </div>
                    </div>

                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>



            <!-- jQuery 2.1.4 -->
            <script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
            <!-- page script -->
            <script>

                $('a.linkAccess').on('click',function(){

                    $('#modal-default').modal('show');
                    //$('#myModal').modal('show');
                    //('#myModal').modal('hide');

                    var url = ($(this).attr('href'));
                    $("#_delete").on('click',function () {
                        //calling the ajax function
                        $(this).attr("disabled",true);
                        pop(url);
                        return true;
                    });

                });


                function getURLParameter(url, name) {
                    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
                }

                function pop(url) {
                    ;
                    $.ajax({
                        type:'get',
                        url:url,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            $(".linkAccess").attr("disabled",true);
                        },error: function (request, status, error) {
                            alert(request.responseText);
                            $(".linkAccess").attr("disabled",false);
                        },
                        success: function (data, textStatus, jqXHR) {

                            $('#modal-default').modal('hide');

                            $(".linkAccess").attr("disabled",false);
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
                }

            </script>




            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



