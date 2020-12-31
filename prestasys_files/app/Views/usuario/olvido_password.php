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
    <title>Olvido de password</title>
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


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img src="<?= base_url("assets/img/Logo.jpg") ?>" alt="Logo">
                    </a>
                </div>
                <div class="login-form">


                    <?php echo view("plantillas/message");  ?>
                    <form onsubmit="recuperar(event)" action="<?= base_url('usuario/olvido-password') ?>" method="POST">


                        <h4 style="font-family: mainfont;">Ingrese el email asociado a su cuenta, en breve recibirá un link de recuperación</h4>

                        <div id="loaderplace"></div>


                        <div class="row mb-1" id="login-form">
                            <div class="col-1 col-md-1 ">
                                <label style="font-weight: 600;color: #555555;">Email:</label>
                            </div>
                            <div class="col-6 col-md-8">
                                <input maxlength="120" type="text" name="email" class="  form-control form-control-label   ">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Recuperar</button>
                            </div>

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
        function show_loader() {
            let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
            document.getElementById("loaderplace").innerHTML = loader;
        }

        function hide_loader() {
            document.getElementById("loaderplace").innerHTML = "";
        }
        async function recuperar(ev) {


            ev.preventDefault();
            let email = document.querySelector("input[name=email]").value;
            let datos = "email=" + email;
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

                let mensaje = '  <h5 style="color: green;font-family: mainfont;">Enviado! Por favor revise su bandeja de correo</h5>';
                document.getElementById("loaderplace").innerHTML = mensaje;
                document.getElementById("login-form").innerHTML = "";
            } else {
                alert(respuesta.msj);
            }
        }
    </script>
</body>

</html>