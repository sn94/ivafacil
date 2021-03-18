<?php

use App\Helpers\Utilidades;
use App\Models\Usuario_model;

//User data
$userData = (new Usuario_model())->where("regnro", session("id"))
    ->where("ruc", session("ruc"))
    ->where("dv", session("dv"))->first();


$regnro = isset($venta) ?  $venta->regnro : "";
$ruc = isset($venta) ?  $venta->ruc :  session("ruc");
$dv = isset($venta) ?  $venta->dv :  session("dv");
$codcliente = isset($venta) ?  $venta->codcliente :  session("id");
$fecha = isset($venta) ?  $venta->fecha : date("Y-m-d");
$factura = isset($ultimo_numero) ?  Utilidades::formato_factura($ultimo_numero) : (isset($venta) ?  $venta->factura :  "");
$moneda = isset($venta) ?  $venta->moneda : "";
$tcambio = isset($venta) ?  Utilidades::number_f($venta->tcambio) : "0";
$importe1 = isset($venta) ?  Utilidades::number_f($venta->importe1) : "0";
$importe2 = isset($venta) ?  Utilidades::number_f($venta->importe2) : "0";
$importe3 = isset($venta) ?  Utilidades::number_f($venta->importe3) : "0";
$total = isset($venta) ?  Utilidades::number_f($venta->total) : "0";
$iva1 = isset($venta) ?  Utilidades::number_f($venta->iva1) : "0";
$iva2 = isset($venta) ?  Utilidades::number_f($venta->iva2) : "0";
$iva3 = isset($venta) ?  Utilidades::number_f($venta->iva3) : "0";
$iva_incluido = isset($compra) ?  ($compra->iva_incluido == "S" ? "checked" : "") : "checked";
$origen = isset($venta) ?  Utilidades::number_f($venta->origen) : "W";
$estado =  isset($venta)  ? ($venta->estado == "A" ? "" : "checked")  : "";
$timbrado = isset($venta) ? $venta->timbrado : ($userData->timbrado);
?>


<?php if (isset($venta)) : ?>
    <input type="hidden" name="regnro" value="<?= $regnro ?>">
<?php endif; ?>

<input type="hidden" name="ruc" value="<?= $ruc ?>">
<input type="hidden" name="dv" value="<?= $dv ?>">
<input type="hidden" name="codcliente" value="<?= $codcliente ?>">
<input type="hidden" name="iva1" value="<?= $iva1 ?>">
<input type="hidden" name="iva2" value="<?= $iva2 ?>">
<input type="hidden" name="iva3" value="<?= $iva3 ?>">
<input type="hidden" name="origen" value="<?= $origen ?>">

<?php echo view("plantillas/message");  ?>
<div class="container-fluid">

    <div class="row form-group">
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-4 col-md-4  pl-md-3 pl-0">
                    <label for="nf-email" class=" form-control-label form-control-sm -label">Fecha:</label>
                </div>
                <div class="col-8 col-md-8">
                    <input value="<?= $fecha ?>" type="date" id="nf-email" name="fecha" class="  form-control form-control-label form-control-sm ">
                </div>
                <div class="col-4 col-md-4  pl-md-3 pl-0 pb-0 mb-0">
                    <label for="nf-password" class="mb-0 form-control-label form-control-sm -label">Fact. N°:</label>
                </div>
                <div class="col-8 col-md-8 pb-0 mb-0">
                    <input oninput="solo_numeros_guiones(event)" value="<?= $factura ?>" placeholder="000-000-0000000" maxlength="15" type="text" id="nf-password" name="factura" class=" form-control form-control-label form-control-sm mb-0 ">

                    <span style="font-size: 11px; font-weight: 600;color: red;">
                        ANULADO <input onchange="mostrarTimbrado(event)" <?= $estado ?> type="checkbox" name="estado" value="B">
                    </span>
                    <p style="color:red; font-size: 11px; font-weight: 600;" id="error-factura"></p>
                </div>



                <div class="col-3 col-md-3  pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
                </div>
                <div class="col-9 col-md-9">
                    <select valor="<?= $moneda ?>" onchange="obtener_cambio( event)" name="moneda" class=" form-control form-control-label form-control-sm "></select>
                </div>
                <div class="col-3 col-md-3  pl-md-3 pl-0 d-none CAMBIO">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">Tipo de cambio:</label>
                </div>
                <div class="col-9 col-md-9  d-none CAMBIO">
                    <input value="<?= $tcambio ?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="formatear_entero(event)" type="text" name="tcambio" class=" form-control form-control-label form-control-sm text-right">
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="row">
                <div id="TIMBRADO" class="col-12 d-none" style="display: grid;grid-template-columns: 40% 60%;">
                    <label style="grid-column-start: 1;" for="nf-password" class=" form-control-label form-control-sm -label">N° Timbrado:</label>
                    <input style="grid-column-start: 2;" value="<?= $timbrado ?>" type="text" id="nf-password" name="timbrado" class=" form-control form-control-label form-control-sm text-right ">
                </div>

                <div class="col-3 col-md-3  pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">10%:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input value="<?= $importe1 ?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe1" class=" form-control form-control-label form-control-sm text-right ">
                </div>
                <div class="col-3 col-md-3  pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">5%:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input value="<?= $importe2 ?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe2" class=" form-control form-control-label form-control-sm text-right ">
                </div>
                <div class="col-3 col-md-3  pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">Exenta:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input value="<?= $importe3 ?>" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe3" class="  form-control form-control-label form-control-sm text-right">
                </div>
                <div class="col-3 col-md-3  pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">TOTAL:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input value="<?= $total ?>" readonly oninput="formatear_entero(event)" type="text" id="nf-password" name="total" class=" form-control form-control-label form-control-sm text-right ">
                </div>
            </div>
        </div>



    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container-fluid p-0" style="border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;border-radius: 20px;">

                <input type="hidden" name="iva_incluido" value="S">


                <h6 class="text-center" style="color: #515050;font-weight: 600;border: 1px solid #cecece;background-color: #b7b3b3;border-radius: 10px 10px 0px 0px;">Total IVA</h6>
                <div class="row form-group">
                    <div class="col-3" style="font-size: 12px;">
                        10%
                    </div>
                    <div class="col-9"><input value="0" class="form-control form-control-sm text-right" type="text" id="iva1" readonly></div>
                    <div class="col-3" style="font-size: 12px;">
                        5%
                    </div>
                    <div class="col-9"><input value="0" class="form-control form-control-sm text-right" type="text" id="iva2" readonly></div>
                </div>

            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="row">

                <div class="col-12">
                    <button style="font-size: 12px;font-weight: 600; width: 100%;" type="submit" class="btn btn-success">
                        GUARDAR
                    </button>
                </div>



            </div>
        </div>

    </div>

</div>
<!--end inner container fluid -->




<script>
    /** 
Validaciones
 */


    function factura_input(ev) {

        let cadena = ev.target.value;
        // if (ev.data != undefined && ev.data != null) 

        if (!(/\d/.test(ev.data)) && (ev.data != undefined && ev.data != null)) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) +
                ev.target.value.substr(ev.target.selectionStart);
        }

        if (ev.target.selectionStart == 3 || ev.target.selectionStart == 7) {
            /*  if (!(/\d/.test(cadena.charAt(ev.target.selectionStart - 1)))) {
                  ev.target.value =
                      ev.target.value.substr(0, ev.target.selectionStart - 1) +
                      ev.target.value.substr(ev.target.selectionStart);
              }*/

            ev.target.value = ev.target.value + "-";
        }
        // }

        //moldear
        let cad = ev.target.value.replaceAll(/\-/g, "");
        let nuevacadena = "";

        for (let a = 0; a < cad.length; a++) {

            nuevacadena += cad.charAt(a);
            if ((a == 2 || a == 5))
                nuevacadena += "-";
        }
        ev.target.value = nuevacadena;


    }



    function formato_valido_factura(valor) {
        let valido = /(\d{3})-(\d{3})-(\d{7})/.test(valor);
        return valido;
    }


    function solo_numeros_guiones(ev) {
        //0 48   9 57
        if (ev.data == null) return;
        if (ev.data.charCodeAt() != 45 && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
            let cad = ev.target.value;
            let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
            ev.target.value = cad_n;
        }
    }


    function solo_numeros(ev) {
        //0 48   9 57
        if (ev.data == null) return;
        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
            let cad = ev.target.value;
            let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
            ev.target.value = cad_n;
        }
    }


    function factura_format(ev) {

        let valido = formato_valido_factura(ev.target.value);
        if (!valido) {
            $(ev.target).addClass("wrong-factura");
            $("#error-factura").text("Formato no válido. Formato sugerido: 000-000-0000000");
        } else {
            $(ev.target).removeClass("wrong-factura");
            $("#error-factura").text("");
        }

    }


    function limpiar_numero_para_float(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "").replaceAll(new RegExp(/[,]{1}/g), ".");
    }


    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
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

        try {
            if (parseInt(enpuntos) == 0) $(ev.target).val("");
            else $(ev.target).val(enpuntos);
        } catch (err) {
            $(ev.target).val(enpuntos);
        }

    }



    function esIVA_INCLUIDO() {
        return $("input[type=checkbox][name=iva_incluido]").prop("checked");
    }


    function mutar_indicador_iva_incluido(ev) {

        if ($(ev.target).prop("checked"))
            $("input[type=hidden][name=iva_incluido]").prop("disabled", true);
        else
            $("input[type=hidden][name=iva_incluido]").prop("disabled", false);
        totalizar();
    }


    function totalizar(ev) {

        if (ev != undefined) {
            if (parseInt($("select[name=moneda]").val()) == 1)
                formatear_entero(ev);
            else
                formatear_decimal(ev);
        }

        let monto1 = limpiar_numero_para_float($("input[name=importe1]").val());
        let monto2 = limpiar_numero_para_float($("input[name=importe2]").val());
        let monto3 = limpiar_numero_para_float($("input[name= importe3]").val());
        let monto1_f = isNaN(parseFloat(monto1)) ? 0 : parseFloat(monto1);
        let monto2_f = isNaN(parseFloat(monto2)) ? 0 : parseFloat(monto2);
        let monto3_f = isNaN(parseFloat(monto3)) ? 0 : parseFloat(monto3);

        let tot = monto1_f + monto2_f + monto3_f;

        $("input[name=total]").val(dar_formato_millares(tot));
        //calculos de iva 
        //TIPO CALCULO: IVA INCLUIDO
        let iva1 = Math.round(monto1_f / 11);
        let iva2 = Math.round(monto2_f / 21);
        let iva3 = 0;
        /**No incluido */
        if (!(esIVA_INCLUIDO())) {
            iva1 = Math.round(monto1_f * (10 / 100));
            iva2 = Math.round(monto2_f * (5 / 100));
            iva3 = 0;
        }
        $("input[name=iva1]").val(iva1);
        $("input[name=iva2]").val(iva2);
        $("input[name=iva3]").val(iva3);
        $("#iva1").val(dar_formato_millares(iva1));
        $("#iva2").val(dar_formato_millares(iva2));
        // if (ev.target.value == "") ev.target.value = "0";
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



    async function obtener_cambio(ev) {

        let id = ev.target.value;

        if (parseInt(id) == 1) {
            $(".CAMBIO").addClass("d-none");
        } else {
            $(".CAMBIO").removeClass("d-none");
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






    /**
    ***
    Envio de formulario
    */



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




    function campos_vacios() {
        if ($("input[name=estado]").prop("checked")) return false;

        if (($("input[name=importe1]").val() == "" || $("input[name=importe1]").val() == "0") &&
            ($("input[name=importe2]").val() == "" || $("input[name=importe2]").val() == "0") &&
            ($("input[name=importe3]").val() == "" || $("input[name=importe3]").val() == "0")) {
            alert("Indique al menos de estos importes: 10% | 5% | Exenta");
            return true;
        }

        /*  if ($("input[name=factura]").val() == "") {
              alert("Indique el número de factura");
              return true;
          }*/
        if ($("select[name=moneda]").val() != "1" && $("input[name=tcambio]").val() == "") {
            alert("Indique el tipo de cambio");
            return true;
        }
        return false;

    }

    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }


    async function guardar_factura(ev) {
        ev.preventDefault();
        if (campos_vacios()) return;

        if ($("input[name=factura]").val() == "") {
            alert("Por favor ingrese el número de factura");
            return;
        }
        //limpiar numero de factura
        //  let factu = $("input[name=factura]").val().replaceAll(/-+/g, "");
        // $("input[name=factura]").val(factu);
        //reemplazar comas por puntos, eliminar los otros puntos
        $("input[name=importe1]").val(limpiar_numero_para_float($("input[name=importe1]").val()));
        $("input[name=importe2]").val(limpiar_numero_para_float($("input[name=importe2]").val()));
        $("input[name=importe3]").val(limpiar_numero_para_float($("input[name=importe3]").val()));
        $("input[name=total]").val(limpiar_numero_para_float($("input[name=total]").val()));
        $("input[name=tcambio]").val(limpiar_numero_para_float($("input[name=tcambio]").val()));

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
            alert("ACTUALIZADO");
            window.location.reload();
        } else {
            $("input[name=importe1]").val(dar_formato_millares($("input[name=importe1]").val()));
            $("input[name=importe2]").val(dar_formato_millares($("input[name=importe2]").val()));
            $("input[name=importe3]").val(dar_formato_millares($("input[name=importe3]").val()));
            $("input[name=total]").val(dar_formato_millares($("input[name=total]").val()));
            $("input[name=tcambio]").val(dar_formato_millares($("input[name=tcambio]").val()));
            alert(procesar_errores(resp.msj));
        }


    }








    function mostrarTimbrado(ev) {

        let check = $(ev.currentTarget).prop("checked");
        if (check)
            $("#TIMBRADO").removeClass("d-none");
        else $("#TIMBRADO").addClass("d-none");
    }

    //init
    window.onload = function() {
        get_monedas();

    };
</script>