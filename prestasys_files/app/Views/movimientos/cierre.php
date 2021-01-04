<?php

use App\Helpers\Utilidades;
use App\Models\Parametros_model;

?>
<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("estilos") ?>

<style>
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



<input type="hidden" id="info-totales" value="<?= base_url("cierres/totales") ?>">

<!-- Menu de Usuario -->



<div class="row">

    <div id="loaderplace" class="col-12"></div>
    <div class="col-12 offset-md-3 col-md-6 ">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Cierre del mes: <?= Utilidades::monthDescr(date("m")) ?>/<?= date("Y") ?></h4>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <table class="table table-light">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Exenta</th>
                                <th>5%</th>
                                <th>10%</th>
                                <th>IVA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>
                                    <?php if (isset($edicion_saldo_inicial)  &&  $edicion_saldo_inicial) : ?>
                                        <a href="<?= base_url("usuario/actualizar-saldo") ?>">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>

                                </th>
                                <td>Saldo Inicial</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id="saldo-anterior" class="text-right">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Compras</td>
                                <td id="compras-exenta" class="text-right"></td>
                                <td id="compras-5" class="text-right"></td>
                                <td id="compras-10" class="text-right"></td>
                                <td id="compras-iva" class="text-right"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Ventas</td>
                                <td id="ventas-exenta" class="text-right"></td>
                                <td id="ventas-5" class="text-right"></td>
                                <td id="ventas-10" class="text-right"></td>
                                <td id="ventas-iva" class="text-right"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Retención</td>
                                <td id="retencion-exenta" class="text-right"></td>
                                <td></td>
                                <td></td>
                                <td id="retencion-iva" class="text-right"></td>
                            </tr>

                            <tr id="saldo-row">
                                <td></td>
                                <td>Saldo final</td>
                                <td id="saldo-descri" colspan="3"></td>
                                <td id="saldo" class="text-right"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row form-group">
                        <?php

                        $Parametro_Mensaje_ = (new Parametros_model())->first();
                        $MSJ_PANT_CIERRE_M =  !(is_null($Parametro_Mensaje_)) ? $Parametro_Mensaje_->MSJ_PANT_CIERRE_M :  "";
                        if ($MSJ_PANT_CIERRE_M !=  "") :
                        ?>
                            <div class="col-12">
                                <p style="color: #026804; font-weight: 600;"><?= $MSJ_PANT_CIERRE_M ?> </p>
                            </div>
                        <?php endif; ?>
                    </div>




                </form>
            </div>
            <div class="card-footer">

                <a onclick="cerrar( event)" style="font-size: 10px;font-weight: 600;" href="<?= base_url("cierres/cierre-mes") ?>" class="btn btn-success">
                    <i class="fa fa-dot-circle-o"></i> CERRAR EL MES
                </a>

            </div>
        </div>
    </div>



</div>

<script>
    async function totales_cierre() {

        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch($("#info-totales").val());
        let resp_json = await req.json();
        $("#loaderplace").html("");

        let compras_total_exe = parseInt(resp_json.compras_total_exe);
        let compras_total_10 = parseInt(resp_json.compras_total_10);
        let compras_total_5 = parseInt(resp_json.compras_total_5);
        let compras_iva10 = parseInt(resp_json.compras_iva10);
        let compras_iva5 = parseInt(resp_json.compras_iva5);
        let compras_total_iva = compras_iva10 + compras_iva5;

        let ventas_total_exe = parseInt(resp_json.ventas_total_exe);
        let ventas_total_10 = parseInt(resp_json.ventas_total_10);
        let ventas_total_5 = parseInt(resp_json.ventas_total_5);
        let ventas_iva10 = parseInt(resp_json.ventas_iva10);
        let ventas_iva5 = parseInt(resp_json.ventas_iva5);
        let ventas_total_iva = ventas_iva10 + ventas_iva5;

        let retencion = parseInt(resp_json.retencion);

        let s_fisco = ventas_total_iva;
        let s_contri = compras_total_iva + retencion;

        let saldo_a = parseInt(resp_json.saldo_anterior);

        let saldo = parseInt(resp_json.saldo) + saldo_a;
        let saldo_descri = (s_fisco > s_contri) ? "a favor del fisco " : "a favor del contribuyente";



        //saldo anterior

        $("#saldo-anterior").text(dar_formato_millares(saldo_a));

        $("#compras-exenta").text(dar_formato_millares(compras_total_exe));
        $("#compras-10").text(dar_formato_millares(compras_total_10));
        $("#compras-5").text(dar_formato_millares(compras_total_5));
        $("#compras-iva").text(dar_formato_millares(compras_total_iva));

        $("#ventas-exenta").text(dar_formato_millares(ventas_total_exe));
        $("#ventas-10").text(dar_formato_millares(ventas_total_10));
        $("#ventas-5").text(dar_formato_millares(ventas_total_5));
        $("#ventas-iva").text(dar_formato_millares(ventas_total_iva));


        $("#retencion-exenta").text(dar_formato_millares(retencion));
        $("#retencion-iva").text(dar_formato_millares(retencion));

        $("#saldo").text(dar_formato_millares(saldo));

        $("#saldo-descri").text(saldo_descri);
        $("#saldo-row").removeClass("table-danger");
        $("#saldo-row").removeClass("table-success");

        if (s_fisco < (s_contri+saldo_a)  ) {
            $("#saldo-row").addClass("table-success");
            $("#saldo-descri").css("color", "green");
        } else {
            $("#saldo-row").addClass("table-danger");
            $("#saldo-descri").css("color", "red");
        }

    }




    async function cerrar(ev) {
        ev.preventDefault();
        if (!confirm("Seguro que desea cerrar el mes?")) return;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch(ev.currentTarget.href);
        let resp_json = await req.json();
        $("#loaderplace").html("");
        if ("data" in resp_json)
            alert("Mes cerrado");
        else
            alert(resp_json.msj);

    }


    function dar_formato_millares(val_float) {

        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    window.onload = function() {
        totales_cierre();
    };
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>