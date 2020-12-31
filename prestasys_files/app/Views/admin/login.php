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


@import url("<?=base_url('assets/Marvel-Regular.ttf')?>");

@font-face{
    font-family: "mainfont";
    src: url("<?=base_url('assets/Marvel-Regular.ttf')?>");
    
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
            background: #5491320f !important;
        
        }

        /* bootstrap.min.css | http://localhost/ivafacil/assets/template/vendors/bootstrap/dist/css/bootstrap.min.css */

        .bg-light {
            /* background-color: #f8f9fa !important; */
            background-color: none !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        div.row:nth-child(1) {
            /* background-color: #dfe8df; */
            background-color: #18594d00 !important;
        }

        /* Elemento | http://localhost/ivafacil/admin/sign-in */

        form.pt-0 {
            background-color: #2cff0000 !important;
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
            color: #ffe140 !important;
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
  html{
    height: 100% !important; 
     background-size: cover; 
     background-image: url(<?= base_url("assets/img/spring-wallpaper.jpg") ?>);
     background-position: 100% 50%;
    }

</style>

    <?php
    else : ?>

<style>
    
    html{
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


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">



                <div class="login-form pt-0  bg-light ">

                    <?php echo view("plantillas/message");  ?>
                    <form class="pt-0 pr-0 m-0 bg-light pb-2" action="<?= base_url('admin/sign-in') ?>" method="POST">

                        <div class="row m-0 " style="background-color: #dfe8df;">
                            <div class="col-12 col-md-6">
                                <div class="login-logo">
                                    <a href="index.html">
                                        <img src="<?= base_url("assets/img/Logo.jpg") ?>" alt="Logo">
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 align-self-center">
                                <h4   style="font-family: mainfont;font-size: 40px;color: #428e20;" class="">ADMINISTRACIÓN</h4>
                            </div>
                        </div>




                        <div class="row mb-1">
                            <div class="col-12">
                                <label style="font-weight: 600;color: #555555;">Usuario:</label>
                            </div>
                            <div class="col-12">
                                <input value="<?= isset($nick) ? $nick : '' ?>" maxlength="15" type="text" name="nick" class="  form-control form-control-label   ">
                            </div>
                        </div>



                        <div class="form-group">
                            <label style="font-weight: 600;color: #555555;">Password</label>
                            <input value="<?= isset($pass_alt) ? $pass_alt  : '' ?>" type="password" name="pass" class="form-control" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">INGRESAR</button>
                        <div class="checkbox">
                            <label>
                                <input <?= isset($remember) ? 'checked' : '' ?> type="checkbox" name="remember" value="S"> <span style="font-weight: 600;color: #555555;">Recordar contraseña</span>
                            </label>
                            <label class="pull-right">
                                <a style="color: #fd4040; font-weight: 600;" href="<?=base_url("admin/olvido-password")?>">Olvidaste tu contraseña?</a>
                            </label>

                        </div>


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