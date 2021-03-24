<?php

if ($MODO == "ADMIN")
    echo $this->extend("admin/layout/index");
else
    echo $this->extend("layouts/index_cliente");


?>

<?= $this->section("estilos") ?>
<?php

use App\Helpers\Utilidades;

$estilo = <<<EOF
<style>

h5{
    color: #404040; 
    font-weight: 600;
}
</style>
EOF;
echo $estilo;

?>
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<?php
$INFO_COMPRAS = "";
$INFO_VENTAS = "";
$INFO_VENTAS_A = "";
$INFO_RETENCION = "";
$INFO_SALDO_ANTERIOR = "";
$INFO_TOTALES = "";
$INFO_CLIENTE = "";

//modo de rutas
if ($MODO == "ADMIN") {
    $INFO_COMPRAS =  base_url("admin/clientes/compras/$CLIENTE");
    $INFO_VENTAS =  base_url("admin/clientes/ventas/$CLIENTE");
    $INFO_VENTAS_A = base_url("admin/clientes/ventas/$CLIENTE");
    $INFO_RETENCION =  base_url("admin/clientes/retencion/$CLIENTE");
    $INFO_SALDO_ANTERIOR =  base_url("admin/clientes/saldo-anterior");
    $INFO_TOTALES = base_url("admin/totales-mes");
    $INFO_CLIENTE =  $CLIENTE;
} else {
    $INFO_COMPRAS =  base_url("compra/index");
    $INFO_VENTAS =  base_url("venta/index");
    $INFO_VENTAS_A = base_url("venta/index");
    $INFO_RETENCION =  base_url("retencion/index");
    $INFO_SALDO_ANTERIOR =  base_url("cierres/calcular-saldo-anterior");
    $INFO_TOTALES = base_url('cierres/totales-mes');
}

?>

<input type="hidden" id="info-cliente" value="<?= $INFO_CLIENTE ?>">
<input type="hidden" id="info-compras" value="<?= $INFO_COMPRAS ?>">
<input type="hidden" id="info-ventas" value="<?= $INFO_VENTAS ?>">
<input type="hidden" id="info-ventas-a" value="<?= $INFO_VENTAS_A ?>">
<input type="hidden" id="info-retencion" value="<?= $INFO_RETENCION ?>">
<input type="hidden" id="info-saldo-anterior" value="<?= $INFO_SALDO_ANTERIOR ?>">
<input type="hidden" id="info-totales" value="<?= $INFO_TOTALES ?>">
<!-- Menu de Usuario -->



<div class="row ml-1">


    <div class="col-12">
        <h3 class="text-center">Movimientos del Mes</h3>
    </div>
    <div class="col-12">
        <!--cargar anios -->
        <select onchange="$('#download-2').val('');cargar_tablas();" name="year" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
            <?php
            for ($m = 2019; $m <= date("Y"); $m++) {
                if ($year ==  $m)
                    echo "<option selected value='$m'>$m</option>";
                else
                    echo "<option value='$m'>$m</option>";
            }
            ?>
        </select>

        <!--cargar meses -->
        <select onchange="$('#download-2').val('');cargar_tablas();" name="month" style="font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $nom_mes = Utilidades::monthDescr($m);
                if ($month ==  $m)
                    echo "<option selected value='$m'>$nom_mes</option>";
                else
                    echo "<option value='$m'>$nom_mes</option>";
            }
            ?>
        </select>

        <button type="submit" style="display: none;"></button>


    </div>


    <!-- TOTALES DE IMPORTE DE FACTURA -->

    <div class="col-12 col-md-12 mt-1 " style="font-family: mainfont;font-weight: 600;">

        <div class="row" style="background-color: #b1d5a0;">
            <div class="col-12 text-center p-0">TOTALES</div>
        </div>
        <div class="row">

            <div class="col-12 col-md text-center">
                Compras
                <div class="row">
                    <div class="col-12 col-md ">
                        <div class="col-3 col-md-12 text-center   pt-0  border-bottom border-success">10%</div>
                        <div class="col-9 col-md-12 text-center pr-4 p-md-1" id="IMPORTE_10_C"> 0 </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="col-3 col-md-12 text-center  pt-0  border-bottom border-success">5%</div>
                        <div class="col-9 col-md-12 text-center  pr-4 p-md-1 " id="IMPORTE_5_C">0</div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-md text-center">
                Ventas
                <div class="row">
                    <div class="col-12 col-md">
                        <div class="col-3 col-md-12 text-center   pt-0  border-bottom border-success">10%</div>
                        <div class="col-9 col-md-12  text-center  pr-4 p-md-1 " id="IMPORTE_10_V">0 </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="col-3 col-md-12 text-center pt-0  border-bottom border-success">5%</div>
                        <div class="col-9 col-md-12  text-center  pr-4 p-md-1  " id="IMPORTE_5_V">0 </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md  text-center">
                Retención
                <div class="row">
                    <div class="col-3 col-md-12 text-center pt-0  border-bottom border-success">Monto tot.</div>
                    <div class="col-9 col-md-12  text-center  pr-4 p-md-1  " id="IMPORTE_R">0 </div>
                </div>
            </div>

        </div>

    </div>
    <!-- END TOTALES  IMPORTE DE FACTURA -->

    <div class="col-12 col-md-12 " style="font-family: mainfont;font-weight: 600;">

        <div class="row">
            <div class="col-12 text-center p-0" style="background-color: #b1d5a0;  ">I.V.A</div>
        </div>
        <div class="row">

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center    pt-0   border-bottom border-success">Saldo inicial</div>
                <div class="col-8 col-md-12 text-center pr-4 p-md-1" id="TOTAL_SALDO_INICIAL">0</div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center  pt-0   border-bottom border-success">Compras</div>
                <div class="col-8 col-md-12 text-center  pr-4 p-md-1 " id="TOTAL_C">0</div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center   pt-0   border-bottom border-success">Ventas</div>
                <div class="col-8 col-md-12  text-center  pr-4 p-md-1 " id="TOTAL_V"> 0</div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center pt-0   border-bottom border-success">Retención</div>
                <div class="col-8 col-md-12  text-center  pr-4 p-md-1  " id="TOTAL_R">0 </div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center  pt-0   border-bottom border-success">Saldo </div>
                <div class="col-8 col-md-12  text-center  pr-4 p-md-1 " id="TOTAL_S">0 </div>
            </div>
            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-center  pt-0   border-bottom border-success">Saldo total</div>
                <div class="col-8 col-md-12  text-center  pr-4 p-md-1 " id="TOTAL_S_TOTAL">0 </div>
            </div>

        </div>

    </div>
    <div class="col-12 col-md-12 mt-2">

        <h5 class="text-center">VENTAS</h5>
        <div id="tabla-ventas">
            <table style="font-size: 11px;" class="table table-bordered ">

            </table>
        </div>
    </div>

    <div class="col-12 col-md-12">

        <h5 class="text-center">VENTAS ANULADAS</h5>
        <div id="tabla-ventas-a">
            <table style="font-size: 11px;" class="table table-bordered ">

            </table>
        </div>
    </div>


    <div class="col-12 col-md-12">

        <h5 class="text-center">COMPRA</h5>
        <div id="tabla-compras">

        </div>
    </div>

    <div class="col-12 col-md-12">

        <h5 class="text-center">RETENCIONES</h5>
        <div id="tabla-retencion">
            <table style="font-size: 11px;" class="table table-bordered ">


            </table>
        </div>
    </div>

    <!--
    <div class="col-12">

        <dl class="row">
            <dt class="col-12 col-md-6" style="border-bottom: 1px solid #555;">SALDO A FAVOR DEL CONTRIBUYENTE </dt>
            <dd class="col-12 col-md-6" id="SALDO-CONTRI"> 0 </dd>
            <dt class="col-12 col-md-6" style="border-bottom: 1px solid #555;">SALDO A FAVOR DEL FISCO </dt>
            <dd class="col-12 col-md-6 " id="SALDO-FISCO">0 </dd>
        </dl>


    </div> -->

    <div class="col-12">
        <button type="button" onclick="cargar_tablas()" class="btn btn-dark mt-3 ">ACTUALIZAR</button>

    </div>


</div>

<script>
    var total_iva_10 = 0,
        total_iva_5 = 0,
        total_ex = 0;


    async function _informe_compras(ARG) {
        //es objeto

        let URL__ = "";
        if (typeof ARG == "object") {
            ARG.preventDefault();
            URL__ = ARG.currentTarget.href;
        } else URL__ = ARG;
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-compras").html(loader);
        let req = await fetch(URL__);
        let resp_html = await req.text();
        $("#tabla-compras").html(resp_html);
        let saldo = parseInt($("#SALDO-CONTRI").text());
        let s5 = parseInt(limpiar_numero($("#compra-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#compra-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-CONTRI").text(dar_formato_millares(saldo));
    }
    async function informe_compras() {
        //Obtener el resumen de compras
        //Parametros Anio, mes
        let mes_ = $("select[name=month]").val();
        let anio_ = $("select[name=year]").val();

        await _informe_compras($("#info-compras").val() + "/" + mes_ + "/" + anio_);
    }




    async function _informe_ventas(ARG) {
        let URL__ = "";
        if (typeof ARG == "object") {
            ARG.preventDefault();
            URL__ = ARG.currentTarget.href;
        } else URL__ = ARG;

        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas").html(loader);
        let req = await fetch(URL__);

        let resp_html = await req.text();
        $("#tabla-ventas").html(resp_html);

        let saldo = parseInt($("#SALDO-FISCO").text());
        let s5 = parseInt(limpiar_numero($("#venta-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#venta-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-FISCO").text(dar_formato_millares(saldo));


    }
    async function informe_ventas() {
        //Parametros Anio, mes
        let mes_ = $("select[name=month]").val();
        let anio_ = $("select[name=year]").val();
        let ruta = $("#info-ventas").val() + "/" + mes_ + "/" + anio_;
        await _informe_ventas(ruta);
    }



    async function _informe_ventas_anuladas(ARG) {
        let URL__ = "";
        if (typeof ARG == "object") {
            ARG.preventDefault();
            URL__ = ARG.currentTarget.href;
        } else URL__ = ARG;

        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas-a").html(loader);
        let req = await fetch(URL__);

        let resp_html = await req.text();
        $("#tabla-ventas-a").html(resp_html);

        let saldo = parseInt($("#SALDO-FISCO").text());
        let s5 = parseInt(limpiar_numero($("#venta-a-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#venta-a-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-FISCO").text(dar_formato_millares(saldo));

    }


    async function informe_ventas_anuladas() {
        //Parametros Anio, mes

        let mes_ = $("select[name=month]").val();
        let anio_ = $("select[name=year]").val();
        let url__ = $("#info-ventas-a").val() + "/" + mes_ + "/" + anio_ + "/B";
        await _informe_ventas_anuladas(url__);
    }


    async function _informe_retencion(ARG) {
        let URL__ = "";
        if (typeof ARG == "object") {
            ARG.preventDefault();
            URL__ = ARG.currentTarget.href;
        } else URL__ = ARG;
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-retencion").html(loader);
        let req = await fetch(URL__);

        let resp_html = await req.text();
        $("#tabla-retencion").html(resp_html);


    }

    async function informe_retencion() {

        // let req = await fetch($("#info-retencion").val());
        let mes_ = $("select[name=month]").val();
        let anio_ = $("select[name=year]").val();

        let URL = $("#info-retencion").val() + "/" + mes_ + "/" + anio_;
        _informe_retencion(URL);

    }



    async function totales() {

        //OBTENER SALDO INICIAL
        let mes = $("select[name=month]").val();
        let anio = $("select[name=year]").val();
        //saldo anterior

        let saldo_anterior_url = $("#info-saldo-anterior").val() + '/' + mes + '/' + anio;
        let idcliente = $("#info-cliente").val();
        if (idcliente != "") saldo_anterior_url += "/" + idcliente;
        let req = await fetch(saldo_anterior_url, {
            headers: {
                formato: "JSON"
            }
        });
        let resp = await req.json();
        let saldo_ini = 0;
        if ("data" in resp) saldo_ini = resp.data;

        //otros totales-mes-session

        let totalesUrl = $("#info-totales").val() + '/' + mes + '/' + anio;
        if (idcliente != "") totalesUrl += "/" + idcliente;


        let req_2 = await fetch(totalesUrl);
        let resp_2 = await req_2.json();
        //totales en  iva compra  venta retencion
        let c = 0;
        let v = 0;
        let r = 0;
        let c_imp_10 = 0;
        let v_imp_10 = 0;
        let c_imp_5 = 0;
        let v_imp_5 = 0;
        let r_imp = 0;
        try {
            c = parseInt(resp_2.compras_iva10) + parseInt(resp_2.compras_iva5);
            v = parseInt(resp_2.ventas_iva10) + parseInt(resp_2.ventas_iva5);
            r = parseInt(resp_2.retencion);
            //importes de factura o comprobantes
            c_imp_10 = parseInt(resp_2.compras_total_10);
            c_imp_5 = parseInt(resp_2.compras_total_5);
            v_imp_10 = parseInt(resp_2.ventas_total_10);
            v_imp_5 = parseInt(resp_2.ventas_total_5);
            c_imp_10 = parseInt(resp_2.compras_total_10);
            r_imp = parseInt(resp_2.retencion);
        } catch (err) {
            c = 0;
            v = 0;
            r = 0;
            alert(err);
        } finally {

        }
        /* let c = $("#compra-total-iva").text();
         let v = $("#venta-total-iva").text();
         let r = $("#retencion-total").text();*/

        let c_ = limpiar_numero(String(c));
        let v_ = limpiar_numero(String(v));
        let r_ = limpiar_numero(String(r));
        let saldo = parseInt(c_) + parseInt(r_) - parseInt(v_);
        //importe de factura
        $("#IMPORTE_10_C").text(dar_formato_millares(c_imp_10));
        $("#IMPORTE_5_C").text(dar_formato_millares(c_imp_5));
        $("#IMPORTE_10_V").text(dar_formato_millares(v_imp_10));
        $("#IMPORTE_5_V").text(dar_formato_millares(v_imp_5));
        $("#IMPORTE_R").text(dar_formato_millares(r_imp));

        $("#TOTAL_SALDO_INICIAL").text(dar_formato_millares(saldo_ini));
        $("#TOTAL_C").text(dar_formato_millares(c));
        $("#TOTAL_V").text(dar_formato_millares(v));
        $("#TOTAL_R").text(dar_formato_millares(r));
        //Sumar saldo inicial 
        let saldo_definitivo = saldo + parseInt(saldo_ini);

        $("#TOTAL_S").text(dar_formato_millares(saldo));
        if (saldo > 0) {
            $("#TOTAL_S").css("color", "green");
            $("#TOTAL_S").removeClass("table-danger");
            $("#TOTAL_S").addClass("table-success");
        } else {
            $("#TOTAL_S").css("color", "red");
            $("#TOTAL_S").removeClass("table-success");
            $("#TOTAL_S").addClass("table-danger");
        }

        $("#TOTAL_S_TOTAL").text(dar_formato_millares(saldo_definitivo));
        if (saldo_definitivo > 0) {
            $("#TOTAL_S_TOTAL").css("color", "green");
            $("#TOTAL_S_TOTAL").removeClass("table-danger");
            $("#TOTAL_S_TOTAL").addClass("table-success");
        } else {
            $("#TOTAL_S_TOTAL").css("color", "red");
            $("#TOTAL_S_TOTAL").removeClass("table-success");
            $("#TOTAL_S_TOTAL").addClass("table-danger");
        }
    }

    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    async function cargar_tablas() {


        await informe_ventas();
        await informe_ventas_anuladas();
        await informe_compras();
        await informe_retencion();
        await totales();


        let mes = $("select[name=month]").val();
        let anio = $("select[name=year]").val();
        $("form input[name=month]").val(mes);
        $("form input[name=year]").val(anio);
        //   $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
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
    async function borrar_opera(ev, action) {
        ev.preventDefault();
        if (!confirm("Borrar operación?")) return;

        let req = await fetch(ev.currentTarget.href);
        let resp = await req.json();
        if ("data" in resp) {
            action();

        } else {
            alert(procesar_errores(data.msj));
        }
    }



    window.onload = function() {

        cargar_tablas();
    };
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>