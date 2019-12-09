

<?php
        
    $stores = $stores2['stores'];
    $reviews = $reviews2['reviews'];


?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Message Error -->
                <div class="col-sm-12">
                    <?php $this->load->view("backend/include/messages");?>
                </div>

            </div>

            <div class="row">

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>


                        <div class="col-md-3 ">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="mdi mdi-store"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text" data-toggle="tooltip" title="<?=Translate::sprint("Total_Stores","")?>" >
                                        <?=Translate::sprint("Total_Stores","")?>
                                    </span>
                                    <span class="info-box-number"><?= $analytics["stores_count"] ?></span>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div><!-- /.col -->

<!--                            --><?php
//                        }
//                        ?>
<!---->
<!--                    --><?php
//                    if($this->mUserBrowser->isUser("admin") OR $this->mUserBrowser->isUser("manager") ) {
//                        ?>

                    <div class="col-md-3 ">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="mdi mdi-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" data-toggle="tooltip" title="<?=Translate::sprint("Total_Events","")?>">
                                    <?=Translate::sprint("Total_Events","")?>
                                </span>
                                <span class="info-box-number"><?= $analytics["events_count"] ?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div>
<!--                        --><?php
//                    }
//                    ?>



                    <?php
                    if($this->mUserBrowser->isUser("admin") ) {
                        ?>

                        <div class="col-md-3 ">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="mdi mdi-account-multiple-outline"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text" data-toggle="tooltip" title="<?=Translate::sprint("Total_Customers","")?>">
                                        <?=Translate::sprint("Total_Customers","")?>
                                    </span>
                                    <span class="info-box-number"><?= $analytics["users_count"] ?></span>
                                </div><!-- /.info-box-content -->
                            </div><!-- /.info-box -->
                        </div><!-- /.col -->

                        <?php
                    }
                    ?>


                    <div class="col-md-3 ">
                        <div class="info-box">
                            <span class="info-box-icon bg-orange-active"><i class="mdi mdi-bullseye"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" data-toggle="tooltip" title="<?=Translate::sprint("Total_Campaigns","")?>">
                                    <?=Translate::sprint("Total_Campaigns","")?>
                                </span>
                                <span class="info-box-number"><?= $analytics["campaigns_count"] ?></span>
                            </div><!-- /.info-box-content -->
                        </div><!-- /.info-box -->
                    </div><!-- /.col -->


                </div>

                <?php


                    $this->load->view("backend/charts/charts_v1");




                ?>


                <div class="row">

                    <div class=" col-md-6">
                        <?php if (!empty($stores)) { ?>

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><b><?=Translate::sprint("Recently_Added","")?> </b></h3>

                                    <div class="box-tools pull-right">

                                    </div>
                                </div>
                                <!-- /.box-header -->

                                <div class="box-body">
                                    <ul class="products-list product-list-in-box">

                                        <?php foreach ($stores AS $store) { ?>
                                            <li class="item">
                                                <div class="product-img">

                                                    <?php

                                                    try{
                                                        $images = json_decode($store->images,JSON_OBJECT_AS_ARRAY);


                                                        if(isset($images[0])){
                                                            $images = $images[0];
                                                            $images = _openDir($images);

                                                            if(isset($images['100_100']['url'])){
                                                                echo '<img src="'.$images['100_100']['url'].'"width="50" height="50" alt="Product Image">';
                                                            }else{
                                                                echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                                            }
                                                        }else{
                                                            echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                                        }

                                                    }catch (Exception $e){
                                                        echo '<img src="'.base_url("views/skin/backend/images/def_logo.png").'"width="50" height="50" alt="Product Image">';
                                                    }

                                                    ?>

                                                </div>
                                                <div class="product-info">
                                                    <a href="<?=admin_url("store/edit?id=".$store->id_store)?>"
                                                       class="product-title"><?= Text::echo_output($store->name) ?>
                                                        <span class="badge bg-green pull-right"><?= (ucfirst( Text::echo_output($store->nameCat)) ) ?></span></a>
                                                    <span class="product-description">
                                                      <?= Text::echo_output($store->address) ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <!-- /.item -->
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                                <?php if(count($stores)>4){ ?>
                                <div class="box-footer text-center">
                                    <a href="<?= admin_url("store/stores") ?>" class="uppercase"><?=Translate::sprint("View_all_stores","View all stores")?> </a>
                                </div>
                                <?php } ?>
                                <!-- /.box-footer -->
                            </div>
                            <!-- /.box -->

                        <?php } ?>
                    </div>


                    <div class=" col-md-6">

                        <?php if (!empty($reviews)) { ?>

                            <div class="box box-solid">
                                <div class="box-header ui-sortable-handle" style="cursor: move;">
                                    <i class="fa fa-comments-o"></i>

                                    <h3 class="box-title"><b><?=Translate::sprint("Last_reviews")?> </b></h3>

                                    <div class="box-tools pull-right" data-toggle="tooltip" title=""
                                         data-original-title="Status">
                                        <div class="btn-group" data-toggle="btn-toggle">

                                        </div>
                                    </div>
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
                                                                $image= base_url("views/skin/backend/images/profile_placeholder.png");
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
                                                                <a class="name"  onclick="return false;">
                                                                    <b><?= ucfirst( htmlspecialchars($review->pseudo)  ) ?></b>
                                                                </a>

                                                                <a href="<?=admin_url("store/edit?id=".$review->store_id)?>">
                                                                    &nbsp;&nbsp;
                                                                    <span class="badge bg-red"><?= Text::echo_output($review->nameStr) ?></span>
                                                                </a>

                                                                <br>
                                                                <?php
                                                                        $reviewMod = $review->review ;
                                                                if(strlen($reviewMod) > 20)
                                                                {
                                                                  echo substr($reviewMod,0,20)."...";
                                                                }else {
                                                                  echo htmlspecialchars($reviewMod);
                                                                }

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
                                                        </tr>
                                                        <!-- /.item -->


                                                    <?php }
                                                }
                                            } ?>
                                        </table>

                                    </div>


                            </div>

                        <?php } ?>


                    </div>

                </div>


        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      
      <!-- jQuery 2.1.4 -->
     <script src="<?=  base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js")?>"></script>
      <!-- Slimscroll -->
      <script src="<?=  base_url("views/skin/backend/plugins/slimScroll/jquery.slimscroll.min.js")?>"></script>
