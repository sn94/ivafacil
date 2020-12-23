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
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/jqvmap/dist/jqvmap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/assets/css/style.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/fontawesome5.15.1.min.css") ?>">


    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        .navbar,
        aside.left-panel {
            background-image: url(<?=base_url("assets/ivax/assets/images/homebg2.jpg")?>);
           
            background-position: -50% 0%;
            
          
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
            color: #d5fec7 !important;
        }
       
    </style>
</head>

<body>

<?= $this->renderSection("estilos") ?>

    <!-- Left Panel-->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default" >
       
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?=base_url("/")?>"><img src="<?=base_url("assets/img/Logo.jpg")?>" alt="Logo"></a>
                <a class="navbar-brand hidden"  href="<?=base_url("/")?>" ><img src="<?=base_url("assets/img/Logo.jpg")?>" alt="Logo"></a>
            </div>
 
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                     
                    <h3 class="menu-title">Comprobantes</h3>
                
                    <li class="menu-item-has-children dropdown">
                        <a  href="<?=base_url("compra/create")?>"  aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Factura de compra</a>
                        
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a  href="<?=base_url("venta/create")?>"  aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Factura de venta</a>
                        
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a  href="<?=base_url("retencion/create")?>"  aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Retención</a>
                        
                    </li>

                    <h3 class="menu-title">Informes</h3>
                    <li class="menu-item-has-children dropdown">
                        <a  href="<?=base_url("movimiento/informe_mes")?>"  aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Movimientos del mes</a>
                        
                    </li>


                    <li class="menu-item-has-children dropdown">
                        <a href="<?=base_url("cierres/view-cierre-mes")?>"    aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Cierre del mes</a>
                        
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="<?=base_url("cierres/view-cierre-anio")?>"      aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Resumen del año</a>
                        
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="<?=base_url("usuario/sign-out")?>"    aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Cerrar sesión</a>
                        
                    </li>
                    
                   
                </ul>
            </div>    
        </nav>
    </aside> 
  
    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        

                         
 
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a style="color: black; text-transform: uppercase;"  href="<?=base_url("usuario/update/".session("id"))?>"   aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user"></i>   MIS DATOS
                        </a>

                     
                    </div>

                    

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>  -->

        <div class="content mt-3" id="contenido-cliente">
    
        <?= $this->renderSection("contenido") ?>

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <?php

    $base_url_for_resources = base_url() . "/assets/template/";
    ?>
    <script src="<?= $base_url_for_resources ?>vendors/jquery/dist/jquery-3.5.1.min.js"></script>
    <script src="<?= $base_url_for_resources ?>vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?= $base_url_for_resources ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <?= $this->renderSection("scripts") ?>

     
    
 
    

</body>

</html>