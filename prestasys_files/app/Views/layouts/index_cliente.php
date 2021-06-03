<?php

$TERMINACION_RUC =   substr(session("ruc"), -1, 1);

?>
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
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="icon" href="<?= base_url("assets/img/page_icon.png") ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/jqvmap/dist/jqvmap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/assets/css/style.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/fontawesome5.15.1.min.css") ?>">
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


        /**Espacio entre opciones del menu  */
        .navbar .navbar-nav li.menu-item-has-children a {
            line-height: 3px;
        }

        h1,
        h2,
        h3,
        h4,
        h4,
        h5,
        h6,
        label {
            font-family: mainfont;
        }

        label {
            font-size: 18px !important;
        }

        .navbar,
        aside.left-panel {
            background: #9FD1BB;
            /* url(<?= base_url("assets/ivax/assets/images/homebg2.jpg") ?>);*/

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
            color: #020022 !important;
            font-family: mainfont;
            font-size: 16px;
        }
    </style>





</head>

<body>

    <?= $this->renderSection("estilos") ?>
    <input type="hidden" id="TERMINACION-RUC" value="<?= $TERMINACION_RUC ?>">
    <!-- Left Panel-->

    <aside id="left-panel" class="left-panel">

        <?= view("componentes/SideBar", ['datos' => [
            ['titulo' => 'Comprobantes', 'opciones' => [
                ["titulo" => "Factura de compra", "link" =>  base_url("compra/create") , "icon"=> "fa fa-tasks" ],
                ["titulo" => "Factura de venta", "link" =>  base_url("venta/create"),  "icon"=> "fa fa-tasks"  ],
                ["titulo" => "Retenciones", "link" =>  base_url("retencion/create"),  "icon"=> "fa fa-tasks" ]
            ]],
            [
                'titulo' => 'Informes', 'opciones' => [
                    ["titulo" => "Movimientos del mes", "link" =>  base_url("movimiento/informe_mes"), "icon"=>"fa fa-book"],
                    ["titulo" => "Cierre del mes", "link" =>  base_url("cierres/view-cierre-mes"),  "icon"=> "fa fa-window-close-o" ],
                    ["titulo" => "Resumen del año", "link" =>   base_url("cierres/comparativo-periodos"), "icon"=> "fa fa-list"],
                    ["titulo" => "Comparativo anual", "link" =>  base_url("cierres/comparativo-ejercicios"), "icon"=>"fa fa-table"],
                ]
            ],

            [
                'titulo' => '', 'opciones' => [
                    ["titulo" => "Pagos del IVA", "link" =>   base_url("pagos-iva/index") , "icon"=> "fa fa-money"],
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

                <div class="col-sm-7 col-md-7" id="NOVEDADES">
                    <a id="menuToggle" class="menutoggle pull-left d-none"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">




                    </div>
                </div>

                <div class="col-sm-5 col-md-5">
                    <div class="user-area dropdown float-right">
                        <a style="color: black; text-transform: uppercase;" href="<?= base_url("usuario/update/" . session("id")) ?>" aria-haspopup="true" aria-expanded="false">
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

        <div class="content mt-3 p-0" id="contenido-cliente">

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
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }



        async function recoger_novedades_vencimiento_iva() {
            let terminacion = $("#TERMINACION-RUC").val();
            let fetching_Data = async function(term) {
                let req = await fetch("<?= base_url('usuario/verificar-vencimiento-iva') ?>/" + term);
                let resp = await req.json();
                if ("data" in resp) {
                    let mensaje = resp.data;
                    let notifi = `
       
       <a href='<?= base_url("pagos-iva/index") ?>'>
       <h5 style='font-weight: 600;color:red;font-size: 14px;'>  ${mensaje}
       <img  style='width: 70px;height:50px;margin: 0px;'  src='<?= base_url("assets/img/notificame.gif") ?>' />
       </h5>
       </a>
       
        `;
                    $("#NOVEDADES").html(notifi);
                }
            };

            //  for( let t=0; t <  terminaciones.length ;  t++ ){

            await fetching_Data(terminacion);
            //   await sleep(10000);
            //    }

        }

        recoger_novedades_vencimiento_iva();
    </script>
    <?= $this->renderSection("scripts") ?>






</body>

</html>