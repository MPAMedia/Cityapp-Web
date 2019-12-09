<?php


$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);

?>

<?php if(GroupAccess::isGranted('store')): ?>
<li class="treeview <?php if ($uri_m == "store") echo "active"; ?>">

    <a href="<?= admin_url("store/stores") ?>"><i class="mdi mdi-store"></i> &nbsp;
        <span> <?= Translate::sprint("Manage Stores") ?></span>
        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>

    </a>

    <ul class="treeview-menu">

        <?php  if (GroupAccess::isGranted('user',USER_ADMIN)  ) { ?>
            <li class="<?php if ($uri_parent == "all_stores") echo "active"; ?>">
                <a href="<?= admin_url("store/all_stores") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                                <?= Translate::sprint("All_stores", "") ?></span></a>
            </li>
        <?php } ?>

        <li class="<?php if ($uri_parent == "stores") echo "active"; ?>">
            <a href="<?= admin_url("store/stores?status=1") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                &nbsp;<span>
                                <?= Translate::sprint("My_stores", "") ?></span></a>
        </li>

        <?php if (GroupAccess::isGranted('store',ADD_STORE)) : ?>
        <li  class="<?php if ($uri_parent == "create") echo "active"; ?>">
            <a href="<?= admin_url("store/create") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                                <?= Translate::sprint("Add new", "") ?></span></a>
        </li>
        <?php endif; ?>
    </ul>
</li>
<?php endif; ?>
