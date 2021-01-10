<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("estilos") ?>
<?php
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



<input type="hidden" id="info-compras" value="<?= base_url("compra/index") ?>">
<input type="hidden" id="info-ventas" value="<?= base_url("venta/index") ?>">
<input type="hidden" id="info-ventas-a" value="<?= base_url("venta/index") ?>">
<input type="hidden" id="info-retencion" value="<?= base_url("retencion/index") ?>">

<!-- Menu de Usuario -->



<div class="row ml-1">

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


    async function informe_compras() {
        //Obtener el resumen de compras
        //Parametros Anio, mes
        let params = $("#compras-reports").serialize();

        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-compras").html(loader);

        let req = await fetch($("#info-compras").val(), {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: params
        });

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
        let params = $("#ventas-reports").serialize();
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas").html(loader);
        let req = await fetch($("#info-ventas").val(), {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: params
        });

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
        
        let params = ($("#ventas-a-reports").serialize()  !=  "") ? $("#ventas-a-reports").serialize() : "";
        params+=  (  params == ""  ? params : "&") +"anulados=B";
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-ventas-a").html(loader);
        let req = await fetch($("#info-ventas-a").val(), {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: params
        });

        let resp_html = await req.text();
        $("#tabla-ventas-a").html(resp_html);

        let saldo = parseInt($("#SALDO-FISCO").text());
        let s5 = parseInt(limpiar_numero($("#venta-a-total-5").text()));
        let s10 = parseInt(limpiar_numero($("#venta-a-total-10").text()));
        saldo += s5 + s10;
        $("#SALDO-FISCO").text(dar_formato_millares(saldo));

    }


    async function informe_retencion() {

        // let req = await fetch($("#info-retencion").val());
        let params = $("#retencion-reports").serialize();
        let loader = "<img  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-retencion").html(loader);
        let req = await fetch($("#info-retencion").val(), {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: params
        });

        let resp_html = await req.text();
        $("#tabla-retencion").html(resp_html);

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