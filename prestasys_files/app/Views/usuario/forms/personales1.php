<?php

use App\Helpers\Utilidades; 

$cedula = isset($usuario) ? Utilidades::number_f($usuario->cedula)  :  "";
$ruc = isset($usuario) ?  $usuario->ruc :  "";
$dv = isset($usuario) ?  $usuario->dv :  "";
$cliente =  isset($usuario) ?  $usuario->cliente :  "";
$email =  isset($usuario) ?  $usuario->email :  "";
$domicilio =  isset($usuario) ?  $usuario->domicilio :  "";


?>

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
                        <input <?= (isset($OPERACION)  && $OPERACION == "M") ? "disabled" : "" ?> oninput="obtener_dv(event);control_campo_vacio(event);" value="<?= $ruc ?>" maxlength="15" type="text" id="nf-email" name="ruc" class="  form-control form-control-label form-control-sm ">
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