<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IVA FÁCIL</title>

    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta name="description" content="iva">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/bootstrap/dist/css/bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/template/vendors/font-awesome/css/font-awesome.min.css") ?>">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>


</head>

<body>


    <!-- Right Panel -->

    <div class="container">


        <div class="content mt-3">



            <?= view("plantillas/message") ?>

            <!-- Menu de Usuario -->
            <div class="row">

                <div class="col-12 offset-md-3 col-md-6 ">


                    <?php

                    use App\Helpers\Utilidades;

                    echo  form_open("usuario/create/N",  ['class' => 'container', 'onsubmit' => 'registro(event)']); ?>


                    <input type="hidden" name="tipo" value="C"><!-- C= cliente  -->

                    <div class="card">
                        <div class="card-header">
                            <strong>Registrarse</strong>
                        </div>
                        <div class="card-body card-block p-0">
                            <div class="row form-group">
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Cédula:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= Utilidades::number_f(set_value('cedula')) == 0 ? "" :  Utilidades::number_f(set_value('cedula')) ?>" maxlength="10" oninput="formatear(event)" type="text" name="cedula" class=" form-control form-control-label form-control-sm ">
                                </div>

                                <div class="row pl-md-3 pl-0 pr-3">
                                    <div class="col-8 ">
                                        <div class="row">
                                            <div class="col-3 col-md-3 ">
                                                <label for="nf-email" class=" form-control-label form-control-sm -label">RUC:</label>
                                            </div>
                                            <div class="col-9 col-md-9">
                                                <input value="<?= set_value('ruc') ?>" maxlength="15" type="text" id="nf-email" name="ruc" class="  form-control form-control-label form-control-sm ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 ml-0">
                                        <div class="row">
                                            <div class="col-3 col-md-3 ">
                                                <label for="nf-email" class=" form-control-label form-control-sm -label">DV:</label>
                                            </div>
                                            <div class="col-9 col-md-9">
                                                <input value="<?= set_value('dv') ?>" maxlength="2" oninput="solo_numero(event)" type="text" name="dv" class="  form-control form-control-label form-control-sm ">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Nombres:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= set_value('cliente') ?>" type="text" maxlength="80" name="cliente" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Email:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= set_value('email') ?>" maxlength="80" type="text" name="email" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Domicilio:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= set_value('domicilio') ?>" maxlength="100" type="text" name="domicilio" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Teléfono:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= set_value('telefono') ?>" oninput="phone_input(event)" maxlength="20" type="text" name="telefono" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Celular:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= set_value('celular') ?>" oninput="phone_input(event)" maxlength="20" type="text" name="celular" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Ciudad:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <select name="ciudad" class=" form-control form-control-label form-control-sm "></select>
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Elegir plan:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <select name="tipoplan" class=" form-control form-control-label form-control-sm "></select>
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Rubro:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <select name="rubro" class=" form-control form-control-label form-control-sm "></select>
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="" maxlength="80" type="password" name="pass" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Repetir contraseña:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="" maxlength="80" type="password" class=" form-control form-control-label form-control-sm ">
                                </div>
                                <div class="col-3 col-md-3 pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo anterior: <span>(a favor del contribuyente)</span></label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="<?= Utilidades::number_f(set_value('saldo_IVA')) ?>" maxlength="11" oninput="formatear( event)" type="text" name="saldo_IVA" class=" form-control form-control-label form-control-sm ">
                                </div>

                                <div class="col-12">
                                    <h6 class="mt-1 text-center" style="color: red; font-weight: 600;">VERIFICAR LOS DATOS REGISTRADOS</h6>
                                    <p class="mt-1 text-center" style="color: red; font-weight: 600;"> El primer mes es GRATIS</p>
                                    <p style="color: #026804; font-weight: 600;">El costo del servicio es de Gs. 60.000 mensuales, pagos por mes adelantado, se abona en cualquier ventanilla de pagos de servicios </p>
                                </div>

                            </div>


                        </div>
                        <div class="card-footer">
                            <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-dot-circle-o"></i> ATRÁS
                            </a>

                            <button style="font-size: 10px;font-weight: 600;" type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-dot-circle-o"></i> REGISTRAR E IR AL MENÚ
                            </button>
                        </div>

                    </div>
                    </form>


                </div>

            </div>


            <script>
                //Validaciones js

                function phone_input(ev) {
                    if (ev.data == undefined || ev.data == null) return;

                    if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
                        ev.target.value =
                            ev.target.value.substr(0, ev.target.selectionStart - 1) + " " +
                            ev.target.value.substr(ev.target.selectionStart);
                    }
                }


                function formatear(ev) {
                    if (ev.data == undefined) return;
                    if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
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







                //Fuentes de datos
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

                function registro(ev) {

                    ev.preventDefault();
                    //limpiar numeros
                    clean_number($("input[name=saldo_IVA]"));
                    clean_number($("input[name=cedula]"));
                    ev.target.submit();
                }








                //init
                window.onload = function() {
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


</body>

</html>