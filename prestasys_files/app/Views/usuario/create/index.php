<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IVA FÁCIL</title>

    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta name="description" content="iva">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url("assets/img/page_icon.png") ?>" type="image/png">


    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="<?= base_url("assets/css/custom.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>


    <style>
        .empty-field {
            border: 2px solid #ed2328;
            /*background-color: #ff9595;*/
        }

        .password-ok {
            background-image: url(<?= base_url("assets/img/ok.png") ?>);
            background-repeat: no-repeat;
            background-position: right;
            background-clip: border-box;
            background-size: contain;
        }

        .password-wrong {
            background-image: url(<?= base_url("assets/img/error.png") ?>);
            background-repeat: no-repeat;
            background-position: right;
            background-clip: border-box;
            background-size: contain;
        }


        html, body{
            height: 100vh !important;
            padding: 0 !important;
            margin: 0 !important;
        }

      body{
        background-image: url(<?= base_url("assets/ivax/assets/images/homebg.jpg") ?>) !important;
   
      }
    </style>




</head>

<body >


    <!-- Right Panel -->


    <div class="container-fluid m-0 p-0" style="background-color: #000000ba;position: absolute !important;"> 


        <div id="message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="background-color: var(--color-neutro-1) !important;">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="message-modal-content" class="text-center p-2" style="font-weight: 600;">
                    </div>
                </div>
            </div>
        </div>



        <div class="container mt-2 mb-5">
            <div id="loaderplace">
            </div>

            <!-- Menu de Usuario -->
            <div class="row">

                <div class="col-12">
                    <?= view("plantillas/message") ?>

                </div>
                <div class="col-12 offset-md-1 col-md-10 p-0">

                    <div class="card text-light" style="background-color: var(--color-neutro-1);">

                        <div class="card-header" style="background-color:  #ffffff !important;">
                            <div class="row">
                                <div class="col-6">
                                    <img style="width: 300px; height: auto;" class="img-responsive" src="<?= base_url("assets/img/Logo.png?" . date('is')) ?>" alt="Logo">
                                </div>
                                <div class="col-6"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php

                            echo  form_open(
                                "usuario/create",
                                [
                                    'id' => 'user-form',
                                    'class' => 'container p-0 p-md-2',
                                    'onsubmit' => 'registro(event)'
                                ]
                            ); ?>

                            <?= view("usuario/update/index") ?>

                            <?= view("usuario/create/footer") ?>
                            </form>
                        </div>



                        <div class="card-footer">
                            ¿Ya tienes cuenta?
                            <a style="font-size: 12px;font-weight: 600;" href="<?= base_url("usuario/sign-in") ?>" class="btn btn-secondary btn-sm">
                                Ingresa aquí
                            </a>
                        </div>

                    </div>

                </div>

            </div>


           

        </div><!-- /#right-panel -->
    </div>

    <?php

    $base_url_for_resources = base_url() . "/assets/template/";
    ?>

    <script src="<?= $base_url_for_resources ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= $base_url_for_resources ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>


    <?= view("usuario/create/js") ?>
</body>

</html>