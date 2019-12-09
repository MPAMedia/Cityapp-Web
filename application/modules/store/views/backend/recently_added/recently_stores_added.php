<?php


$stores = $this->mStoreModel->recentlyAdd();
$stores = $stores['stores'];

?>
<div class=" col-md-6">
    <?php if (!empty($stores)) { ?>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><b><?= Translate::sprint("Recently_Added", "") ?> </b></h3>

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

                                try {
                                    $images = json_decode($store->images, JSON_OBJECT_AS_ARRAY);


                                    if (isset($images[0])) {
                                        $images = $images[0];
                                        $images = _openDir($images);

                                        if (isset($images['100_100']['url'])) {
                                            echo '<img src="' . $images['100_100']['url'] . '"width="50" height="50" alt="Product Image">';
                                        } else {
                                            echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                        }
                                    } else {
                                        echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                    }

                                } catch (Exception $e) {
                                    echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                }

                                ?>

                            </div>
                            <div class="product-info">
                                <a href="<?= admin_url("store/edit?id=" . $store->id_store) ?>"
                                   class="product-title"><?= Text::echo_output($store->name) ?>
                                    <span class="badge bg-green pull-right"><?= (ucfirst(Text::echo_output($store->nameCat))) ?></span></a>
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
            <?php if (count($stores) > 4) { ?>
                <div class="box-footer text-center">
                    <a href="<?= admin_url("store/stores") ?>"
                       class="uppercase"><?= Translate::sprint("View_all_stores", "View all stores") ?> </a>
                </div>
            <?php } ?>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->

    <?php } ?>
</div>
