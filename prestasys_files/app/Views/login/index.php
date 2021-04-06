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
    <title>Login</title>
    <meta name="description" content="Iva facil">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">

    <link rel="stylesheet" href="<?= base_url("assets/template/assets/css/style.css") ?>">

    <link rel="stylesheet" href="<?= base_url("assets/template/assets/fonts/fuente1.css") ?>">
   

    <style>
        .form-control {
            border: none;
            border-bottom: 1px solid #0f0;
        }
    </style>


</head>

<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap" style="background-color: #a5df99;">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img src="<?= base_url("assets/img/Logo.jpg") ?>" alt="Logo">
                    </a>
                </div>
                <div class="login-form">

                    <?php    echo view("plantillas/message");  ?>
                    <form action="<?= base_url('usuario/sign_in') ?>" method="POST">
                        <div class="row form-group  ">
                            <div class="col-8 ">
                                <div class="row">
                                    <div class="col-3 col-md-3 ">
                                        <label style="font-weight: 600;color: #555555;">RUC:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input maxlength="15" type="text" id="nf-email" name="ruc" class="  form-control form-control-label   ">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 ml-0">
                                <div class="row">
                                    <div class="col-3 col-md-3 ">
                                        <label style="font-weight: 600;color: #555555;">DV:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input maxlength="2" oninput="solo_numero(event)" type="text" name="dv" class="  form-control form-control-label  ">
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label style="font-weight: 600;color: #555555;">Password</label>
                            <input type="password" name="pass" class="form-control" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Siguiente</button>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> <span style="font-weight: 600;color: #555555;">Recordar Número de RUC o contraseña</span>
                            </label>
                            <label class="pull-right">
                                <a style="color: #fd4040; font-weight: 600;" href="#">Olvidaste tu contraseña?</a>
                            </label>

                        </div>
                        <a href="<?= base_url("usuario/registro") ?>" class="btn btn-warning btn-flat m-b-30 m-t-30">Registrarse</a>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="<?= base_url("assets/template/vendors/jquery/dist/jquery.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/popper.js/dist/umd/popper.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/assets/js/main.js") ?>"></script>


</body>

</html>