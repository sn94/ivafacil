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
    <title>Sufee Admin - HTML5 Admin Template</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/themify-icons/css/themify-icons.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/flag-icon-css/css/flag-icon.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/selectFX/css/cs-skin-elastic.css") ?>">

    <link rel="stylesheet" href="<?= base_url("assets/template/assets/css/style.css") ?>">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>


  

</head>

<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap"  style="background-color: #a5df99;">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                    <img src="<?=base_url("assets/img/Logo.jpg")?>" alt="Logo">
                    </a>
                </div>
                <div class="login-form">

                    <?php
                    if (isset($errorSesion)) {
                        echo view("plantillas/error", array("mensaje" => $errorSesion));
                    }
                    ?>
                    <form  action="<?= base_url('usuario/sign_in') ?>" method="POST">
                        <div class="form-group">
                            <label style="font-weight: 600;color: #555555;">RUC</label>
                            <input type="text" name="RUC" class="form-control" placeholder="RUC">
                        </div>
                        <div class="form-group">
                            <label  style="font-weight: 600;color: #555555;">Password</label>
                            <input type="password" name="PASS" class="form-control" placeholder="Password">
                        </div>
                       
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Siguiente</button>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> <span  style="font-weight: 600;color: #555555;">Recordar Número de RUC o contraseña</span>
                            </label>
                            <label class="pull-right">
                                <a style="color: #fd4040; font-weight: 600;" href="#">Olvidaste tu contraseña?</a>
                            </label>

                        </div>
                        <a   href="<?=base_url("usuario/registro")?>" class="btn btn-warning btn-flat m-b-30 m-t-30">Registrarse</a>
                        
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