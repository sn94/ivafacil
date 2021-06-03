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
    <title>Admin Panel</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?=base_url("assets/img/page_icon.png")?>" type="image/png">

    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/jqvmap/dist/jqvmap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/assets/css/style.css") ?>">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <script>
        function replaceAll_compat() {
            if (!("replaceAll" in String.prototype)) {
                let replaceAll = function(expre_reg, substitute) {
                    return this.replace(expre_reg, substitute);
                };
                String.prototype.replaceAll = replaceAll;
            }
        }
        replaceAll_compat();
    </script>
    <style>
        @import url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        @font-face {
            font-family: "mainfont";
            src: url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        }

        h1,
        h2,
        h3,
        h4,
        h4,
        h5,
        h6 {
            font-family: mainfont;
        }

        .navbar,
        aside.left-panel {
            background:#9FD1BB;  

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
             
            color: #020022 !important;
            font-family: mainfont;
            font-size: 16px;
        }
    </style>

</head>

<body>

    <?= $this->renderSection("estilos") ?>


    <!-- Left Panel-->

    <aside id="left-panel" class="left-panel">

    <?= view("componentes/SideBar", ['datos' => [
            ['titulo' => 'Principal', 'opciones' => [
                ["titulo" => "Clientes", "link" =>  base_url("admin/clientes") , "icon"=> "fa fa-address-book-o" ]
            ]],
            [
                'titulo' => 'Configuración', 'opciones' => [
                    ["titulo" => "Administradores", "link" =>  base_url("admin/list"), "icon"=> "fa fa-users"],
                    ["titulo" => "Parámetros", "link" =>  base_url("admin/parametros/create"), "icon"=> "fa fa-wrench" ],
                    ["titulo" => "Calendario perpetuo", "link" =>   base_url("admin/calendario"),  "icon"=> "fa fa-calendar"],
                  
                    ["titulo" => "Monedas", "link" =>   base_url("admin/monedas"), "icon"=> "fa fa-usd"],
                    ["titulo" => "Planes", "link" =>    base_url("admin/planes"), "icon"=> "fa fa-pagelines"  ], 
                    ["titulo" => "Cerrar sesión", "link" =>  base_url("usuario/sign-out"), "icon"=> "fa fa-sign-out"]
                ]
            ] 

        ]]) ?>

 
    </aside>

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7" id="NOVEDADES">

                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a style="color: black; text-transform: uppercase;" href="<?= base_url("admin/update/" . session("id")) ?>" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> MIS DATOS
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

        <div class="content mt-0 p-0">

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

    <script>
        async function recoger_novedades() {

            let req = await fetch("<?= base_url('admin/clientes/novedades') ?>");
            let resp = await req.json();
            if ("data" in resp) {
                let notifi = `
                   
                   <a href='<?= base_url("admin/clientes") ?>'>
                   <h4 style='color:red;'>Novedades por cierres de mes
                   <img  style='width: 70px;height:50px;'  src='<?= base_url("assets/img/notificame.gif") ?>' />
                   </h4>
                   </a>
                   
                    `;
                $("#NOVEDADES").html(notifi);

            }
        }


       



        recoger_novedades();
    </script>

    <?= $this->renderSection("scripts") ?>





</body>

</html>