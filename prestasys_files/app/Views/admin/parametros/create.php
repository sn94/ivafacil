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

<div class="container p-0">


    <div class="content mt-3 p-0">


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
        <div class="row p-0">

            <div class="col-12">
                <?= view("plantillas/message") ?>

            </div>
            <div class="col-12 offset-md-3 col-md-6 p-0 ">

                <div class="container-fluid m-0 p-0">
                    <?php echo  form_open("admin/parametros/create",  ['id' => 'param-form', 'class' => 'container m-0 p-0', 'onsubmit' => 'registro(event)']); ?>

                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Parámetros</h4>
                            </div>
                            <div class="card-body card-block p-3">
                                <div class="row form-group">

                                    <div class="col-12">
                                        <div class="row form-group">

                                            <input type="hidden" name="regnro" value="<?= $parametros->regnro ?>">

                                            <div class="col-4 col-md-4 pl-md-3 pl-0">
                                                <label for="nf-password" class=" form-control-label form-control-sm -label">Email principal:</label>
                                            </div>
                                            <div class="col-8 col-md-8">
                                                <input value="<?= $parametros->EMAIL ?>" type="text" maxlength="20" name="EMAIL" class=" form-control form-control-label form-control-sm ">

                                            </div>
                                            <div class="col-4 col-md-4 pl-md-3 pl-0">
                                                <label for="nf-password" class=" form-control-label form-control-sm -label">Redondeo:</label>
                                            </div>
                                            <div class="col-8 col-md-8">
                                                <input size="3" oninput="solo_numero(event)"  value="<?= $parametros->REDONDEO ?>" maxlength="1" type="text" name="REDONDEO" class=" form-control-inline  form-control-sm ">
                                                <span style="font-size: 11px;">decimales</span>
                                            </div>


                                            <div class="col-4 col-md-4 pl-md-3 pl-0">
                                                <label for="nf-password" class=" form-control-label form-control-sm -label">Prueba gratuita:</label>
                                            </div>
                                            <div class="col-8 col-md-8">
                                                <input size="3" oninput="solo_numero( event)" value="<?= $parametros->DIASGRATIS ?>" maxlength="3" type="text" name="DIASGRATIS" class=" form-control-inline   form-control-sm ">
                                                <span style="font-size: 11px;">días</span>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!--end row form  -->


                            </div>
                            <div class="card-footer">
                                <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> GUARDAR
                                </button>
                            </div>

                        </div>
                    </div>
                    </form>
                </div>


            </div>

        </div>


        <script>
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














            //Procesamiento de formulario






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

                let datos = $("#param-form").serialize();
                show_loader();
                let req = await fetch($("#param-form").attr("action"), {
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