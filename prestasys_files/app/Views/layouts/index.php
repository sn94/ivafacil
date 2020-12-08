<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IVA FÁCIL</title>
 
  <meta http-equiv="Cache-Control" content="no-cache" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta name="description" content="iva">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-icon.png">
  <link rel="shortcut icon" href="favicon.ico">

  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">
  <link rel="stylesheet" href="<?= base_url("assets/template/vendors/jqvmap/dist/jqvmap.min.css") ?>">



  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
  <style>
    .navbar,
    aside.left-panel {
      background: #a5df99 !important;
    }

    .navbar .navbar-brand,
    .navbar .navbar-nav li>a,
    .navbar .navbar-nav li>a .menu-icon,
    .navbar .menu-title,
    .navbar .navbar-nav>.active>a,
    .navbar .navbar-nav>.active>a:focus,
    .navbar .navbar-nav>.active>a:hover,
    .navbar .navbar-nav li.active .menu-icon,


    .navbar .navbar-nav li:hover .toggle_nav_button::before,
    .navbar .navbar-nav li .toggle_nav_button.nav-open::before {
      color: #0a2601 !important;
    }
  </style>
</head>

<body>


  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?=base_url("/")?>">
      <img src="<?= base_url("assets/img/Logo.jpg") ?>" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
          <a class="nav-link" href="<?=base_url("/")?>">Inicio </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="<?=base_url("movimiento/index")?>">Comprobantes </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=base_url("movimiento/informe_mes")?>">Movimientos del mes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" style="  font-weight: 600;color: #422efa;" href="<?=base_url("usuario/sign_out")?>">Cerrar sesión</a>
        </li>
      </ul>
      <span class="navbar-text" style="color:black; background-color: white; font-weight: 600;border-radius: 20px;border: 2px dashed  #057e30; padding: 2px 5px !important;">
      <i class="fa fa-user"></i>
     <?= session("ruc")."-".session("dv")?>
      </span>
    </div>
  </nav>

  <!-- Right Panel -->

  <div class="container">


    <div class="content mt-3 p-0">

      <?= $this->renderSection("contenido") ?>

    </div> <!-- .content -->
  </div><!-- /#right-panel -->

  <!-- Right Panel -->

  <?php

  $base_url_for_resources = base_url() . "/assets/template/";
  ?>
  <script src="<?= $base_url_for_resources ?>vendors/jquery/dist/jquery.min.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/chart.js/dist/Chart.bundle.min.js"></script>
  <script src="<?= $base_url_for_resources ?>assets/js/dashboard.js"></script>
  <script src="<?= $base_url_for_resources ?>assets/js/widgets.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/jqvmap/dist/jquery.vmap.min.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <script src="<?= $base_url_for_resources ?>vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script>
    (function($) {
      "use strict";

      jQuery('#vmap').vectorMap({
        map: 'world_en',
        backgroundColor: null,
        color: '#ffffff',
        hoverOpacity: 0.7,
        selectedColor: '#1de9b6',
        enableZoom: true,
        showTooltip: true,
        values: sample_data,
        scaleColors: ['#1de9b6', '#03a9f5'],
        normalizeFunction: 'polynomial'
      });
    })(jQuery);
  </script>

</body>

</html>