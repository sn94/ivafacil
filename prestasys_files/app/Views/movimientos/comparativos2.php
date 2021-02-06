<?php

use App\Helpers\Utilidades;
use App\Models\Parametros_model;

?>
<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("estilos") ?>

<style>
    /* Elemento | http://localhost/ivafacil/usuario/view-cierre-mes */

    .card-header>h4:nth-child(1) {

        font-weight: 600;
        font-size: 1.5rem;
        color: #646464;
    }


    .card-header {
        background-color: rgba(0, 0, 0, 0.16);
        border-radius: 15px 15px 0px 0px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>









<input type="hidden" id="info-totales" value="<?= base_url("cierres/totales-anio-session") ?>">

<!-- Menu de Usuario -->



<div class="row">

    <div id="loaderplace" class="col-12"></div>

    

    <div class="col-12  offset-md-2 col-md-8 ">
        <h3 class="text-center">Cuadros comparativos</h3>

         


        <!--cuadro comparativo de totales por cada año-->
        <div id="comparativo-anio-2">


        </div>
    </div>


</div>

<script>
    async function saldo_anterior_anio() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch("<?= base_url("cierres/saldo-anterior-anio") ?>");
        let resp_json = await req.json();
        $("#loaderplace").html("");
        $("#saldo-anterior").val(dar_formato_millares(resp_json.data));
    }

    async function totales_cierre(ev) {


        let ejercicio = $("select[name=year]").val();
        if (ev != undefined) ejercicio = ev.target.value;


        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let resource = $("#info-totales").val() + "/" + ejercicio;
        let req = await fetch(resource);
        let resp_json = await req.json();

        $("#loaderplace").html("");
        $("#ANIO-LABEL").text(ejercicio);
        //ocultar boton de cierre
        if (parseInt((new Date()).getFullYear()) == ejercicio)
            $("#BOTON-CERRAR-AREA").removeClass("d-none");
        else
            $("#BOTON-CERRAR-AREA").addClass("d-none");

        let saldo_ini = parseInt(resp_json.saldo_inicial);
        let compras = parseInt(resp_json.compras);
        let ventas = parseInt(resp_json.ventas);
        let retencion = parseInt(resp_json.retencion);
        let s_fisco = ventas;
        let s_contri = compras + retencion;

        let saldo_ = s_contri - s_fisco;


        //saldo anterior
        $("#saldo-inicial").val(dar_formato_millares(saldo_ini));
        //$("#saldo-liquido").val(dar_formato_millares(saldo_));
        $("#saldo").val(dar_formato_millares(saldo_ + saldo_ini));
        $("#t_compras").val(dar_formato_millares(compras));
        $("#t_ventas").val(dar_formato_millares(ventas));
        $("#t_retencion").val(dar_formato_millares(retencion));
        $("#s_fisco").val(dar_formato_millares(s_fisco));
        $("#s_contri").val(dar_formato_millares(s_contri));

        //Anuladas
        /*   let total_fv_cant = resp_json.ventas_anuladas_cant;
        let total_fv_monto = resp_json.ventas_anuladas_tot;
        let total_fv_iva = resp_json.ventas_anuladas_iva;
        $("#total-fv-anuladas").val(dar_formato_millares(total_fv_cant));
        $("#total-fv-monto").val(dar_formato_millares(total_fv_monto));
        $("#total-fv-iva").val(dar_formato_millares(total_fv_iva));
*/
    }




    async function cerrar(ev) {
        ev.preventDefault();
        if (!confirm("Seguro que desea cerrar el año?")) return;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let url___=  ev.currentTarget.href+"/"+$("select[name=year]").val();
        let req = await fetch( url___);
        let resp_json = await req.json();
        $("#loaderplace").html("");
        if ("data" in resp_json)
            alert("Año cerrado");
        else
            alert(resp_json.msj);

    }


    function dar_formato_millares(val_float) {
        console.log(val_float);
        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }




    async function comparativo_meses_del_anio() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#comparativo-anio").html(loader);
        let url__ = "<?= base_url('cierres/comparativo-anio-view-sess') ?>";
        let ElAnio = $("select[name=year]").val();
        let req = await fetch(url__ + "/" + ElAnio);
        let resp_html = await req.text();
        $("#comparativo-anio").html(resp_html);
    }

    async function comparativo_por_ejercicio() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#comparativo-anio-2").html(loader);
        let url__ = "<?= base_url('cierres/comparativo-ejercicios-view') ?>";
       // let ElAnio = $("select[name=year]").val();
        let req = await fetch(url__);
        let resp_html = await req.text();
        $("#comparativo-anio-2").html(resp_html);
    }



    window.onload = function() {
        //saldo_anterior_anio();
       // totales_cierre(); 
        comparativo_por_ejercicio();
    };
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>