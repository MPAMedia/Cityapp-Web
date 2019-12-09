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
    <li class="treeview <?php if ($uri_m == "store") echo "active"; ?>">

        <a href="<?= admin_url("store/stores") ?>"><i class="mdi mdi-store"></i> &nbsp;
            <span> <?= Translate::sprint("Manage Stores") ?></span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>

        </a>

        <ul class="treeview-menu">

            <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                <li>
                    <a href="<?= admin_url("store/stores") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                                <?= Translate::sprint("All_stores", "") ?></span></a>
                </li>
            <?php } ?>

            <li>
                <a href="<?= admin_url("store/stores?status=1") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                    &nbsp;<span>
                                <?= Translate::sprint("My_stores", "") ?></span></a>
            </li>
            <li>
                <a href="<?= admin_url("store/create") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                                <?= Translate::sprint("Add new", "") ?></span></a>
            </li>
        </ul>
    </li>


    <li class="treeview <?php if ($uri_m == "offer") echo "active"; ?>">
        <a href="<?= admin_url("offer/offers") ?>"><i class="mdi mdi-sale "></i> &nbsp;
            <span><?= Translate::sprint("Offers") ?></span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">

            <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                <li><a href="<?= admin_url("offer/offers") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                        &nbsp;<?= Translate::sprint("All Offers") ?></a></li>
            <?php } ?>

            <li><a href="<?= admin_url("offer/offers?status=1") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                    &nbsp;<?= Translate::sprint("My Offers") ?></a></li>
            <li><a href="<?= admin_url("offer/add") ?>"><i class="mdi mdi-plus-box  "></i>
                    &nbsp;<?= Translate::sprint("Add new") ?></a></li>
        </ul>
    </li>

    <li class="treeview <?php if ($uri_m == "event") echo "active"; ?>">
        <a href="<?= admin_url("event/events") ?>"><i class="mdi mdi-calendar-text "></i> &nbsp;
            <span><?= Translate::sprint("Manage Events", "") ?> </span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">

            <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                <li>
                    <a href="<?= admin_url("event/events") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                                <?= Translate::sprint("All Events", "") ?></span></a>
                </li>
            <?php } ?>

            <li>
                <a href="<?= admin_url("event/events?status=1") ?>"><i class="mdi mdi-format-list-bulleted"></i>
                    &nbsp;<span>
                                <?= Translate::sprint("My events", "") ?></span></a>
            </li>
            <li>
                <a href="<?= admin_url("event/create") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                                 <?= Translate::sprint("Add new", "") ?></span></a>
            </li>
        </ul>

    </li>

    <li class="treeview <?php if ($uri_m == "place") echo "active"; ?>">
        <a href="<?= admin_url("place/places") ?>"><i class="mdi mdi-calendar-text "></i> &nbsp;
            <span><?= Translate::sprint("Manage Places", "") ?> </span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">

            <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                <li>
                    <a href="<?= admin_url("place/places") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                                <?= Translate::sprint("All Places", "") ?></span></a>
                </li>
            <?php } ?>

          
            <li>
                <a href="<?= admin_url("place/create") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                                 <?= Translate::sprint("Add new", "") ?></span></a>
            </li>
        </ul>

    </li>

    <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>

        <li class="treeview <?php if ($uri_m == "user") echo "active"; ?>">
            <a href="<?= admin_url("user/getUsers") ?>">
                <i class="mdi mdi-account-multiple-outline"></i> &nbsp;
                <span> <?= Translate::sprint("Manage Customers", "") ?> </span>
                <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>

            <ul class="treeview-menu">
                <li>
                    <a href="<?= admin_url("user/users") ?>"><i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                               <?= Translate::sprint("Customers", "") ?> </span></a>
                </li>
                <li>
                    <a href="<?= admin_url("user/add") ?>"><i class="mdi mdi-plus-box "></i> &nbsp;<span>
                              <?= Translate::sprint("Add new", "") ?>  </span></a>
                </li>
            </ul>
        </li>

    <?php } ?>


    <?php if (ENABLE_MESSAGES == TRUE): ?>

        <?php


            $newMessageCount = Modules::run("messenger/ajax/countMessagesNoSeen");
            if(isset($newMessageCount[Tags::COUNT]))
                $newMessageCount = $newMessageCount[Tags::COUNT];
            else
                $newMessageCount = 0;


        ?>


        <?php if ($this->mUserBrowser->getData("typeAuth") == "admin" OR (ALLOW_DASHBOARS_MESSENGER_TO_OWNERS == TRUE
                && $this->mUserBrowser->getData("typeAuth") == "manager")
        ): ?>

            <li class=" <?php if ($uri_m == "messenger") echo "active"; ?>">
                <a href="<?= admin_url("messenger/messages") ?>"><i class="mdi mdi-forum"></i> &nbsp;
                    <span> <?= Translate::sprint("Messages") ?></span>



                    <?php if ($newMessageCount > 0): ?>
                        <span class="pull-right-container">
                        <small class="badge pull-right bg-yellow"><?= $newMessageCount ?></small>
                    </span>
                    <?php endif; ?>
                </a>

            </li>


        <?php endif; ?>

    <?php endif; ?>




    <?php if ($this->mUserBrowser->getData("typeAuth") == "admin") { ?>

        <li class="treeview <?php if ($uri_m == "campaign") echo "active"; ?>">
            <a href="<?= admin_url("campaign/campaigns") ?>"><i class="mdi mdi-bullseye"></i> &nbsp;&nbsp;
                <span><?= Translate::sprint("Campaigns") ?></span>
                <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
            </a>


            <?php

            $this->load->model("campaign/campaign_model");
            $nbrCampaigns = 0;
            $nbrCampaigns = $this->campaign_model->getPendingCampaigns();

            ?>
            <ul class="treeview-menu">
                <li>
                    <a href="<?= admin_url("campaign/campaigns") ?>"><i class="mdi mdi-bullseye"></i> &nbsp;<span>
                             <?= Translate::sprint("All Compaigns", "") ?></span>

                        <?php if ($nbrCampaigns > 0): ?>
                            <span class="pull-right-container">
                                  <small class="badge pull-right bg-yellow"><?= $nbrCampaigns ?></small>
                                </span>
                        <?php endif; ?>

                    </a>
                </li>
                <li>
                    <a href="<?= admin_url("campaign/campaigns?owner=1") ?>"><i class="mdi mdi-bullseye "></i>
                        &nbsp;<span>
                           <?= Translate::sprint("My Compaigns", "") ?></span></a>
                </li>

                <li>
                    <a href="<?= admin_url("campaign/campaigns?status=1") ?>"><i class="mdi mdi-bullseye "></i>
                        &nbsp;<span>
                            <?= Translate::sprint("Pushed", "") ?></span></a>
                </li>

                <li>
                    <a href="<?= admin_url("campaign/campaigns?status=2") ?>"><i class="mdi mdi-bullseye "></i> &nbsp;<span>
                            <?= Translate::sprint("Completed", "") ?> </span></a>
                </li>

                <li>
                    <a href="<?= admin_url("campaign/campaigns?status=-1") ?>"><i class="mdi mdi-bullseye "></i> &nbsp;
                        <span>  <?= Translate::sprint("Campaigns (No-valid)", "") ?></span>

                    </a>
                </li>
            </ul>
        </li>

    <?php } else if(ENABLE_CAMPAIGNS_FOR_OWNER==TRUE) { ?>



        <li class="<?php if ($uri_m == "campaign") echo "active"; ?>">
            <a href="<?= admin_url("campaign/campaigns") ?>"><i class="mdi mdi-bullseye"></i> &nbsp;<span>
                    <?= Translate::sprint("Campaigns") ?>
                </span>
            </a>
        </li>


    <?php } ?>


    <?php if ($this->mUserBrowser->getData("typeAuth") != "manager") { ?>
        <li class="<?php if ($uri_m == "category") echo "active"; ?>">
            <a href="<?= admin_url("category/categories") ?>">
                <i class="mdi mdi-format-list-bulleted"></i> &nbsp;<span>
                            <?= Translate::sprint("Categories") ?></span>
            </a>
        </li>
    <?php } ?>



    <?php if ($this->mUserBrowser->getData("typeAuth") != "manager" && ($this->mUserBrowser->getData("manager") == 1 || DEMO == TRUE)) { ?>
        <li class="treeview <?php if ($uri_parent == "application" || $uri_parent == "currencies") echo "active"; ?>">
            <a href="<?= admin_url("application") ?>"><i class="mdi mdi-settings"></i> &nbsp;
                <span> <?= Translate::sprint("Application") ?></span>

                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>

            <ul class="treeview-menu">
                <li class="<?php if ($uri_parent == "application") echo "active"; ?>">
                    <a href="<?= admin_url("setting/application") ?>"><i class="mdi  mdi-settings"></i>
                        &nbsp;<span> <?= Translate::sprint("config_sidebar", "Config") ?></span></a>
                </li>
                <li class="<?php if ($uri_parent == "currencies") echo "active"; ?>">
                    <a href="<?= admin_url("setting/currencies") ?>"><i class="mdi mdi-currency-eur"></i>
                        &nbsp;<span> <?= Translate::sprint("Currencies") ?></span></a>
                </li>
                <li class="<?php if ($uri_parent == "deeplinking") echo "active"; ?>">
                    <a href="<?= admin_url("setting/deeplinking") ?>"><i class="mdi mdi-link"></i>
                        &nbsp;<span> <?= Translate::sprint("Deep Linking") ?></span></a>
                </li>
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
          