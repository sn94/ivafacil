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
    </style>


</head>

<body>


    <!-- Right Panel -->

    <div class="container">


        <div class="content mt-3">



            <?= view("plantillas/message") ?>

            <!-- Menu de Usuario -->
            <div class="row">

                <div class="col-12 offset-md-1 col-md-10 ">


                    <?php

                    use App\Helpers\Utilidades;

                    echo  form_open("usuario/create/N",  ['class' => 'container', 'onsubmit' => 'registro(event)']); ?>


                    <input type="hidden" name="tipo" value="C"><!-- C= cliente  -->

                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-center">Registrarse</h4>
                        </div>
                        <div class="card-body card-block p-2">
                            <div class="row form-group">

                                <div class="col-12 col-md-6">
                                    <div class="row form-group">
                                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                                            <label for="nf-password" class=" form-control-label form-control-sm -label">Cédula:</label>
                                        </div>
                                        <div class="col-9    col-md-9">
                                            <input value="<?= Utilidades::number_f(set_value('cedula')) == 0 ? "" :  Utilidades::number_f(set_value('cedula')) ?>" maxlength="10" oninput="formatear(event);if(this.value=='0') this.value= ''; control_campo_vacio(event);" type="text" name="cedula" class=" form-control form-control-label form-control-sm ">
                                            <p id="cedula" style="font-size: 11px;color: red;"></p>
                                        </div>
                                        <div class="col-12">
                                            <div class="row form-group">
                                                <div class="col-3 col-md-3 pl-0 pl-md-3">
                                                    <label class=" form-control-label form-control-sm -label">RUC:</label>
                                                </div>
                                                <div class="col-5 col-md-6 pr-0">
                                                    <input oninput="control_campo_vacio(event)" value="<?= set_value('ruc') ?>" maxlength="15" type="text" id="nf-email" name="ruc" class="  form-control form-control-label form-control-sm ">
                                                    <p id="ruc" style="font-size: 11px;color: red;"></p>
                                                </div>
                                                <div class="col-1 col-md-1 pl-0 ">
                                                    <label class=" form-control-label form-control-sm -label">DV:</label>
                                                </div>
                                                <div class="col-3 col-md-2">
                                                    <input value="<?= set_value('dv') ?>" maxlength="2" oninput="solo_numero(event);control_campo_vacio(event);" type="text" name="dv" class="  form-control form-control-label form-control-sm ">
                                                    <p id="dv" style="font-size: 11px;color: red;"></p>
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
                                            <input oninput="control_campo_vacio(event)" value="<?= set_value('email') ?>" maxlength="80" type="text" name="email" class=" form-control form-control-label form-control-sm ">
                                            <p id="email" style="font-size: 11px;color: red;"></p>
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
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="row form-group">

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
                                            <input oninput="clave_no_coincide(event)" id="pass2" value="" maxlength="80" type="password" class=" form-control form-control-label form-control-sm ">
                                        </div>
                                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo anterior: <span>(a favor del contribuyente)</span></label>
                                        </div>
                                        <div class="col-9 col-md-9">
                                            <input value="<?= Utilidades::number_f(set_value('saldo_IVA')) ?>" maxlength="10" oninput="formatear( event)" type="text" name="saldo_IVA" class=" form-control form-control-label form-control-sm ">
                                        </div>
                                    </div>
                                </div>





                                <div class="col-12  col-md-6 mb-2">
                                    <input type="checkbox" name="aceptar-bases" value="S"> He leído y acepto las <a href="#">bases y condiciones</a>
                                </div>
                                <div class="col-12  col-md-6 ">
                                    <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> REGISTRARME
                                    </button>
                                </div>

                                <div class="col-12" style="border-radius: 20px; background-color: #e8c14c;">

                                    <p class="mt-1 text-center pb-0 mb-0" style="color: red; font-weight: 700;"> PRIMER MES GRATIS</p>
                                    <p class="mt-0 pt-0" style="color: #1d2101; font-weight: 600;">El costo del servicio es de Gs. 30.000 Gs. en el 2do y 3er mes, a
                                        partir del 4to mes sube 60.000 Gs. y de ahi no sube más! </p>
                                </div>


                            </div>
                            <!--end row form  -->


                        </div>
                        <div class="card-footer">



                            ¿Ya tienes cuenta?
                            <a style="font-size: 12px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-secondary btn-sm">
                                Ingresa aquí
                            </a>
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
                        if( ev.target.name != "dv")
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
                    if (!$("input[name=aceptar-bases]").prop("checked")) {
                        alert("Aceptar primero las bases y condiciones para continuar");
                        return true;
                    }
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


                function registro(ev) {


                    ev.preventDefault();
                    if (campos_vacios() || !claves_validas()) return;

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