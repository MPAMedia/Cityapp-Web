<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>

<?php if (GroupAccess::isGranted('user',USER_ADMIN)) { ?>
    <li class="treeview <?php if ($uri_m == "user" AND !TemplateManager::isSettingActive()) echo "active"; ?>">
        <a href="<?= admin_url("user/getUsers") ?>">
            <i class="mdi mdi-account-multiple-outline"></i> &nbsp;
            <span> <?= Translate::sprint("Manage Users", "") ?> </span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">

            <li class="<?php if ($uri_parent == "users") echo "active"; ?>">
                <a href="<?= admin_url("user/users") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                               <?= Translate::sprint("Users", "") ?> </span></a>
            </li>

            <?php if (GroupAccess::isGranted('user',MANAGE_GROUP_ACCESS)) : ?>
                <li class="<?php if ($uri_parent == "group_access") echo "active"; ?>">
                    <a href="<?= admin_url("user/group_access") ?>"><i class="mdi mdi-account-key"></i> &nbsp;<span>
                              <?= Translate::sprint("Group Access", "") ?>  </span></a>
                </li>
            <?php endif; ?>

            <?php if (GroupAccess::isGranted('user',ADD_USERS)) : ?>
            <li class="<?php if ($uri_parent == "add") echo "active"; ?>">
                <a href="<?= admin_url("user/add") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                              <?= Translate::sprint("Add new", "") ?>  </span></a>
            </li>
            <?php endif; ?>


        </ul>
    </li>
<?php }else{ ?>



<li class="treeview <?php if ($uri_m == "user" AND !TemplateManager::isSettingActive()) echo "active"; ?>">
    <a href="#">
        <i class="mdi mdi-account-outline"></i> &nbsp;
        <span> <?= Translate::sprint("Account", "") ?> </span>
        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        <li class="<?php if ($uri_parent == "profile") echo "active"; ?>">
            <a href="<?= admin_url("user/profile") ?>"><i class="mdi mdi-account-edit"></i> &nbsp;<span>
                               <?= Translate::sprint("Profile") ?> </span></a>
        </li>

    </ul>
</li>

<?php } ?>

