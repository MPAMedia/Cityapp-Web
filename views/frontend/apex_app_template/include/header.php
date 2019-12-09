<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=APP_NAME?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/icon" href="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/assets/images/favicon.ico")?>"/>
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/assets/css/bootstrap.min.css")?>" rel="stylesheet">
    <!-- Slick slider -->
    <link href="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/assets/css/slick.css")?>" rel="stylesheet">
    <!-- Theme color -->
    <link id="switcher" href="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/assets/css/theme-color/red-theme.css")?>" rel="stylesheet">


    <!-- Main Style -->
    <link href="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/style.css")?>" rel="stylesheet">

    <!-- Fonts -->

    <!-- Open Sans for body and title font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112054244-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?=DASHBOARD_ANALYTICS?>');
    </script>

</head>
<body>


<!-- Start Header -->
<header id="mu-header" class="" role="banner">
    <div class="mu-header-overlay">
        <div class="container">
            <div class="mu-header-area">

                <!-- Start Logo -->
                <div class="mu-logo-area">
                    <!-- text based logo -->
                    <a class="mu-logo" href="#">
                        <img src="<?=base_url("views/skin/frontend/".FRONTEND_TEMPLATE_NAME."/assets/images/logo-nearby-stores-fend.png")?>"" alt="<?=APP_NAME?>"/>
                    </a>

                    <u class="menu">
                        <li><a class="active" href="https://codecanyon.net/item/nearbystores-offers-events-chat-realtime/21251567?ref=DroideveTechnology">Buy now</a></li>
                        <?php if($this->mUserBrowser->isLogged()): ?>
                            <li><a href="<?=admin_url("")?>">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?=site_url("user/login")?>">LOG IN</a></li>
                        <?php endif;?>

<!--                        <li><a href="#mu-faq">FAQ</a></li>-->
                        <li><a href="https://play.google.com/store/apps/details?id=com.droideve.apps.nearbystores" >Download</a></li>
                        <li><a href="#mu-apps-screenshot">Screenshots </a></li>
                        <li><a href="#mu-feature">Features</a></li>
                    </u>
                    <!-- image based logo -->
                    <!-- <a class="mu-logo" href="#"><img src="assets/images/logo.png" alt="logo img"></a> -->
                </div>
                <!-- End Logo -->
                <!-- Start header featured area -->
                <div class="mu-header-featured-area">

                    <div class="row">
                        <div class="col-sm-6 mu-header-featured-img">
                            <img src="<?=base_url('views/skin/frontend/'.FRONTEND_TEMPLATE_NAME)?>/assets/images/screenshot/first_screen.png" alt="iphone image"/>
                        </div>
                        <div class="col-sm-6  mu-header-featured-content">
                            <h1>Welcome To <span><i><u><?=APP_NAME?></u> </i></span></h1>
                            <p>
                               <?=Translate::sprint("Description_app","")?>

                                </p>
                            <div class="mu-app-download-area">
                                <h4>Download The App</h4>

                                <a class="mu-apple-btn" href="#"><i class="fa fa-apple"></i><span>apple store</span></a>
                                <a class="mu-google-btn" target="_blank" href="https://play.google.com/store/apps/details?id=com.droideve.apps.nearbystores"><i class="fa fa-android"></i><span>google play</span></a>
                                <!-- <a class="mu-windows-btn" href="#"><i class="fa fa-windows"></i><span>windows store</span></a> -->
                            </div>

                        </div>


                    </div>



                </div>
                <!-- End header featured area -->

            </div>
        </div>
    </div>
</header>
<!-- End Header -->

<!-- Start Menu -->
<button class="mu-menu-btn">
    <i class="fa fa-bars"></i>
</button>
<div class="mu-menu-full-overlay">
    <div class="mu-menu-full-overlay-inner">
        <a class="mu-menu-close-btn" href="#"><span class="mu-line"></span></a>
        <nav class="mu-menu" role="navigation">
            <ul>
                <?php if(DEMO==TRUE): ?>
                <li><a class="active" href="#" onclick="redirect('https://codecanyon.net/item/nearbystores-offers-events-chat-realtime/21251567&ref=DroideveTechnology')">Buy now</a></li>
                <?php endif;?>
                <?php if($this->mUserBrowser->isLogged()): ?>
                    <li><a href="#"  onclick="redirect('<?=admin_url("")?>')">Dashboard</a></li>
                <?php else: ?>
                    <li><a href="#"  onclick="redirect('<?=admin_url("user/login")?>')">LOG IN</a></li>
                <?php endif;?>

                <!--                        <li><a href="#mu-faq">FAQ</a></li>-->
                <li><a href="#"  onclick="redirect('https://play.google.com/store/apps/details?id=com.droideve.apps.nearbystores')" >Download</a></li>
                <li><a href="#mu-apps-screenshot">Screenshots </a></li>
                <li><a href="#mu-feature">Features</a></li>
            </ul>
        </nav>
    </div>
</div>
<!-- End Menu -->

