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


        <div id="loaderplace">
        </div>

        <!-- Menu de Usuario -->
        <div class="row">

            <div class="col-12">
                <?= view("plantillas/message") ?>

            </div>
            <div class="col-12 offset-md-3 col-md-6 ">


                <?php

                use App\Helpers\Utilidades;

                echo  form_open("admin/create",  ['id' => 'user-form', 'class' => 'container', 'onsubmit' => 'registro(event)']); ?>


                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Usuario Administrador</h4>
                    </div>
                    <div class="card-body card-block p-2">
                        <div class="row form-group">

                            <div class="col-12">
                                <div class="row form-group">



                                    <div class="col-3 col-md-3 pl-md-3 pl-0">
                                        <label for="nf-password" class=" form-control-label form-control-sm -label">Nick:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input oninput="control_campo_vacio( event)" value="<?= set_value('nick') ?>" type="text" maxlength="20" name="nick" class=" form-control form-control-label form-control-sm ">
                                        <p id="nick" style="font-size: 11px; color:red; font-weight: 600;"></p>
                                    </div>
                                    <div class="col-3 col-md-3 pl-md-3 pl-0">
                                        <label for="nf-password" class=" form-control-label form-control-sm -label">Email:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input oninput="control_campo_vacio(event)" value="<?= set_value('email') ?>" maxlength="80" type="text" name="email" class=" form-control form-control-label form-control-sm ">
                                        <p id="email" style="font-size: 11px; color:red; font-weight: 600;"></p>
                                    </div>


                                    <div class="col-3 col-md-3 pl-md-3 pl-0">
                                        <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input oninput="control_campo_vacio( event)" value="" maxlength="80" type="password" name="pass" class=" form-control form-control-label form-control-sm ">
                                        <p id="pass" style="font-size: 11px; color:red; font-weight: 600;"></p>
                                    </div>
                                    <div class="col-3 col-md-3 pl-md-3 pl-0">
                                        <label for="nf-password" class=" form-control-label form-control-sm -label">Repetir contraseña:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input oninput="clave_no_coincide(event)" id="pass2" value="" maxlength="80" type="password" class=" form-control form-control-label form-control-sm ">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end row form  -->


                    </div>
                    <div class="card-footer">
                        <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-dot-circle-o"></i> REGISTRARME
                        </button>
                    </div>

                </div>
                </form>


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
                    $("#" + ev.target.name).text("Campo obligatorio");

                } else {
                    $(ev.target).removeClass("empty-field");
                    $("#" + ev.target.name).text("");
                }
            }










            //Procesamiento de formulario


            function campos_vacios() {
                if ($("input[name=nick]").val() == "") {
                    $("input[name=nick]").addClass("empty-field");
                    $("#nick").text("Campo obligatorio");
                }
                if ($("input[name=email]").val() == "") {
                    $("input[name=email]").addClass("empty-field");
                    $("#email").text("Campo obligatorio");
                }
                if ($("input[name=pass]").val() == "") {
                    $("input[name=pass]").addClass("empty-field");
                    $("#pass").text("Campo obligatorio");
                }
                return ($("input[name=nick]").val() == "") || ($("input[name=email]").val() == "") || ($("input[name=pass]").val() == "");
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


                let datos = $("#user-form").serialize();
                show_loader();
                let req = await fetch($("#user-form").attr("action"), {
                    method: "POST",
                    headers: {
                        // 'Content-Type': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: datos
                });
                let respuesta = await req.json();
                hide_loader();
                if (("data" in respuesta) && parseInt(respuesta.code) == 200) {

                    $("#message-modal-content").html("REGISTRADO");

                    $("#message-modal").modal("show");
                } else {
                    $("#message-modal-content").html(procesar_errores(respuesta.msj));
                    $("#message-modal").modal("show");
                }
            }
        </script>

    </div> <!-- .content -->
</div><!-- /#right-panel -->









<?= $this->endSection() ?>