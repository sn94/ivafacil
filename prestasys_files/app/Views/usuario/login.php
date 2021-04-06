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
    <title>Login</title>
    <meta name="description" content="Iva facil">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="icon" href="<?=base_url("assets/img/page_icon.png")?>" type="image/png">

    
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
                <div class="login-logo bg-light "  style="margin-bottom: 0px;">
                    <a href="index.html">
                        <img style="width: 300px;height: 109px;" src="<?= base_url("assets/img/Logo.png?".date('is')) ?>" alt="Logo">
                    </a>
                </div>
                <div class="login-form">

                    <?php echo view("plantillas/message");  ?>
                    <form action="<?= base_url('usuario/sign-in') ?>" method="POST">


                        <div class="row mb-1">
                            <div class="col-1 col-md-1 ">
                                <label style="font-family: mainfont;font-weight: 600;color: #303030  !important ;">RUC:</label>
                            </div>
                            <div class="col-6 col-md-8">
                                <input oninput="obtener_dv( event)" value="<?= isset($ruc) ? $ruc : '' ?>" maxlength="15" type="text" name="ruc" class="  form-control form-control-label   ">
                            </div>

                            <div class="col-1 col-md-1 ml-0 pl-0 ">
                                <label style="font-family: mainfont;font-weight: 600;color: #303030  !important ;">DV:</label>
                            </div>
                            <div class="col-3 col-md-2">
                                <input value="<?= isset($dv) ? $dv : '' ?>" maxlength="2" oninput="solo_numero(event)" type="text" name="dv" class="  form-control form-control-label  ">
                            </div>
                        </div>



                        <div class="form-group">
                            <label style="font-family: mainfont;font-weight: 600;color: #303030  !important ;">Password</label>
                            <input value="<?= isset($pass_alt) ? $pass_alt  : '' ?>" type="password" name="pass" class="form-control" placeholder="Password">
                        </div>




                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">INGRESAR</button>
                        <div class="checkbox">
                            <label>
                                <input onchange="olvidar(event)" <?= isset($remember) ? 'checked' : '' ?> type="checkbox" name="remember" value="S">
                                <span style="font-weight: 600;color: #555555;font-family: mainfont;">Recordar Número de RUC y contraseña</span>
                            </label>
                            <label class="pull-right">
                                <a style="color: #fd4040; font-weight: 600;font-family: mainfont;" href="<?= base_url("usuario/olvido-password") ?>">Olvidaste tu contraseña?</a>
                            </label>

                        </div>
                        <a href="<?= base_url("usuario/create") ?>" class="btn btn-secondary btn-flat m-b-30 m-t-30">Registrarse</a>
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
        function olvidar(ev) {

            if (!ev.target.checked)
                document.querySelector("input[name=pass]").value = "";
        }



        function obtener_dv(ev) {
            if (ev.target.value == "") document.querySelector("input[name=dv]").value = "";

            if (ev.data == undefined || ev.data == null) return;
            let cad = calcular_digito_verificador(ev.target.value, 11);
            document.querySelector("input[name=dv]").value = cad;
        }

        function calcular_digito_verificador(tcNumero, tnBaseMax) {
            let lcNumeroAl, i, lcCaracter, k, lnTotal, lnNumeroAux, lnResto, lnDigito;
            lcNumeroAl = ""

            for (let i = 0; i < tcNumero.length; i++) {
                lcCaracter = tcNumero.substr(i, 1).toUpperCase();
                if (lcCaracter.charCodeAt() < 48 || lcCaracter.charCodeAt() > 57)
                    lcNumeroAl = lcNumeroAl + String(lcCaracter);
                else
                    lcNumeroAl = lcNumeroAl + lcCaracter;
            }
            console.log("lcNumeroAL", lcNumeroAl);

            k = 2;
            lnTotal = 0;
            for (i = lcNumeroAl.length - 1; i >= 0; i--) {
                if (k > tnBaseMax)
                    k = 2;

                lnNumeroAux = parseInt(lcNumeroAl.substr(i, 1)); //VAL
                lnTotal = lnTotal + (lnNumeroAux * k);
                k = k + 1
            }
            lnResto = lnTotal % 11;
            if (lnResto > 1)
                lnDigito = 11 - lnResto;
            else
                lnDigito = 0;
            return lnDigito;

        }
    </script>
</body>

</html>