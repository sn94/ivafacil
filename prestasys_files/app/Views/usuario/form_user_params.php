<?php

use App\Helpers\Utilidades;

$saldo_IVA = isset($usuario) ?  Utilidades::number_f($usuario->saldo_IVA) :  "0";
$timbrado =  isset($usuario) ?  $usuario->timbrado  : "";
$ultimo_nro = isset($usuario) ?     $usuario->ultimo_nro : "";
$clave_marangatu =  isset($usuario) ?  $usuario->clave_marangatu  : "";


//Saldo inicial field
use App\Models\Estado_mes_model;

$reg_anio = (new Estado_mes_model())->where("codcliente", session("id"))->where("anio", date("Y"))->first();
$yaestaCerrado =  !is_null($reg_anio) ? (($reg_anio->estado == "P") ? "" : "disabled")  :   "";
?>



<style>
    legend {
        background-color: #d1d1d1;
        border-radius: 0px 0px 20px 20px;
    }
</style>
<fieldset>
    <legend>
        <h5 class="text-center">Configuraciones</h5>
    </legend>

    <div class="row">
        <div class="col-12 col-md-6">


            <label for="nf-password" class=" form-control-label form-control-sm -label">
                Saldo Inicial <?= date("Y") ?> : <span></span></label>
            <input <?= $yaestaCerrado ?> onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" value="<?= $saldo_IVA ?>" maxlength="10" oninput="formatear( event)" type="text" name="saldo_IVA" class=" form-control form-control-label form-control-sm ">

            <label for="" class=" form-control-label form-control-sm -label">N° Timbrado:</label>
            <input maxlength="8" type="text" name="timbrado" class="form-control form-control-sm" value="<?= $timbrado ?>">
        </div>

        <div class="col-12 col-md-6">

            <label for="nf-password" class=" form-control-label form-control-sm -label">
                Última factura de venta: <span></span></label>
            <input oninput="solo_num_guiones(event)" value="<?= $ultimo_nro ?>" maxlength="15" type="text" name="ultimo_nro" class=" form-control form-control-label form-control-sm ">
            <label for="nf-password" class=" form-control-label form-control-sm -label">
                Clave de acceso Marangatu: <span></span></label>
            <input maxlength="80" value="<?= $clave_marangatu ?>" type="text" name="clave_marangatu" class=" form-control form-control-label form-control-sm ">
        </div>
    </div>


</fieldset>