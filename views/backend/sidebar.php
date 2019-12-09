<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);


?>

<ul class="sidebar-menu">
    <li class="header"><?= Translate::sprint("MENU", "") ?></li>
    <li class="<?php if ($uri_parent == "" || $uri_parent == "index") echo "active"; ?>">
        <a href="<?= admin_url("") ?>"><i class="mdi mdi-home-outline"></i> &nbsp;<span>
                        <?= Translate::sprint("Home", "Home") ?></span></a>
    </li>

    <?php

    $menuList = TemplateManager::loadMenu();
    if(!empty($menuList)){
        foreach ($menuList as $menu){
            foreach ($menu as $li){
                $this->load->view($li);
            }
        }
    }

    ?>



    <?php if (GroupAccess::isGranted('setting')) { ?>
        <li class="treeview <?php if(TemplateManager::isSettingActive()) echo 'active'?>">
            <a href="<?= admin_url("application") ?>"><i class="mdi mdi-settings"></i> &nbsp;
                <span> <?= Translate::sprint("Application") ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>

            <ul class="treeview-menu">

                <?php

                $menuList = TemplateManager::loadMenuSetting();
                if(!empty($menuList)){
                    foreach ($menuList as $menu){
                        foreach ($menu as $li){
                            $this->load->view($li);
                        }
                    }
                }

                ?>


            </ul>
        </li>
    <?php } ?>



    <?php if (DEMO): ?>
        <li class="<?php if ($uri_parent == "campaigns") echo "active"; ?>">
            <a target="_blank" href="https://play.google.com/store/apps/details?id=com.droideve.apps.nearbystores">
                <i class="mdi mdi-google-play"></i> &nbsp;<span> <?= Translate::sprint("Download app", "") ?></span>
            </a>
        </li>
    <?php endif; ?>


</ul>
          