<?php

use App\Helpers\Utilidades;


$regnro = isset($retencion) ?  $retencion->regnro : "";
$ruc = isset($retencion) ?  $retencion->ruc :  session("ruc");
$dv = isset($retencion) ?  $retencion->dv :  session("dv");
$codcliente = isset($retencion) ?  $retencion->codcliente :  session("id");
$fecha = isset($retencion) ?  $retencion->fecha : date("Y-m-d");
$retencionnro = isset($retencion) ?  $retencion->retencion :  "";
$moneda = isset($retencion) ?  $retencion->moneda : "";
$tcambio = isset($retencion) ?  Utilidades::number_f($retencion->tcambio) : "0";
$importe = isset($retencion) ?  Utilidades::number_f($retencion->importe) : "0";
$origen = isset($retencion) ?  Utilidades::number_f($retencion->origen) : "W";

?>

<?php if (isset($retencion)) : ?>
    <input type="hidden" name="regnro" value="<?= $regnro ?>">
<?php endif; ?>
<input type="hidden" name="ruc" value="<?= $ruc ?>">
<input type="hidden" name="dv" value="<?= $dv ?>">
<input type="hidden" name="codcliente" value="<?= $codcliente ?>">
<input type="hidden" name="origen" value="<?= $origen ?>">

<div class="row">
        <?= view("plantillas/message") ?>

    </div>

<div class="row form-group">
    <div class="col-3 col-md-3 pl-md-3 pl-0">
        <label for="nf-email" class=" form-control-label form-control-sm -label">Fecha:</label>
    </div>
    <div class="col-9 col-md-9">
        <input value="<?= $fecha ?>" type="date" name="fecha" class="  form-control form-control-label form-control-sm ">
    </div>
    <div class="col-3 col-md-3 pl-md-3 pl-0">
        <label for="nf-password" class=" form-control-label form-control-sm -label">N° de retención:</label>
    </div>
    <div class="col-9 col-md-9">
        <input value="<?= $retencionnro ?>" type="text" maxlength="20" name="retencion" class=" form-control form-control-label form-control-sm ">
    </div>

    <div class="col-3 col-md-3 pl-md-3 pl-0">
        <label for="nf-password" class=" form-control-label form-control-sm -label">Importe retenido:</label>
    </div>
    <div class="col-9 col-md-9">
        <input value="<?= $importe ?>" maxlength="15" oninput="formatear_entero( event);" type="text" name="importe" class=" form-control form-control-label form-control-sm ">
    </div>

    <div class="col-3 col-md-3 pl-md-3 pl-0">
        <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
    </div>
    <div class="col-9 col-md-9">
        <select valor="<?= $moneda ?>" onchange="cargar_cambio( event)" name="moneda" class=" form-control form-control-label form-control-sm "></select>
    </div>

    <div id="cambio1" class="col-3 col-md-3  pl-md-3 pl-0 d-none">
        <label for="nf-password" class=" form-control-label form-control-sm -label">Tipo de cambio:</label>
    </div>
    <div id="cambio2" class="col-9 col-md-9 d-none">
        <input value="<?= $tcambio ?>" oninput="formatear_entero(event)" type="text" name="tcambio" class=" form-control form-control-label form-control-sm text-right">
    </div>


    <div class="col-12">
        <button style="font-size: 12px;font-weight: 600;width: 100%;" type="submit" class="btn btn-success btn-sm">
            REGISTRAR
        </button>
    </div>
</div>






<script>
    //Validaciones

    function limpiar_numero_para_float(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "").replaceAll(new RegExp(/[,]{1}/g), ".");
    }


    function formatear_decimal(ev) { //

        if (ev.data == undefined) {
            ev.target.value = "0";
            return;
        }
        if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
            let noEsComa = ev.data.charCodeAt() != 44;
            let yaHayComa = ev.data.charCodeAt() == 44 && /(,){1}/.test(ev.target.value.substr(0, ev.target.value.length - 2));
            let comaPrimerLugar = ev.data.charCodeAt() == 44 && ev.target.value.length == 1;
            let comaDespuesDePunto = ev.data.charCodeAt() == 44 && /\.{1},{1}/.test(ev.target.value);
            if (noEsComa || (yaHayComa || comaPrimerLugar || comaDespuesDePunto)) {
                ev.target.value = ev.target.value.substr(0, ev.target.selectionStart - 1) + ev.target.value.substr(ev.target.selectionStart);
                return;
            } else return;
        }
        //convertir a decimal
        //dejar solo la coma decimal pero como punto 
        let solo_decimal = limpiar_numero_para_float(ev.target.value);
        let noEsComaOpunto = ev.data.charCodeAt() != 44 && ev.data.charCodeAt() != 46;
        if (noEsComaOpunto) {
            let float__ = parseFloat(solo_decimal);

            //Formato de millares 
            let enpuntos = dar_formato_millares(float__);
            $(ev.target).val(enpuntos);
        }
    }

    function formatear_entero(ev) {

        //       if (ev.data == undefined) return;
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




    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }



    async function obtener_cambio(ev) {

        let id = ev.target.value;

        if (parseInt(id) == 1) {
            $("input[name=importe]").attr("oninput", " formatear_entero(event)");
        } else {
            $("input[name=importe]").attr("oninput", "formatear_decimal(event)");
        }

        let req = await fetch("<?= base_url("monedas/show") ?>/" + id);
        let json_r = await req.json();
        if ("data" in json_r) {
            let cambio = json_r.data.tcambio;
            try {
                cambio = parseInt(cambio);
            } catch (err) {
                cambio = 0;
            }
            $("input[name=tcambio]").val(dar_formato_millares(cambio));
        }
    }

    function cargar_cambio(ev) {
        if (parseInt(ev.target.value) != 1) {
            $("#cambio1,#cambio2").removeClass("d-none");
        } else {
            $("#cambio1,#cambio2").addClass("d-none");
            let fl = parseInt($("input[name=importe]").val().replaceAll(/[\.]/g, "").replaceAll(/[,]/g, "."));
            $("input[name=importe]").val(fl);
        }

        obtener_cambio(ev);


    }




    //Fuente de datos



    async function get_monedas() {

        let req = await fetch("<?= base_url("auxiliar/monedas") ?>");
        let json_r = await req.json();
        let elegido = $("select[name=moneda]").prop("valor");
        json_r.forEach(function(obj) {
            let options = "<option value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>";
            if (elegido == obj.regnro)
                options = "<option selected value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>";
            $("select[name=moneda]").append(options);
        });

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





    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



    //procesar form

    
    function limpiar_numero_para_float(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "").replaceAll(new RegExp(/[,]{1}/g), ".");
    }

    
    async function guardar(ev) {
        ev.preventDefault();


        if ($("input[name=retencion]").val() ==  "") {
              alert("Por favor ingrese el número de retención");
              return;
          }
        //limpiar numericos
        $("input[name=tcambio]").val(
            $("input[name=tcambio]").val().replaceAll(new RegExp(/\.+/g), "").replaceAll(new RegExp(/,+/g), ".")
        );
        $("input[name=importe]").val(
            $("input[name=importe]").val().replaceAll(new RegExp(/\.+/g), "").replaceAll(new RegExp(/,+/g), ".")
        );
        show_loader();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            headers: {
                // "Content-Type": "application/json"
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: $(ev.target).serialize()
        });
        let resp = await req.json();
        hide_loader();

        if ("data" in resp) {
            alert("GUARDADO");
            window.location.reload();
        } else 
         alert(procesar_errores(resp.msj));



    }




    //init
    window.onload = function() {
        get_monedas();

    };
</script>