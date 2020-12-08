<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<style>
    .wrong-factura {
        border: 2px solid #ed2328;
        background-color: #ff9595;
    }
</style>
<!-- Menu de Usuario -->

<!-- VISTA IVA -->

<div class="row m-0">


    <div class="col-12  offset-md-2 col-md-8 ">
        <div class="container p-0">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <strong>Registro de factura de compra</strong>
            </div>

            <form onsubmit="guardar_factura( event )" action="<?= base_url("movimiento/r_f_compra/N") ?>" method="post" class="pt-2" style="border: 1px solid #cecece;border-radius: 0px 0px 15px 15px ;">

                <input type="hidden" name="ruc"  value="<?= session("ruc")?>" >
                <input type="hidden" name="dv"  value="<?= session("dv")?>">
                <input type="hidden" name="codcliente"  value="<?= session("id")?>">
                <input type="hidden" name="iva1">
                <input type="hidden" name="iva2">
                <input type="hidden" name="iva3">

                <?php echo view("plantillas/message");  ?>
                <div class="container-fluid">

                    <div class="row form-group">
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-email" class=" form-control-label form-control-sm -label">Fecha:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="<?= date("Y-m-d") ?>" type="date" id="nf-email" name="fecha" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">N° de factura:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input oninput="factura_format(event)" value="000-000-0000000" maxlength="15" type="text" id="nf-password" name="factura" class=" form-control form-control-label form-control-sm ">
                            <p style="color:red; font-size: 11px; font-weight: 600;" id="error-factura"></p>
                        </div>

                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <select name="moneda" class=" form-control form-control-label form-control-sm "></select>
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Tipo de cambio:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="0" oninput="formatear(event)" type="text" name="tcambio" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">10%:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="0" oninput="totalizar(event)" type="text" id="nf-password" name="importe1" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">5%:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="0" oninput="totalizar(event)" type="text" id="nf-password" name="importe2" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Exenta:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="0" oninput="totalizar(event)" type="text" id="nf-password" name="importe3" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">TOTAL:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input readonly oninput="formatear(event)" type="text" id="nf-password" name="total" class=" form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-12 col-md-12 ">
                            <div class="container-fluid p-0" style="border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;border-radius: 20px;">

                                <h6 class="text-center" style="border: 1px solid #cecece;background-color: #a5df99;border-radius: 10px 10px 0px 0px;">Total IVA</h6>
                                <dl class="row">
                                    <dt class="col-3 col-md-3 col-lg-2 " style="font-size: 12px;">
                                        10%
                                    </dt>
                                    <dd class="col-9 col-md-9 col-lg-4"><input value="0" class="form-control form-control-sm" type="text" id="iva1" readonly></dd>
                                    <dt class="col-3 col-md-3 col-lg-2 " style="font-size: 12px;">
                                        5%
                                    </dt>
                                    <dd class="col-9 col-md-9 col-lg-4 "><input value="0" class="form-control form-control-sm" type="text" id="iva2" readonly></dd>
                                </dl>

                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-12 col-md-4">
                            <a style="font-size: 11px;font-weight: 600;display:block;" href="<?= base_url("/") ?>" class="btn btn-success">
                                <i class="fa fa-dot-circle-o"></i> IR AL MENÚ
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <a style="font-size: 11px;font-weight: 600;display:block;" href="<?= base_url("movimiento/index") ?>" class="btn btn-success ">
                                <i class="fa fa-dot-circle-o"></i> REGISTRAR OTROS COMPROBANTES
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <button style="font-size: 11px;font-weight: 600; width: 100%;" type="submit" class="btn btn-success">
                                <i class="fa fa-dot-circle-o"></i> SEGUIR REGISTRANDO
                            </button>
                        </div>
                    </div>

                </div>
                <!--end inner container fluid -->
            </form>
        </div>
    </div>
    <!--end second col -->

</div>




<script>
    /** 
Validaciones
 */

    function formato_valido_factura(valor) {
        let valido = /(\d{3})-(\d{3})-(\d{7})/.test(valor);
        return valido;
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

    function formatear(ev) {

        if (ev.data == undefined) {  ev.target.value= "0";  return;}
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


    function totalizar(ev) {
        formatear(ev);
        let monto1 = limpiar_numero_para_float($("input[name=importe1]").val());
        let monto2 = limpiar_numero_para_float($("input[name=importe2]").val());
        let monto3 = limpiar_numero_para_float($("input[name= importe3]").val());
        let monto1_f = isNaN(parseFloat(monto1)) ? 0 : parseFloat(monto1);
        let monto2_f = isNaN(parseFloat(monto2)) ? 0 : parseFloat(monto2);
        let monto3_f = isNaN(parseFloat(monto3)) ? 0 : parseFloat(monto3);

        let tot = monto1_f + monto2_f + monto3_f;
        console.log(monto1_f, monto2_f, monto3_f, tot);
        $("input[name=total]").val(dar_formato_millares(tot));
        //calculos de iva
        let iva1 = monto1_f / 11;
        let iva2 = monto2_f / 21;
        let iva3 = 0;
        $("input[name=iva1]").val(iva1);
        $("input[name=iva2]").val(iva2);
        $("input[name=iva3]").val(iva3);
        $("#iva1").val(dar_formato_millares(iva1));
        $("#iva2").val(dar_formato_millares(iva2));
        if( ev.target.value == "")  ev.target.value= "0";
    }




    //Fuente de datos


    async function get_monedas() {

        let req = await fetch("<?= base_url("auxiliar/monedas") ?>");
        let json_r = await req.json();

        json_r.forEach(function(obj) {
            $("select[name=moneda]").append("<option value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>");
        });

    }






    /**
    ***
    Envio de formulario
    */

    function campos_vacios() {
        if ($("input[name=importe1]").val() == "" && $("input[name=importe2]").val() == "" && $("input[name=importe3]").val() == "") {
            alert("Indique al menos de estos importes: 10% | 5% | Exenta");
            return true;
        }

        if ($("input[name=factura]").val() == "") {
            alert("Indique el número de factura");
            return true;
        }
        if ($("select[name=moneda]").val() != "1" && $("input[name=tcambio]").val() == "") {
            alert("Indique el tipo de cambio");
            return true;
        }
        return false;

    }

    function guardar_factura(ev) {
        ev.preventDefault();
        if (campos_vacios()) return;
        //limpiar numero de factura
        let factu= $("input[name=factura]").val().replaceAll(/-+/g, "");
        $("input[name=factura]").val( factu);
        //reemplazar comas por puntos, eliminar los otros puntos
        $("input[name=importe1]").val(limpiar_numero_para_float($("input[name=importe1]").val()));
        $("input[name=importe2]").val(limpiar_numero_para_float($("input[name=importe2]").val()));
        $("input[name=importe3]").val(limpiar_numero_para_float($("input[name=importe3]").val()));
        $("input[name=total]").val(limpiar_numero_para_float($("input[name=total]").val()));
        $("input[name=tcambio]").val(limpiar_numero_para_float($("input[name=tcambio]").val()));
        ev.target.submit();
    }


    //init
    window.onload = function() {
        get_monedas();

    };
</script>



<?= $this->endSection() ?>