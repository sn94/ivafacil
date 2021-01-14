<style>
label{
    font-family: mainfont; 
} 
</style>
<?php

use App\Helpers\Utilidades;

$CLIENTE =  $ESTADO_MES->codcliente;
$RUC =  $ESTADO_MES->ruc;
$DV =  $ESTADO_MES->dv;
$MES =  $ESTADO_MES->mes;
$ANIO = $ESTADO_MES->anio;
$SALDO = Utilidades::number_f($ESTADO_MES->saldo);


echo  form_open(
    "admin/clientes/pagos-iva/procesar",
    [
        'id' => 'user-form',
        'class' => 'container p-0 p-md-2',
        'onsubmit' => 'registro(event)'
    ]
); ?>

<input type="hidden" name="codcliente" value="<?= $CLIENTE ?>">
<input type="hidden" name="dv" value="<?= $DV ?>">
<input type="hidden" name="ruc" value="<?= $RUC ?>">
<input type="hidden" name="mes" value="<?= $MES ?>">
<input type="hidden" name="anio" value="<?= $ANIO ?>">

<div class="row">

    <div class="col-12" id="loaderplace"></div>
    <div class="col-12" >
        <h5 style="font-weight: 700;" >RUC: <?= "$RUC-$DV Pago del mes de ". Utilidades::monthDescr($MES)." ($ANIO) " ?></h5>
    </div>
 

    <div class="col-12 col-md-4">
        <div class="row form-group">
            <div class="col-12">
                <label  >Comprobante:</label>
            </div>
            <div class="col-12">
                <input maxlength="20" type="text" name="comprobante" class="  form-control form-control-inline form-control-sm ">

            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 pl-0">

        <div class="row form-group">
            <div class="col-12 pr-0 ">
                <label >Fecha:</label>
            </div>
            <div class="col-12 ">
                <input size="10" value="<?= date("Y-m-d") ?>" type="date" name="fecha" class="  form-control form-control-inline form-control-sm ">

            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 pl-0">

        <div class="row form-group">
            <div class="col-12 pr-0 ">
                <label >Importe:</label>
            </div>
            <div class="col-12 ">
                <input value="<?= $SALDO ?>" oninput="formatear(event)" maxlength="13" type="text" name="importe" class="text-right  form-control form-control-inline form-control-sm ">

            </div>
        </div>
    </div>


</div>




<div class="row form-group">
    <div class="col-12  col-md-2 mb-2 ">
        <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-dot-circle-o"></i> GUARDAR
        </button>
    </div>
</div>


</form>


<script>
    async function registro(ev) {

        ev.preventDefault();
        if (campos_vacios()) return;


        clean_number($("input[name=importe]"));
        $("#loaderplace").html(showLoader());
        let datos = $("#user-form").serialize();
        let req = await fetch($("#user-form").attr("action"), {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: datos
        });
        let respuesta = await req.json();

        if (("data" in respuesta) && parseInt(respuesta.code) == 200) {

            $("#loaderplace").html("");
            $("#form-pagos").html("<h5 style='color:green;'>Pago registrado</h5>");
            //actualizar grilla de pagos
            actualizar_grilla();
        } else {
            $("#loaderplace").html("");
            $("#form-pagos").html(procesar_errores(respuesta.msj));

        }
    }
</script>