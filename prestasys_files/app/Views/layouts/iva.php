<?php

$base_url_for_resources = base_url() . "/assets/ivax/";
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>IVA FÁCIL</title>
    <meta name="description" content="gestion de IVA">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?= base_url("assets/img/Logo.jpg") ?>" />

    <!--<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,600,700' rel='stylesheet' type='text/css'>-->

    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/fonticons.css">
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/fonts/stylesheet.css">
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/bootstrap.min.css">
    <!--        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">-->


    <!--For Plugins external css-->
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/plugins.css" />

    <!--Theme custom css -->
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/style.css">

    <!--Theme Responsive css-->
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/responsive.css" />

    <script src="<?= $base_url_for_resources ?>assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>



    <style>
        /* style.css | http://localhost/ivafacil/assets/ivax/assets/css/style.css */

        .main_menu_bg .navbar-nav>li>a {
            font-weight: 600;
            color: aliceblue !important;
        }

        /* Elemento | http://localhost/ivafacil/usuario/t */

        .main_menu_bg {
            background-color: #0007;
            border: none;
        }

        .sections {
            margin-top: 50px;
            margin-top: 0px;
        }

       
        .footer {
            /* background: #2a2a2a; */
            background: #21220b;
        }

        .footer a,
        p {
            font-weight: 600;
            font-size: 26px;
        } 

        .fa-phone {
            font-weight: 600;
            font-size: 26px;
        }
 

 
 

.single_slider h2 {
 
color: #7adb84;
}
.single_slider p { 
color: #7adb84;
}

 
.home-overlay-fluid { 
  background-color: rgba(0, 0, 0, 0.83);
}


    </style>
</head>

<body data-spy="scroll" data-target="#navmenu">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class='preloader'>
        <div class='loaded'>&nbsp;</div>
    </div>

    <?= $this->renderSection("section_menu") ?>
    <?= $this->renderSection("section_presentacion") ?>


    <?= ""
    //$this->renderSection("section_plans") 
    ?>
    <?= ""
    //$this->renderSection("section_comments") 
    ?>

    <?= ""
    // $this->renderSection("section_services") 
    ?>

    <?= $this->renderSection("section_footer") ?>














    <!-- STRAT SCROLL TO TOP -->

    <div class="scrollup">
        <a href="#"><i class="fa fa-chevron-up"></i></a>
    </div>

    <script src="<?= $base_url_for_resources ?>assets/js/vendor/jquery-1.11.2.min.js"></script>
    <script src="<?= $base_url_for_resources ?>assets/js/vendor/bootstrap.min.js"></script>
    <script src="<?= $base_url_for_resources ?>assets/js/jquery.easypiechart.min.js"></script>

    <script src="<?= $base_url_for_resources ?>assets/js/plugins.js"></script>
    <script src="<?= $base_url_for_resources ?>assets/js/main.js"></script>

</body>

</html>