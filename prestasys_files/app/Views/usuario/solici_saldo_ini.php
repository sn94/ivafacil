<?php

use App\Helpers\Utilidades;
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

    /* style.css | http://localhost/ivafacil/assets/template/assets/css/style.css */


    /* bootstrap.min.css | http://localhost/ivafacil/assets/template/vendors/bootstrap/dist/css/bootstrap.min.css */

    .card-header {
        background-color: rgba(0, 0, 0, 0.16);
        border-radius: 15px 15px 0px 0px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>

<?php 


$SALDO=  isset( $saldo )?  Utilidades::number_f(   $saldo  ) :  0;

?>

<input type="hidden" id="info-totales" value="<?= base_url("usuario/totales") ?>">

<!-- Menu de Usuario -->



<div class="row">

    <div id="loaderplace" class="col-12"></div>
    <div class="col-12 offset-md-3 col-md-6 ">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Actualizar saldo para el <?= date("Y") ?></h4>
            </div>
            <div class="card-body card-block p-0">
                <form onsubmit="cerrar( event)" action="<?= base_url("usuario/actualizar-saldo") ?>" method="get" class="container">

                    <p style="color:black; font-weight: 600;">Antes de cerrar este período, puede proporcionar el saldo inicial para el ejercicio <?= date("Y") ?>. O simplemente puede omitir este paso (en este caso el saldo inicial se considerará como cero)</p>
                    <div class="row form-group">
                        <div class="col-12 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo inicial:</label>
                        </div>
                        <div class="col-12 col-md-5">
                            <input value="<?=$SALDO?>"   onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';"   oninput="formatear_entero(event)" id="SALDO" style="border:none; border-bottom: 1px solid #092301 !important;" type="text" class=" text-right form-control form-control-label form-control-sm ">
                        </div>
                    </div>
                     <button type="submit"   style="font-size: 10px;font-weight: 600;"  class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> GUARDAR
                     </button>
                <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("cierres/v-cierre-mes") ?>" class="btn btn-warning btn-sm">
                    <i class="fa fa-dot-circle-o"></i> OMITIR
                </a>
                </form>
            </div>
            <div class="card-footer">

               

                
            </div>
        </div>
    </div>



</div>

<script>
    async function cerrar(ev) {
        ev.preventDefault();
        let saldo = $("#SALDO").val();

        if (saldo == "") {
            alert("Proporcione el monto de saldo inicial");
            return;
        }
        if (!confirm("continuar?")) return;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);

        saldo = limpiar_numero(saldo);

        let req = await fetch(ev.currentTarget.action + "/" + saldo);
        let resp_json = await req.json();
        $("#loaderplace").html("");
        if ("data" in resp_json) {
            alert(resp_json.data);
            window.location = "<?= base_url("cierres/view-cierre-mes") ?>";
        } else
            alert(resp_json.msj);
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


    function dar_formato_millares(val_float) {

        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>