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
            <div class="col-12 offset-md-1 col-md-10 p-0">

                <div class="card">


                    <div class="card-header"  style="border-radius: 15px 15px 0px 0px; background-color: #d1d1d1;">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="text-center">NUEVO CLIENTE</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php

                        use App\Helpers\Utilidades;


                        echo  form_open(
                            "admin/clientes/create",
                            [
                                'id' => 'user-form',
                                'class' => 'container p-0 p-md-2',
                                'onsubmit' => 'registro(event)'
                            ]
                        ); ?>

                        <?= view("usuario/update/index") ?>
                        <div class="row">

                            <div class="col-12 offset-md-4  col-md-4  mb-2 ">
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



            /***
            
            **Fuentes de datos
            **/
            async function get_ciudades() {
                let req = await fetch("<?= base_url("auxiliar/ciudades") ?>");
                let json_r = await req.json();


                let departs = json_r.map(
                    function(obje) {
                        return obje.departa;
                    }
                ).filter(function(obj, indice, arr) {

                    return arr.indexOf(obj) == indice;
                });


                let ordenado = departs.map(function(key) {
                    let cities = json_r.filter(function(obj_ciu) {
                        return obj_ciu.departa == key;
                    }).map(function(nuevo) {
                        return {
                            regnro: nuevo.regnro,
                            ciudad: nuevo.ciudad
                        };
                    });
                    return {
                        [key]: cities
                    };
                });

                ordenado.forEach(function(regi) {

                    let depart = Object.keys(regi)[0];
                    let ciudades = regi[depart];
                    let str_ciudades = ciudades.map(function(citi) {
                        return "<option value='" + citi.regnro + "'>" + citi.ciudad + "</option>";
                    }).join();

                    let optgr = "<optgroup label='" + depart + "'>" + str_ciudades + "</optgroup>";
                    //clasificar
                    $("select[name=ciudad]").append(optgr);
                });

                /* */
            }





            async function get_actividades_comer() {

                let req = await fetch("<?= base_url("auxiliar/rubros") ?>");
                let json_r = await req.json();

                json_r.forEach(function(obj) {
                    $("select[name=rubro]").append("<option value='" + obj.regnro + "'>" + obj.descr + "</option>");
                });

            }

            async function get_planes() {

                let req = await fetch("<?= base_url("auxiliar/planes") ?>");
                let json_r = await req.json();

                json_r.forEach(function(obj) {
                    $("select[name=tipoplan]").append("<option value='" + obj.regnro + "'>" + obj.descr + "</option>");
                });

            }






            //Procesamiento de formulario


            function campos_vacios() {

                if ($("input[name=email]").val() == "" || $("input[name=ruc]").val() == "" || $("input[name=dv]").val() == "") {
                    if ($("input[name=email]").val() == "") {
                        $("input[name=email]").addClass("empty-field");

                    }
                    if ($("input[name=ruc]").val() == "") {
                        $("input[name=ruc]").addClass("empty-field");

                    }
                    if ($("input[name=dv]").val() == "") {
                        $("input[name=dv]").addClass("empty-field");

                    }
                    return true;
                }

                return false;
            }

            function claves_validas() {
                if ($("input[name=pass]").val() == "") {
                    alert("Proporcione una contraseña");
                    return false;
                }
                if ($("#pass2").val() == "") {
                    $("#pass2").addClass("empty-field");
                    alert("Por favor repita su contraseña");
                    return false;
                }
                if ($("input[name=pass]").val() != $("#pass2").val()) {
                    alert("Ambas contraseñas no coinciden");
                    return false;
                }
                return true;
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


            async function registro(ev) {


                ev.preventDefault();
                if (campos_vacios() || !claves_validas()) return;

                //limpiar numeros
                clean_number($("input[name=saldo_IVA]"));
                clean_number($("input[name=cedula]"));

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

                   // $("#message-modal-content").html("REGISTRADO");
                    //$("#message-modal").modal("show");
                    alert("REGISTRADO");
                    window.location.reload();
                } else {
                    $("#message-modal-content").html(procesar_errores(respuesta.msj));
                    $("#message-modal").modal("show");
                }
            }






            //init
            window.onload = function() {
                $('#message-modal').on('hidden.bs.modal', function(e) {
                    actualizar_grilla();
                });

                get_planes();
                get_actividades_comer();
                get_ciudades();

            }
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