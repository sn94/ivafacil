<?= $this->extend("admin/layout/index") ?>
<?= $this->section("estilos") ?>
<?php

use App\Helpers\Utilidades;
use App\Models\Usuario_model;

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



<input type="hidden" id="IDCLIENTE" value="<?= $CLIENTE ?>">
<input type="hidden" id="info-compras" value="<?= base_url("admin/clientes/compras") ?>">
<input type="hidden" id="info-ventas" value="<?= base_url("admin/clientes/ventas") ?>">
<input type="hidden" id="info-ventas-a" value="<?= base_url("admin/clientes/ventas") ?>">
<input type="hidden" id="info-retencion" value="<?= base_url("admin/clientes/retencion") ?>">

<!-- Menu de Usuario -->



<div class="row ml-1">


    <div class="col-12">
        <!--cargar anios -->

        <h3>
            <?php
            $cliente_ruc_ = (new Usuario_model())->find($CLIENTE);
            echo "RUC: " . $cliente_ruc_->ruc . "-" . $cliente_ruc_->dv;
            ?>
        </h3>


        <input type="hidden" name="cliente" value="<?= $CLIENTE ?>">
        <select onchange=" cargar_tablas();" id="ANIO-REFER" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
            <?php
            for ($m = 2019; $m <= date("Y"); $m++) {
                if ($ANIO_REFER ==  $m)
                    echo "<option selected value='$m'>$m</option>";
                else
                    echo "<option value='$m'>$m</option>";
            }
            ?>
        </select>

        <!--cargar meses -->
        <select onchange=" cargar_tablas();" id="MES-REFER" style="font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $nom_mes = Utilidades::monthDescr($m);
                if ($MES_REFER ==  $m)
                    echo "<option selected value='$m'>$nom_mes</option>";
                else
                    echo "<option value='$m'>$nom_mes</option>";
            }
            ?>
        </select>

    </div>


    <div class="col-12 col-md-12" style="background-color: #b1d5a0; font-family: mainfont;font-weight: 600;">
        <div class="row">
            <div class="col-12 text-center p-0">I.V.A</div>
        </div>
        <div class="row">

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right    pt-0">Saldo inicial</div>
                <div class="col-8 col-md-12 text-right pr-4 p-md-1" id="TOTAL_SALDO_INICIAL"></div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right  pt-0">Compras</div>
                <div class="col-8 col-md-12 text-right  pr-4 p-md-1 " id="TOTAL_C"></div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right   pt-0">Ventas</div>
                <div class="col-8 col-md-12  text-right  pr-4 p-md-1 " id="TOTAL_V"> </div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right pt-0">Retención</div>
                <div class="col-8 col-md-12  text-right  pr-4 p-md-1  " id="TOTAL_R"> </div>
            </div>

            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right  pt-0">Saldo </div>
                <div class="col-8 col-md-12  text-right  pr-4 p-md-1 " id="TOTAL_S"> </div>
            </div>
            <div class="col-12 col-md">
                <div class="col-4 col-md-12 text-right  pt-0">Saldo total</div>
                <div class="col-8 col-md-12  text-right  pr-4 p-md-1 " id="TOTAL_S_TOTAL"> </div>
            </div>

        </div>
    </div>


    <div class="col-12 col-md-12">

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


    function getClienteId() {
        return $("#IDCLIENTE").val();
    }
    async function informe_compras() {
        //Obtener el resumen de compras
        //Parametros Anio, mes
        let mes = $("#MES-REFER").val();
        let anio = $("#ANIO-REFER").val();
        let url__ = $("#info-compras").val() + "/" + getClienteId() + "/" + mes + "/" + anio;
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-compras").html(loader);

        let req = await fetch(url__);

        let resp_html = await req.text();
        $("#tabla-compras").html(resp_html);

        let saldo = parseInt($("#SALDO-CONTRI").text());
        let s5 = parseInt(limpiar_numero($("#compra-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#compra-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-CONTRI").text(dar_formato_millares(saldo));
    }

    async function informe_ventas() {
        //Parametros Anio, mes
        let mes = $("#MES-REFER").val();
        let anio = $("#ANIO-REFER").val();
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas").html(loader);
        let url__ = $("#info-ventas").val() + "/" + getClienteId() + "/" + mes + "/" + anio;
        let req = await fetch(url__);

        let resp_html = await req.text();
        $("#tabla-ventas").html(resp_html);

        let saldo = parseInt($("#SALDO-FISCO").text());
        let s5 = parseInt(limpiar_numero($("#venta-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#venta-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-FISCO").text(dar_formato_millares(saldo));

    }


    async function informe_ventas_anuladas() {
        //Parametros Anio, mes
        let mes = $("#MES-REFER").val();
        let anio = $("#ANIO-REFER").val();

        // let params = $("#ventas-a-reports").serialize()+ "&cliente="+getClienteId()+"&anulados=B";
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas-a").html(loader);
        let url__ = $("#info-ventas-a").val() + "/" + getClienteId() + "/" + mes + "/" + anio + "/B";
        let req = await fetch(url__);

        let resp_html = await req.text();
        $("#tabla-ventas-a").html(resp_html);

        let saldo = parseInt($("#SALDO-FISCO").text());
        let s5 = parseInt(limpiar_numero($("#venta-a-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#venta-a-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-FISCO").text(dar_formato_millares(saldo));

    }


    async function informe_retencion() {
        //parametros
        let mes = $("#MES-REFER").val();
        let anio = $("#ANIO-REFER").val();
        //loader image
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        //URL
        let url__ = $("#info-retencion").val() + "/" + getClienteId() + "/" + mes + "/" + anio;
        $("#tabla-retencion").html(loader);
        let req = await fetch(url__);
        let resp_html = await req.text();
        $("#tabla-retencion").html(resp_html);
    }


    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    async function totales() {

        //OBTENER SALDO INICIAL
        let mes = $("select[name=month]").val();
        let anio = $("select[name=year]").val();
        let req = await fetch('<?= base_url('admin/clientes/saldo-anterior/') ?>/' +getClienteId()+'/'+  mes + '/' + anio);
        let resp = await req.json();
        let saldo_ini = 0;
        if ("data" in resp) saldo_ini = resp.data;


        let c = $("#compra-total-iva").text();
        let v = $("#venta-total-iva").text();
        let r = $("#retencion-total").text();

        let c_ = limpiar_numero(c);
        let v_ = limpiar_numero(v);
        let r_ = limpiar_numero(r);
        let saldo = parseInt(c_) + parseInt(r_) - parseInt(v_);
        $("#TOTAL_SALDO_INICIAL").text(dar_formato_millares(saldo_ini));
        $("#TOTAL_C").text(c);
        $("#TOTAL_V").text(v);
        $("#TOTAL_R").text(r);
        //Sumar saldo inicial 
        let saldo_definitivo = saldo + parseInt(saldo_ini);

        $("#TOTAL_S").text(dar_formato_millares(saldo));
        if (saldo > 0) {
            $("#TOTAL_S").css("color", "green");
            $("#TOTAL_S").addClass("table-success");
        } else {
            $("#TOTAL_S").css("color", "red");
            $("#TOTAL_S").addClass("table-danger");
        }

        $("#TOTAL_S_TOTAL").text(dar_formato_millares(saldo_definitivo));
        if (saldo_definitivo > 0) {
            $("#TOTAL_S_TOTAL").css("color", "green");
            $("#TOTAL_S_TOTAL").addClass("table-success");
        } else {
            $("#TOTAL_S_TOTAL").css("color", "red");
            $("#TOTAL_S_TOTAL").addClass("table-danger");
        }
    }


    async function cargar_tablas() {
        await informe_ventas();
        await informe_ventas_anuladas();
        await informe_compras();
        await informe_retencion();
        await totales();
        $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
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