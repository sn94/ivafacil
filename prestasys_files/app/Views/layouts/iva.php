<?php

use App\Libraries\Mobile_Detect;

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

    <link rel="icon" href="<?= base_url("assets/img/page_icon.png?v=". rand(0, 1000) * 11 ) ?>" type="image/png">


    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/fonticons.css">
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/fonts/stylesheet.css">
    <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
     <!--      <link rel="stylesheet" href="<?= $base_url_for_resources ?>assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">-->


    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/custom.css?v=<?= rand(0, 1000) * 11 ?>">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/animaciones.css?v=<?= rand(0, 1000) * 11 ?>">
</head>

<body data-spy="scroll" data-target="#navmenu">

 

   
    <?= $this->renderSection("section_presentacion") ?>
    <?= $this->renderSection("section_footer") ?>


    <script src="<?= $base_url_for_resources ?>assets/js/vendor/jquery-1.11.2.min.js"></script>
    <script src="<?= $base_url_for_resources ?>assets/js/vendor/bootstrap.min.js"></script>



</body>

</html>