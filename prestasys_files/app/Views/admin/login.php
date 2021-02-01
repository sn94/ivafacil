<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en" style="height: 100% !important; background-size: cover; background-image: url(<?= base_url("assets/img/spring-wallpaper.jpg") ?>);">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Login</title>
    <meta name="description" content="Iva facil">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
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
        @import url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        @font-face {
            font-family: "mainfont";
            src: url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        }



        .form-control {
            border: none;
            border-bottom: 1px solid #aaaaaa;
        }

        body {
            line-height: unset;
            height: 100%;
        }



        /* style.css | http://localhost/ivafacil/assets/template/assets/css/style.css */

        .login-form {
            /* background: #ffffff; */
            background: #ffffff !important;

        }

        /* bootstrap.min.css | http://localhost/ivafacil/assets/template/vendors/bootstrap/dist/css/bootstrap.min.css */

        .bg-light {
            /* background-color: #f8f9fa !important; */
            background-color: none !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        div.row:nth-child(1) {
            /* background-color: #dfe8df; */
            background-color: #ffffff !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        form.pt-0 {
            background-color: #ffffff !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        div.row:nth-child(2)>div:nth-child(1)>label:nth-child(1) {
            /* color: #555555; */
            color: #fff !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        .form-group>label:nth-child(1) {
            /* color: #555555; */
            color: #fff !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        .checkbox>label:nth-child(1)>span:nth-child(2) {
            /* color: #555555; */
            color: #fff !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        .pull-right>a:nth-child(1) {
            /* color: #fd4040; */
            color: #ff1c1c !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        div.col-md-6:nth-child(2)>h4:nth-child(1) {
            /* color: #428e20; */
            color: #69a44e !important;
        }



        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        .btn {
            color: #d9d9d9 !important;
            font-size: 16px !important;
            font-weight: 600 !important;
        }
    </style>



    <?php

    use App\Libraries\Mobile_Detect;

    $adaptativo = new Mobile_Detect();
    if ($adaptativo->isMobile()) :
    ?>

        <style>
            html {
                height: 100% !important;
                background-size: cover;
                background-image: url(<?= base_url("assets/img/spring-wallpaper.jpg") ?>);
                background-position: 100% 50%;
            }
        </style>

    <?php
    else : ?>

        <style>
            html {
                height: 100% !important;
                background-size: cover;
                background-image: url(<?= base_url("assets/img/spring-wallpaper.jpg") ?>);
            }
        </style>



    <?php
    endif;
    ?>

</head>

<body style="background: #060606c9;">


    <input type="hidden" id="ADMIN_INDEX" value="<?= base_url("admin/index") ?>">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">



                <div class="login-form pt-0  bg-light ">

                    <?php echo view("plantillas/message");  ?>
                    <form onsubmit="login(event)" class="pt-0 pr-0 m-0 bg-light pb-2" action="<?= base_url('admin/sign-in') ?>" method="POST">

                        <div class="row m-0 " style="background-color: #dfe8df;">
                            <div class="col-12 col-md-12 p-0">
                                <div class="login-logo m-0 p-0">
                                    <a href="index.html">
                                        <img class="m-0 img-responsive" src="<?= base_url("assets/img/Logo_adm.jpg?".date('is')) ?>" alt="Logo">
                                    </a>
                                </div>
                            </div>
                            
                        </div>




                        <div class="row mb-1"   >
                            <div class="col-12">
                                <label style="font-family: mainfont; font-weight: 600;color: #303030 !important ;">Usuario:</label>
                            </div>
                            <div class="col-12">
                                <input value="<?= isset($nick) ? $nick : '' ?>" maxlength="15" type="text" name="nick" class="  form-control form-control-label   ">
                            </div>
                        </div>



                        <div class="form-group"   >
                            <label style="font-family: mainfont;font-weight: 600;color: #303030  !important ;">Password</label>
                            <input value="<?= isset($pass_alt) ? $pass_alt  : '' ?>" type="password" name="pass" class="form-control" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">INGRESAR</button>
                        <div class="checkbox">
                            <label>
                                <input onchange="olvidar(event)" <?= isset($remember) ? 'checked' : '' ?> type="checkbox" name="remember" value="S"> <span style="font-family: mainfont;font-weight: 600;color: #303030  !important ;">Recordar contraseña</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label class="pull-right">
                                <a style="font-weight: 600;font-family: mainfont;" href="<?= base_url("admin/olvido-password") ?>">Olvidaste tu contraseña?</a>
                            </label>

                        </div>
                        <a href="<?= base_url("home") ?>" class="badge badge-success">PÁGINA PRINCIPAL</a>


                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="<?= base_url("assets/template/vendors/jquery/dist/jquery.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/popper.js/dist/umd/popper.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/assets/js/main.js") ?>"></script>

    <script>
        $ = jQuery;

        function olvidar(ev) {

            if (!ev.target.checked)
                document.querySelector("input[name=pass]").value = "";
        }


        async function login(ev) {

            ev.preventDefault();
            let setting = {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-urlencoded"
                },
                body: $(ev.target).serialize()
            };

            let req = await fetch(ev.target.action, setting);
            let resp = await req.json();
            if ("data" in resp)
                window.location = $("#ADMIN_INDEX").val();
            else alert(resp.msj);
        }
    </script>

</body>

</html>