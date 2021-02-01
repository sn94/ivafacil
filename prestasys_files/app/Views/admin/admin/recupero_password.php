<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en" style="height: 100% !important; background-image: url(<?= base_url("assets/ivax/assets/images/homebg.jpg") ?>);">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Actualizar password</title>
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
        @import url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        @font-face {
            font-family: "mainfont";
            src: url("<?= base_url('assets/Marvel-Regular.ttf') ?>");

        }


        .form-control {
            border: none;
            border-bottom: 1px solid #0f0;
        }


        body {
            line-height: unset;
            height: 100%;
        }
    </style>


</head>

<body style="background-color: #000000e0;">


<input type="hidden" id="inicio-sesion" value="<?=base_url("admin/sign-in")?>">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img src="<?= base_url("assets/img/Logo.jpg?".date('is'))?>" alt="Logo">
                    </a>
                </div>
                <div class="login-form">

                    <?php echo view("plantillas/message");  ?>
                    <div id="loaderplace"></div>
                    
                    <?php  if( !isset($error)  ): ?>
                    <form id="login-form"  action="<?= base_url('admin/recuperar-password') ?>" method="POST">

                    <h4 style="font-family: mainfont;">Actualizar contraseña</h4>

                        <div class="row mb-1" >

                      
                            <input type="hidden" name="token_recu" value="<?= $usuario->token_recu ?>">
                            <div class="col-3 col-md-3 ">
                                <label style="font-weight: 600;color: #555555;">Nueva contraseña:</label>
                            </div>
                            <div class="col-9 col-md-9">
                                <input maxlength="120" type="password" id="pass1" name="pass" class="  form-control form-control-label   ">
                            </div>
                            <div class="col-3 col-md-3 ">
                                <label style="font-weight: 600;color: #555555;">Repetir contraseña:</label>
                            </div>
                            <div class="col-9 col-md-9">
                                <input maxlength="120" type="password" id="pass2" class="  form-control form-control-label   ">
                                <p style="color: red;font-weight: 600;" id="wrong-pass"></p>
                            </div>


                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Actualizar contraseña</button>

                        <a href="<?= base_url("home") ?>" class="badge badge-success">PÁGINA PRINCIPAL</a>

                    </form>

                    <?php  endif; ?>
                </div>
            </div>
        </div>
    </div>


    <script src="<?= base_url("assets/template/vendors/jquery/dist/jquery.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/popper.js/dist/umd/popper.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/vendors/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
    <script src="<?= base_url("assets/template/assets/js/main.js") ?>"></script>

    <script>

function show_loader() {
            let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
            document.getElementById("loaderplace").innerHTML = loader;
        }

        function hide_loader() {
            document.getElementById("loaderplace").innerHTML = "";
        }
        async function recuperar(ev) {


            ev.preventDefault();
            let pass = document.querySelector("input[name=pass]").value;
            let pass2 = document.getElementById("pass2").value;
            if( pass != pass2) {  document.getElementById("wrong-pass").textContent= "Las contraseñas deben ser iguales"; return ;}
            let datos = "pass=" + pass;
            show_loader();
            let req = await fetch(ev.target.action, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: datos
            });
            let respuesta = await req.json();
            hide_loader();
            if (("data" in respuesta) && parseInt(respuesta.code) == 200) {

                let mensaje = '  <h5 style="color: green;font-family: mainfont;">Actualizado!  </h5>';
                document.getElementById("loaderplace").innerHTML = mensaje;
                document.getElementById("login-form").innerHTML = "";
                window.location=  document.getElementById("inicio-sesion").value;
            } else {
                alert(respuesta.msj);
            }
        }

    </script>

</body>

</html>