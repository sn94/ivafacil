<?php

use App\Controllers\Pagos_iva;
use App\Models\Usuario_model;

$NOMBRE_CLIENTE = "";
$DATA_CLI = (new Usuario_model())->find($CLIENTE);
if (!is_null($DATA_CLI))
    $NOMBRE_CLIENTE =   $DATA_CLI->cliente . " RUC: " . $DATA_CLI->ruc . "-" . $DATA_CLI->dv;
?>

<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>


<style>
    .card-header {
        background-color: #d1d1d1;
        border: 1px solid beige;
        border-radius: 15px 15px 0px 0px;
    }


    .card {
        border-radius: 15px 15px 0px 0px;
    }


    h4.text-center {
        color: #646464;
        font-weight: bolder;
    }



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
</style>




<!-- Right Panel -->

<div class="container">


    <div class="content mt-3">


        <div id="message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">PAGO DE IVA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="message-modal-content" class="text-center p-2" style="font-weight: 600;">
                    </div>
                </div>
            </div>
        </div>


        <div id="loaderplace">
        </div>

        <!-- Menu de Usuario -->
        <div class="row">

            <div class="col-12">
                <?= view("plantillas/message") ?>
            </div>



            <div class="col-12 col-md-12 p-0">

                <div class="card">


                    <div class="card-header" style="border-radius: 15px 15px 0px 0px; background-color: #d1d1d1;">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-center"> PAGOS DEL I.V.A: <?= $NOMBRE_CLIENTE ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    
                    </div>

                </div>
                <!--end card-->

            </div>

            <div class="col-12" id="form-pagos">

            </div>

        </div>

        <?php

        $pagos = isset($pagos)  ? $pagos :  [];
        ?>
        <!-- TABLA PAGOS -->
        <div class="row" id="tabla-pagos">
            <?php


            echo view("pago_iva/grill_pagos_pendientes");

            ?>

        </div>
        <!-- end PAGOS -->

        <script>
            /***
                Validaciones js

                **/




            function formatear(ev) {

                if (ev.data == null || ev.data == undefined)
                    ev.target.value = ev.target.value.replaceAll(new RegExp(/[.]*[,]*/g), "");


                if (ev.data != null && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
                    ev.target.value =
                        ev.target.value.substr(0, ev.target.selectionStart - 1) +
                        ev.target.value.substr(ev.target.selectionStart);
                }
                //Formato de millares
                let val_Act = ev.target.value;
                val_Act = val_Act.replaceAll(new RegExp(/[.]*[,]*/g), "");
                let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);
                $(ev.target).val(enpuntos);

                try {
                    if (parseInt(enpuntos) == 0) $(ev.target).val("");
                    else $(ev.target).val(enpuntos);
                } catch (err) {
                    $(ev.target).val(enpuntos);
                }
            }


            function solo_numero(ev) {

                if (ev.data == undefined || ev.data == null) return;
                if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
                    ev.target.value =
                        ev.target.value.substr(0, ev.target.selectionStart - 1) +
                        ev.target.value.substr(ev.target.selectionStart);
                }

            }


            function clean_number(arg) {
                arg.val(arg.val().replaceAll(/(\.|,)/g, ""));
            }

















            //Procesamiento de formulario


            function campos_vacios() {

                if ($("input[name=comprobante]").val() == "" || $("input[name=fecha]").val() == "") {
                    alert("Indique al menos la fecha de pago")
                    return true;
                }

                return false;
            }






            function hide_loader() {
                $("#loaderplace").html("");
            }




            function procesar_errores(err) {
                if (typeof err == "object") {
                    let errs = Object.keys(err);
                    let concat_errs = errs.map(function(it) {
                        return err[it];
                    }).join("<br>");
                    console.log(concat_errs);
                    return concat_errs;
                }
                return err;

            }





            //- GRILLA DE PAGOS 


            function showLoader() {
                let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
                return loader;
            }

            //Actualizar tabla
            async function actualizar_grilla() {

                let MES = $("#pagos-reports select[name=month]").val();
                let ANIO = $("#pagos-reports select[name=year]").val();

                $("#tabla-pagos").html(showLoader());
                let form = await fetch("<?= base_url("pagos-iva/index/" . $CLIENTE) ?>", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },

                });
                let form_R = await form.text();
                $("#tabla-pagos").html(form_R);
                $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
            }


            async function actualizar_grilla_pagos_realizados() {

                 
                $("#tabla-pagos").html(showLoader());
                let form = await fetch("<?= base_url("pagos-iva/index/" . $CLIENTE) ?>/L", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },

                });
                let form_R = await form.text();
                $("#tabla-pagos").html(form_R);
                $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
            }



            async function mostrar_form(ev) {
                ev.preventDefault();
                $("#form-pagos").html(showLoader());
                let form = await fetch(ev.currentTarget.href);
                let form_R = await form.text();
                $("#form-pagos").html(form_R);
                //   $("#message-modal").modal("show");
            }


            window.onload = function() {
                actualizar_grilla();
            };
        </script>

    </div> <!-- .content -->
</div><!-- /#right-panel -->

<!-- Right Panel -->

<?php

$base_url_for_resources = base_url() . "/assets/template/";
?>

<script src="<?= $base_url_for_resources ?>vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= $base_url_for_resources ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>



<?= $this->endSection() ?>