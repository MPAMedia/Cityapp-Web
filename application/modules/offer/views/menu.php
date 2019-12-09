<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>
<?php if (GroupAccess::isGranted('offer')) : ?>
<li class="treeview <?php if ($uri_m == "offer") echo "active"; ?>">
    <a href="<?= admin_url("offer/offers") ?>"><i class="mdi mdi-sale "></i> &nbsp;
        <span><?= Translate::sprint("Offers") ?></span>
        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>

    <ul class="treeview-menu">

        <?php if (GroupAccess::isGranted('user',USER_ADMIN)) : ?>
        <li><a href="<?= admin_url("offer/all_offers") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                    &nbsp;<?= Translate::sprint("All Offers") ?></a></li>
        <?php endif; ?>

        <li><a href="<?= admin_url("offer/offers?status=1") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                &nbsp;<?= Translate::sprint("My Offers") ?></a></li>

        <?php if(GroupAccess::isGranted('offer',ADD_OFFER)): ?>
        <li><a href="<?= admin_url("offer/add") ?>"><i class="mdi mdi-plus-box  "></i>
                &nbsp;<?= Translate::sprint("Add new") ?></a></li>
        <?php endif; ?>
    </ul>
</li>
<?php endif; ?>
