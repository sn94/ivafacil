<?php

use App\Helpers\Utilidades;
use App\Models\Estado_mes_model;

$cedula = isset($usuario) ? Utilidades::number_f($usuario->cedula)  :  "";
$ruc = isset($usuario) ?  $usuario->ruc :  "";
$dv = isset($usuario) ?  $usuario->dv :  "";
$cliente =  isset($usuario) ?  $usuario->cliente :  "";
$email =  isset($usuario) ?  $usuario->email :  "";
$domicilio =  isset($usuario) ?  $usuario->domicilio :  "";
$telefono =  isset($usuario) ?  $usuario->telefono :  "";
$celular =  isset($usuario) ?  $usuario->celular :  "";
$saldo_IVA = isset($usuario) ?  Utilidades::number_f($usuario->saldo_IVA) :  "0";

$ciudad =  isset($usuario) ?  $usuario->ciudad :  "";
$tipoplan =  isset($usuario) ?  $usuario->tipoplan :  "";
$rubro =  isset($usuario) ?  $usuario->rubro :  "";
$ultimo_nro = isset($usuario) ?     $usuario->ultimo_nro : "";
?>

<input type="hidden" name="tipo" value="C"><!-- C= cliente  -->

<?php if (isset($OPERACION)  &&  $OPERACION == "M") : ?>
    <input type="hidden" name="regnro" value="<?= $usuario->regnro ?>">
<?php endif; ?>

<div class="row form-group">

    <div class="col-12 col-md-6">
        <div class="row form-group">
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Cédula:</label>
            </div>
            <div class="col-9    col-md-9">
                <input value="<?= $cedula ?>" maxlength="10" oninput="formatear(event);if(this.value=='0') this.value= ''; control_campo_vacio(event);" type="text" name="cedula" class=" form-control form-control-label form-control-sm ">
                <p id="cedula" style="font-size: 11px;color: greenyellow;"></p>
            </div>
            <div class="col-12">
                <div class="row form-group">
                    <div class="col-3 col-md-3 pl-0 pl-md-3">
                        <label class=" form-control-label form-control-sm -label">RUC:</label>
                    </div>
                    <div class="col-5 col-md-6 pr-0">
                        <input <?= (isset($OPERACION)  && $OPERACION == "M") ? "disabled" : "" ?> oninput="solo_numero(event);control_campo_vacio(event);" value="<?= $ruc ?>" maxlength="15" type="text" id="nf-email" name="ruc" class="  form-control form-control-label form-control-sm ">
                        <p id="ruc" style="font-size: 11px;color: greenyellow;"></p>
                    </div>
                    <div class="col-1 col-md-1 pl-0 ">
                        <label class=" form-control-label form-control-sm -label">DV:</label>
                    </div>
                    <div class="col-3 col-md-2">
                        <input <?= (isset($OPERACION)  && $OPERACION == "M") ? "disabled" : "" ?> value="<?= $dv ?>" maxlength="2" oninput="solo_numero(event);control_campo_vacio(event);" type="text" name="dv" class="  form-control form-control-label form-control-sm ">
                        <p id="dv" style="font-size: 11px;color: greenyellow;"></p>
                    </div>
                </div>
            </div>

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Nombres:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $cliente ?>" type="text" maxlength="80" name="cliente" class=" form-control form-control-label form-control-sm ">
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Email:</label>
            </div>
            <div class="col-9 col-md-9">
                <input oninput="control_campo_vacio(event)" value="<?= $email ?>" maxlength="120" type="text" name="email" class=" form-control form-control-label form-control-sm ">
                <p id="email" style="font-size: 11px;color: greenyellow;"></p>
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Domicilio:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $domicilio ?>" maxlength="100" type="text" name="domicilio" class=" form-control form-control-label form-control-sm ">
            </div>

        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="row form-group">

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Teléfono:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $telefono ?>" oninput="phone_input(event)" maxlength="20" type="text" name="telefono" class=" form-control form-control-label form-control-sm ">
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Celular:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $celular ?>" oninput="phone_input(event)" maxlength="20" type="text" name="celular" class=" form-control form-control-label form-control-sm ">
            </div>

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Ciudad:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $ciudad ?>" name="ciudad" class=" form-control form-control-label form-control-sm "></select>
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Elegir plan:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $tipoplan ?>" name="tipoplan" class=" form-control form-control-label form-control-sm "></select>
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Rubro:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $rubro ?>" name="rubro" class=" form-control form-control-label form-control-sm "></select>
            </div>

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
            </div>
            <div class="col-9 col-md-9">
                <input id="masterpass" <?= !isset($OPERACION) ? "" : "disabled" ?> value="" maxlength="80" type="password" name="pass" class=" form-control form-control-label form-control-sm ">
            </div>

            <?php if (isset($OPERACION)  && $OPERACION == "M") : ?>
                <div class="col-12 text-right">
                    <span style="font-size: 11px;"> Editar password <input onclick="editar_pass(event); " type="checkbox" name="" id="switch-pass"></span>
                </div>
            <?php endif; ?>

            <?php if (!isset($OPERACION)) : ?>
                <div class="col-3 col-md-3 pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">Repetir contraseña:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input oninput="clave_no_coincide(event)" id="pass2" value="" maxlength="80" type="password" class=" form-control form-control-label form-control-sm ">
                </div>
            <?php endif; ?>


        </div>
    </div>


    <div class="col-12 col-md-6">
        <div class="row form-group">
            <div class="col-4 col-md-5 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label" style="font-weight: 600;">
                    Saldo Inicial <?= date("Y") ?> : <span></span></label>
            </div>
            <div class="col-8 col-md-7">

                <!--Campo de saldo inicial editable ?  -->
                <?php

                $reg_anio =
                    (new Estado_mes_model())->where("codcliente", session("id"))->where("anio", date("Y"))->first();

                $yaestaCerrado =  !is_null($reg_anio) ? (($reg_anio->estado == "P") ? "" : "disabled")  :   "";
                ?>
                <input <?= $yaestaCerrado ?> onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" value="<?= $saldo_IVA ?>" maxlength="10" oninput="formatear( event)" type="text" name="saldo_IVA" class=" form-control form-control-label form-control-sm ">


            </div>
        </div>
    </div>


    <div class="col-12 col-md-6">
        <div class="row form-group">
            <div class="col-12 col-md-6 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label" style="font-weight: 600;">
                    Última factura de venta: <span></span></label>
            </div>
            <div class="col-12 col-md-6">
                <input oninput="solo_num_guiones(event)" value="<?= $ultimo_nro ?>"   maxlength="15" type="text" name="ultimo_nro" class=" form-control form-control-label form-control-sm ">
            </div>
        </div>
    </div>




    <!--Eleccion de fondo de pantalla -->
    <?php if (isset($usuario)) : ?>
        <div class="col-12">
            <label style="font-weight: 600;" for="nf-password" class=" form-control-label form-control-sm -label">Fondo de pantalla:</label>

            <div class="card-group">


                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="" alt="NINGUNO">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="none">
                    </div>
                </div>
                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo0.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo0.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo1.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo1.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo2.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo2.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo3.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo3.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo4.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo4.jpg") ?>">
                    </div>
                </div>


            </div>
        </div>
    <?php endif; ?>

</div>
<!--end row form  -->
<script>
    function clean_number(arg) {
        try {
             
            arg.val(arg.val().replaceAll(/\.|,|\-/g, ""));
        } catch (er) {}
    }





    function solo_num_guiones(ev) {
        //0 48   9 57
        if (ev.data == null) return;
        if (ev.data.charCodeAt() != 45 && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
            let cad = ev.target.value;
            let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
            ev.target.value = cad_n;
        }
    }


    function wallpaper() {
        let selected = Array.prototype.filter.call(document.querySelectorAll("input[name=fondo]"),
            function(ar) {
                return $(ar).prop("checked");
            });

        if (selected.length > 0) return selected[0].value;
        else return "";

    }



    function editar_pass(ev) {
        if (ev.target.checked) document.getElementById('masterpass').disabled = false;
        else document.getElementById('masterpass').disabled = true;
    }
</script>