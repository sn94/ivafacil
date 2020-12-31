<?php

use App\Models\Usuario_model;

$NOMBRE_CLIENTE = "";
$DATA_CLI = (new Usuario_model())->find($CLIENTE);
if (!is_null($DATA_CLI))
    $NOMBRE_CLIENTE =   $DATA_CLI->cliente . " RUC: " . $DATA_CLI->ruc . "-" . $DATA_CLI->dv;
?>

<?= $this->extend("admin/layout/index") ?>
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

        <div id="testest">

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
                                <h4 class="text-center"> PAGOS: <?= $NOMBRE_CLIENTE ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php

                        use App\Helpers\Utilidades;

                        $CLIENTE = isset($CLIENTE) ? $CLIENTE : "";
                        echo  form_open(
                            "admin/clientes/pagos",
                            [
                                'id' => 'user-form',
                                'class' => 'container p-0 p-md-2',
                                'onsubmit' => 'registro(event)'
                            ]
                        ); ?>

                        <input type="hidden" name="cliente" value="<?= $CLIENTE ?>">
                        <input type="hidden" name="estado" value="A">

                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="row form-group">
                                    <div class="col-12 col-md-4">
                                        <label class=" form-control-label form-control-sm -label">Comprobante:</label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input maxlength="20" type="text" name="comprobante" class="  form-control form-control-inline form-control-sm ">

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 pl-0">

                                <div class="row form-group">
                                    <div class="col-12 col-md-3 pr-0 ">
                                        <label class=" form-control-label form-control-sm -label">Fecha:</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input size="10" value="<?= date("Y-m-d") ?>" type="date" name="fecha" class="  form-control form-control-inline form-control-sm ">

                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 pl-0">
                                <div class="row form-group">
                                    <div class="col-12 col-md-3 ">
                                        <label class=" form-control-label form-control-sm -label">Concepto:</label>
                                    </div>
                                    <div class="col-12 col-md-9 ">
                                        <input " type=" text" name="concepto" class="  form-control form-control-inline form-control-sm ">

                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="row form-group">
                            <div class="col-12   col-md-2  mb-2 ">
                                <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> GUARDAR
                                </button>
                            </div>
                        </div>


                        </form>
                    </div>

                </div>
                <!--end card-->

            </div>

        </div>

        <?php

        $pagos = isset($pagos)  ? $pagos :  [];
        ?>
        <!-- TABLA PAGOS -->
        <div class="row" id="tabla-pagos">
            <?= view("admin/clientes/grill_pagos") ?>
        </div>
        <!-- end PAGOS -->

        <script>
            /***
                Validaciones js

                **/

            function phone_input(ev) {
                if (ev.data == undefined || ev.data == null) return;

                if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
                    ev.target.value =
                        ev.target.value.substr(0, ev.target.selectionStart - 1) + " " +
                        ev.target.value.substr(ev.target.selectionStart);
                }
            }


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



            function clave_no_coincide(ev) {
                let rep = ev.target.value;
                if (rep == $("input[name=pass]").val()) {
                    $(ev.target).removeClass("empty-field");
                    $(ev.target).removeClass("password-wrong");
                    $(ev.target).addClass("password-ok");
                    $("input[name=pass]").addClass("password-ok");
                } else {
                    $("input[name=pass]").removeClass("password-ok");
                    $(ev.target).removeClass("password-ok");
                    $(ev.target).addClass("password-wrong");
                }
            }





            function control_campo_vacio(ev) {
                if (ev.target.value == "") {
                    $(ev.target).addClass("empty-field");
                    if (ev.target.name != "dv")
                        $("#" + ev.target.name).text("Campo obligatorio");

                } else {
                    $(ev.target).removeClass("empty-field");
                    $("#" + ev.target.name).text("");
                }
            }










            //Procesamiento de formulario


            function campos_vacios() {

                if ($("input[name=comprobante]").val() == "" || $("input[name=fecha]").val() == "") {
                    alert("Indique al menos la fecha de pago")
                    return true;
                }

                return false;
            }




            function show_loader() {
                let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
                $("#loaderplace").html(loader);
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


function limpiar_campos(){
    $("input[name=comprobante]").val("");
    $("input[name=concepto]").val("");
}
            async function registro(ev) {

                ev.preventDefault();
                if (campos_vacios()) return;

                let datos = $("#user-form").serialize();
                show_loader();
                let req = await fetch($("#user-form").attr("action"), {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: datos
                });
                let respuesta = await req.json();
                hide_loader();
                if (("data" in respuesta) && parseInt(respuesta.code) == 200) {

                 
                    alert("REGISTRADO");
                    limpiar_campos();
                    //actualizar grilla de pagos
                    actualizar_grilla();
                } else {
                    $("#message-modal-content").html(procesar_errores(respuesta.msj));
                    $("#message-modal").modal("show");
                }
            }



            //- GRILLA DE PAGOS 


            function showLoader() {
                let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
                return loader;
            }

            //Actualizar tabla
            async function actualizar_grilla() {
                $("#tabla-pagos").html(showLoader());
                let form = await fetch("<?= base_url("admin/clientes/list-pagos/" . $CLIENTE) ?>", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                let form_R = await form.text();
                $("#tabla-pagos").html(form_R);
                $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
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


<?= $this->endSection() ?>