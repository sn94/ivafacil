<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<style>
    
h4.text-center {
  /* color: #272727; */
  color: #646464;
  font-weight: 600;
}



.offset-md-3 > div:nth-child(1) > div:nth-child(1) {
  background-color: #d1d1d1;
}

    .wrong-factura {
        border: 2px solid #ed2328;
        background-color: #ff9595;
    }
</style>
<!-- Menu de Usuario -->

<!-- VISTA IVA -->

<div class="row mt-3">


    <div class="col-12" id="loaderplace">
    </div>
    <div class="col-12  offset-md-3 col-md-6 ">
        <div class="container p-0">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <h4   class="text-center">Factura de venta</h4>
            </div>

            <form onsubmit="guardar_factura( event )" action="<?= base_url("venta/create") ?>" method="post" class="pt-2 bg-light" style="border: 1px solid #cecece;border-radius: 0px 0px 15px 15px ;">

                <input type="hidden" name="ruc" value="<?= session("ruc") ?>">
                <input type="hidden" name="dv" value="<?= session("dv") ?>">
                <input type="hidden" name="codcliente" value="<?= session("id") ?>">
                <input type="hidden" name="iva1">
                <input type="hidden" name="iva2">
                <input type="hidden" name="iva3">
                <input type="hidden" name="origen" value="W">

                <?php echo view("plantillas/message");  ?>
                <div class="container-fluid">

                    <div class="row form-group">
                        <div class="col-12 col-md-6">
                            <div class="row">
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
                                    <input placeholder="000-000-0000000" maxlength="15" type="text" id="nf-password" name="factura" class=" form-control form-control-label form-control-sm ">
                                    <p style="color:red; font-size: 11px; font-weight: 600;" id="error-factura"></p>
                                </div>

                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <select onchange="obtener_cambio( event)" name="moneda" class=" form-control form-control-label form-control-sm "></select>
                                </div>
                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Tipo de cambio:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="0" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="formatear_entero(event)" type="text" name="tcambio" class=" form-control form-control-label form-control-sm text-right">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">10%:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="0" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe1" class=" form-control form-control-label form-control-sm text-right ">
                                </div>
                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">5%:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="0" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe2" class=" form-control form-control-label form-control-sm text-right ">
                                </div>
                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">Exenta:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input value="0" onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';" oninput="totalizar(event)" type="text" id="nf-password" name="importe3" class="  form-control form-control-label form-control-sm text-right">
                                </div>
                                <div class="col-3 col-md-3  pl-md-3 pl-0">
                                    <label for="nf-password" class=" form-control-label form-control-sm -label">TOTAL:</label>
                                </div>
                                <div class="col-9 col-md-9">
                                    <input readonly oninput="formatear_entero(event)" type="text" id="nf-password" name="total" class=" form-control form-control-label form-control-sm text-right ">
                                </div>
                            </div>
                        </div>



                    </div>


                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="container-fluid p-0" style="border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;border-radius: 20px;">

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


    function totalizar(ev) {


        formatear_entero(ev);
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
        let iva1 = Math.round(monto1_f / 11);
        let iva2 = Math.round(monto2_f / 21);
        let iva3 = 0;
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

        json_r.forEach(function(obj) {
            $("select[name=moneda]").append("<option value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>");
        });

    }



    async function obtener_cambio(ev) {

        let id = ev.target.value;

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

        /* if (!formato_valido_factura($("input[name=factura]").val())) {
             alert("Formato de Numero de factura no valido");
             return;
         }*/
        //limpiar numero de factura
        let factu = $("input[name=factura]").val().replaceAll(/-+/g, "");
        $("input[name=factura]").val(factu);
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
        if ("data" in resp) alert(resp.data);
        else alert(procesar_errores(resp.msj));

        window.location.reload();
    }





    //init
    window.onload = function() {
        get_monedas();

    };
</script>



<?= $this->endSection() ?>