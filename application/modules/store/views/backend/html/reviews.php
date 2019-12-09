<?php


        $reviews = $data["reviews"];
        $pagination = $data['pagination'];
        $store = $store[Tags::RESULT][0];

//    if($store['user_id']!=$this->mUserBrowser->getData("id_user") AND $this->mUserBrowser->getData("typeAuth")!="admin"){
//        redirect(admin_url("error404"));
//    }


?>


<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?=Translate::sprint("Store detail")?></b></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="form" role="form">
                            <!-- text input -->

                            <?php

                            $images = $store['images'];
                            if($images!="" AND !is_array($images)){
                                $images = json_decode($images);
                            }

                            ?>

                            <div class="form-group required">
                                <label for="name"><?=Translate::sprint("Images","")?>:  </label>

                                <label class="msg-error-form image-data"></label>
                                <div class="clear"></div>
                                <div id="progress" class="hidden">
                                    <div class="percent" style="width: 0%"></div>
                                </div>

                                <div class="clear"></div>

                                <div id="image-previews">

                                    <?php if(!empty($images)){ ?>

                                        <?php foreach ($images as $key => $value){ ?>

                                            <?php

                                            $name = $value['name'];
                                            $item = "item_".$name;

                                            $imagesData = $value;

                                            ?>


                                            <div class="image-uploaded item_<?=$name?>">
                                                <a id="image-preview">
                                                    <img src="<?=$imagesData['200_200']['url']?>" alt="">
                                                </a>

                                                <div class="clear"></div>

                                            </div>


                                        <?php } ?>
                                    <?php } ?>
                                </div>

                            </div>



                            <input type="hidden" id="id" value="<?=$store['id_store']?>" >
                            <div class="form-group">
                                <label><?=Translate::sprint("Name","")?> : </label>
                                <input type="text" class="form-control" placeholder="Enter ..." value="<?=$store['name']?>" name="name" id="name" disabled>

                            </div>
                            <div class="form-group">
                                <label><?=Translate::sprint("Address","")?> :</label>
                                <input type="text" class="form-control" placeholder="Enter ..." value="<?=$store['address']?>" name="address" id="address" disabled>
                            </div>
                            <div class="form-group">

                                <div class="row no-margin">
                                    <div class="col-md-6 no-padding"><label> <?=Translate::sprint("Lat","")?> : </label>  <input  class="form-control" value="<?=$store['latitude']?>" type="text" name="lat" id="lat"  disabled/> </div>
                                    <div class="col-md-6 no-padding">  <label><?=Translate::sprint("Lng","")?> : </label>  <input class="form-control" value="<?=$store['longitude']?>"  type="text" name="long" id="lng"  disabled/></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?=Translate::sprint("Category","")?> :</label>
                                <input type="text" class="form-control" placeholder="Enter ..." value="<?=  Text::output($this->mStoreModel->getCatName($store['category_id']))?>" name="address" id="address" disabled>
                            </div>

                            <!-- textarea -->
                            <div class="form-group">
                                <label><?=Translate::sprint("Detail","")?> :</label>
                                <textarea id="" class="form-control" style="height: 300px" disabled><?php echo strip_tags($store['detail']); ?></textarea>
                            </div>


                            <div class="form-group">
                                <label><?=Translate::sprint("Phone Number","")?> :</label>
                                <input type="text" class="form-control" placeholder="Enter ..." value='<?=$store['telephone']?>' name="tel" id="tel" disabled>
                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>


            </div>


            <div class="col-md-6">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b> <?=Translate::sprint("Reviews")?> :</b></h3>
                    </div>

                    <div class="box-body chat" id="chat-box">

                        <table id="example2" class="table table-bordered table-hover">
                        <?php foreach ($reviews AS $review) {
                            if ($review->review != "" AND isset($review->review)) {

                                if ($review->pseudo != "" AND isset($review->pseudo)) {

                                   $user =  $this->mUserModel->getUserByGuestId($review->guest_id);
                                   $image = base_url("views/skin/backend/images/profile_placeholder.png");

                                    if($user!=NULL and isset($user[Tags::RESULT][0])){
                                        $user = $user[Tags::RESULT][0];
                                        if(isset($user['images'][0]['200_200']['url'])){
                                            $image = $user['images'][0]['200_200']['url'];
                                        }else{

                                        }
                                    }


                                    ?>
                                    <!-- chat item -->
                                    <tr>
                                        <td width="10%"  valign="center">
                                            <div class="image-container-40"  style="background-image: url('<?= $image ?>');">
                                               <img  class="direct-chat-img invisible" src="<?= $image ?>" alt="user image">
                                            </div>
                                        </td>
                                        <td width="60%" valign="center">

                                                <a href="#" class="name" onclick="return false;">
                                                    <b><?= ucfirst( htmlspecialchars($review->pseudo)  ) ?></b>
                                                </a><br>

                                            <?php
                                                $reviewMod = $review->review ;
                                                echo htmlspecialchars($reviewMod);
                                            ?>

                                        </td>
                                        <td width="30%" align="right"  valign="center">
                                            <small class="text-muted pull-right">

                                                <?php

                                                $rate = ceil($review->rate);

                                                for ($i = 1; $i <= $rate; $i++) { ?>
                                                    <span class="mdi mdi-star"
                                                          style="color: #db8b0b;font-size: 15px;"></span>
                                                    <?php


                                                    if ($i == $rate) {

                                                        for ($j = $i; $j < 5; $j++) {
                                                            echo ' <span class="mdi mdi-star-outline"style="color: #db8b0b;font-size: 15px;"></span>';
                                                        }
                                                        break;
                                                    }
                                                }

                                                ?>


                                            </small>
                                        </td>
                                    <?php if ($store['status'] == 1 || $this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                                        <td>
                                            <a href="#" data-toggle="modal"
                                               data-target="#modal-default-<?= md5($review->id_rate) ?>">
                                            <button type="button" class="btn btn-sm"><span
                                                        class="glyphicon glyphicon-trash"></span></button>
                                         </td>
                                    </tr>

                                        <!-- Popup to delete the reviews-->
                                        <div class="modal fade" id="modal-default-<?= md5($review->id_rate) ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title"></h4>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">

                                                            <div style="text-align: center">
                                                                <h3 class="text-red"><?= Translate::sprint("Are you sure you want to delete") ?> <strong> <?=$review->review . " ?" ?> </strong> </h3>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default pull-left"
                                                                data-dismiss="modal"><?= Translate::sprint("Cancel", "Cancel") ?></button>
                                                        <button type="button" id="_deleteRev"
                                                                data="<?= ($review->id_rate) ?>"
                                                                class="btn btn-flat btn-primary"><?= Translate::sprint("Delete", "Delete") ?></button>
                                                    </div>
                                                </div>

                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                    <?php } ?>
                                    <!-- /.item -->


                                <?php }
                            }
                        } ?>
                        </table>
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                            <?php

                            echo $pagination->links(array(
                                "id"         => intval($this->input->get("id")),
                            ),admin_url("reviews"));

                            ?>
                        </div>

                        <?php

                            if(count($reviews)==0){
                                echo Translate::sprint("No reviews");
                            }

                        ?>
                    </div>

                </div>

            </div>

    </section>

</div>

<?php

$script = $this->load->view('backend/html/scripts/reviews-script',NULL,TRUE);
TemplateManager::addScript($script);

?>

