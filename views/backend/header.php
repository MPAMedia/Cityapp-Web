<?php
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>

    <base href="<?= base_url() ?>"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Translate::sprint("Dashboard") ?> | <?= APP_NAME ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/bootstrap/css/bootstrap.min.css") ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/select2/select2.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/dist/css/AdminLTE.css") ?>">
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/datatables/dataTables.bootstrap.css") ?>">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/datepicker/datepicker3.css") ?>">
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/iCheck/all.css") ?>">


    <link rel="stylesheet" href="<?= base_url("views/skin/backend/dist/css/skins/skin-light.css") ?>">


    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
          href="<?= base_url("views/skin/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") ?>">


    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/datatables/dataTables.bootstrap.css") ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/minified/themes/default.min.css") ?>"
          type="text/css" media="all"/>


    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/datatables/jquery.dataTables.min.css") ?>"
          type="text/css" media="all"/>


    <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.3.54/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url("views/skin/backend/plugins/colorpicker/bootstrap-colorpicker.css") ?>">
    <link rel="stylesheet" href="<?= base_url("views/skin/backend/custom_skin/style.css") ?>">


    <?php if (Translate::getDir() == "rtl"): ?>
        <link rel="stylesheet" href="<?= base_url("views/skin/backend/custom_skin/rtl.css") ?>">
    <?php endif; ?>


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112054244-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '<?=DASHBOARD_ANALYTICS?>');
    </script>


    <script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
    <?php TemplateManager::loadCssLibs() ?>

    <style>

        .skin-blue .main-header .logo {
            color: <?=DASHBOARD_COLOR?> !important;
        }

        .btn-primary {
            background-color: <?=DASHBOARD_COLOR?>;
            border-color: <?=DASHBOARD_COLOR?>;
            border: 1px solid <?=DASHBOARD_COLOR?>;
        }

        .bg-primary {
            background-color: <?=DASHBOARD_COLOR?>;
        }

        .btn-primary:hover {
            border: 1px solid <?=DASHBOARD_COLOR?> !important;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a {
            border-left-color: <?=DASHBOARD_COLOR?>;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary.hover {
            background-color: <?=DASHBOARD_COLOR?> !important;
            /*border: 1px solid #eeeeee !important;*/
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: <?=DASHBOARD_COLOR?> !important;
            border-color: <?=DASHBOARD_COLOR?> !important;
        }

        a {
            color: <?=DASHBOARD_COLOR?>;
        }

        .skin-blue .main-header .navbar .sidebar-toggle {
            color: <?=DASHBOARD_COLOR?>;
        }

        .skin-blue .main-header .navbar .sidebar-toggle:hover {
            background-color: <?=DASHBOARD_COLOR?>;
        }

        .image-uploaded #delete {
            background-color: <?=DASHBOARD_COLOR?>;
        }

        #progress {
            border: 1px solid<?=DASHBOARD_COLOR?>;
        }

        #progress .percent {
            background: <?=DASHBOARD_COLOR?>;
        }

        .direct-chat-primary .right .direct-chat-text {
            background: <?=DASHBOARD_COLOR?>;
            border-color: <?=DASHBOARD_COLOR?>;
            color: #ffffff;
        }

        .nsup-btn {
            background: <?=DASHBOARD_COLOR?>;
        }

        .nsup-btn strong{
            color: #ffffff;
        }


    </style>
    <?php TemplateManager::loadScriptsLibs() ?>

</head>

<body class="hold-transition skin-blue sidebar-mini" dir="<?= __DIR ?>">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <?php if ($this->session->userdata('agent') == "mobile") { ?>

        <?php } else { ?>
            <a href="<?= admin_url("") ?>" class="logo">

                <span class="logo-lg"> <b style="text-transform: uppercase"><?= strtoupper(APP_NAME) ?></b></span>
                <span class="logo-mini"></span>

            </a>
        <?php } ?>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= Translate::sprint("Toggle navigation", "") ?></span>
            </a>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                    <?php if (ModulesChecker::isEnabled('pack')): ?>
                        <?php

                        $expired_date = $this->mUserBrowser->getData('will_expired');
                        $days = MyDateUtils::getDays($expired_date);


                        $grp_access_id = $this->mUserBrowser->getData('grp_access_id');
                        $pack_id = $this->mUserBrowser->getData('pack_id');


                        ?>

                        <?php if ($grp_access_id!=1): ?>
                            <li class="messages-menu no-hover">
                                <a class="no-hover font-size12px">
                                    <?php if ($days > 7): ?>
                                        <i class="mdi mdi-account font-size18px"></i> <?= Translate::sprintf("Your pack will expired after %s days", array($days)) ?>.
                                        <?php if ($this->mPack->canUpgrade()): ?>
                                            <u class="text-blue cursor-pointer"
                                               onclick="location.href = '<?= site_url("/pack/pickpack?req=upgrade") ?>';"><?= Translate::sprint("Upgrade") ?></u>
                                        <?php endif; ?>
                                    <?php elseif ($days < 7 && $days > 0): ?>
                                        <i class="fa fa-warning text-yellow font-size18px"></i> <?= Translate::sprintf("Your pack will expired after %s days", array($days)) ?>.
                                        <u class="text-blue cursor-pointer"
                                           onclick="location.href = '<?= admin_url("pack/renew") ?>';"><?= Translate::sprint("Renew") ?></u>
                                    <?php else: ?>
                                        <i class="fa fa-warning text-red font-size18px"></i> <?= Translate::sprint("Your pack has been expired") ?>.
                                        <u class="text-blue cursor-pointer"
                                           onclick="location.href = '<?= admin_url("pack/renew") ?>';"><?= Translate::sprint("Renew") ?></u>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php elseif ($grp_access_id!=1 && $pack_id == 0): ?>
                            <li class="messages-menu no-hover">
                                <a class="no-hover font-size12px">
                                    <i class="fa fa-warning text-red font-size18px"></i> <?= Translate::sprint("Your account is not for business") ?>
                                    .&nbsp;&nbsp;
                                    <u class="text-blue cursor-pointer"
                                       onclick="location.href = '<?= site_url("pack/pickpack?req=upgrade") ?>';"><?= Translate::sprint("Upgrade to business") ?></u>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="messages-menu no-hover">
                                <a class="no-hover font-size12px">
                                    <i class="mdi mdi-account font-size18px"></i> <?= Translate::sprintf("Your are connected as admin") ?>
                                    .
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (GroupAccess::isGranted('user',USER_ADMIN)) { ?>
                        <?php

                        $this->load->model("campaign/campaign_model");
                        $nbrCampaigns = 0;
                        $nbrCampaigns = $this->campaign_model->getPendingCampaigns();

                        if ($nbrCampaigns > 0) {
                            ?>
                            <li class=" messages-menu">
                                <a href="<?= admin_url("campaign/campaigns?status=-1") ?>">
                                    <i class="fa fa-paper-plane"></i>
                                    <span class="label label-warning"><?= $nbrCampaigns ?></span>
                                </a>
                            </li>
                        <?php } ?>

                    <?php } ?>

                    <!-- Control Sidebar Toggle Button -->

                    <?php
                    $languages = Translate::getLangsCodes();
                    $langName = "";
                    foreach ($languages as $key => $lng) {
                        if (Translate::getDefaultLang() == $key)
                            $langName = strtoupper($key) . "-" . $lng['name'];
                    }
                    ?>
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="mdi mdi-flag"></i> &nbsp;<?= Translate::sprint("Language", "") ?>
                            : <?= $langName ?>
                        </a>

                        <ul class="dropdown-menu">
                            <?php

                            foreach ($languages as $key => $lng) {
                                echo ' <li>
                    <ul class="menu">
                      <li>
                        <a href="' . site_url("setting/language") . "?lang=" . $key . '">' . strtoupper($key) . '-' . $lng['name'] . ' </a>
                      </li>
                    </ul>
                  </li>';
                            }
                            ?>

                        </ul>

                    </li>

                    <li class=" dropdown user user-menu">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">

                            <?php

                            //prepare image data
                            $userImage = $this->mUserBrowser->getData("images");
                            if ($userImage != "") {
                                $userImage = json_decode($userImage, JSON_OBJECT_AS_ARRAY);
                            }

                            $dc = $userImage;
                            if (!is_array($userImage) and $dc != "") {
                                $userImage = array();
                                $userImage[] = $dc;
                            }


                            //get image url
                            $imageUrl = base_url("views/skin/backend/images/place-holder-160.png");

                            if (!empty($userImage)) {
                                $userImage = _openDir($userImage[0]);
                                if (!empty($userImage))
                                    $imageUrl = $userImage['200_200']['url'];

                            }

                            ?>


                            <img src="<?= $imageUrl ?>" class="user-image" alt="User Image">
                            <span class="hidden-xs">
                     <?= $this->mUserBrowser->getAdmin("name") ?>
                  </span>
                        </a>

                    </li>

                    <li class=" dropdown notifications-menu ">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-sliders"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <!-- <li class="header"><? /*=Translate::sprint("Role Type","")*/ ?> : <? /*=$this->mUserBrowser->getAdmin("typeAuth")*/ ?></li>-->
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li>
                                        <a href="<?= admin_url("user/profile") ?>"><i class="fa fa-pencil"></i>&nbsp;&nbsp;<?= Translate::sprint("Profile", "") ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= site_url("user/logout") ?>"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;<?= Translate::sprint("Logout", "") ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                    </li>


                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->


            <!-- search form (Optional) -->
            <!--
            <form action="#" method="get" class="sidebar-form">
              <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Chercher...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
            -->
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <?php $this->load->view("backend/sidebar"); ?>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>


