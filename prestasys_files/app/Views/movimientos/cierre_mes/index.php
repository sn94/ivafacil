<?php

use App\Helpers\Utilidades;
use App\Models\Estado_anio_model;
use App\Models\Parametros_model;


//Lista de anios ya registrados
$ANIOS =  (new Estado_anio_model())->select("anio")
    ->where("codcliente",   $codcliente)->get()->getResult();
?>



<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("estilos") ?>

<style>
    #TABLA-CIERRE-MES tbody tr td {
        padding: 0px;
    }

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




<input type="hidden" id="info-totales-html" value="<?= base_url("cierres/view-cierre-mes") ?>">

<input type="hidden" id="info-totales" value="<?= base_url("cierres/totales_mes") ?>">

<!-- Menu de Usuario -->



<div class="row">


    <div class="col-12 offset-md-2 col-md-8 ">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Cierre del mes:



                    <!--cargar meses -->
                    <select onchange="totales_cierre_html()" id="month" style="font-size: 15px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $nom_mes = Utilidades::monthDescr($m);
                            if (date("m") ==  $m)
                                echo "<option selected value='$m'>$nom_mes</option>";
                            else
                                echo "<option value='$m'>$nom_mes</option>";
                        }
                        ?>
                    </select>
                    <select onchange="totales_cierre_html()" id="year" style="font-size: 15px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
                        <?php
                        $year = date("Y");
                        foreach ($ANIOS as $m) {
                            if (date("Y") ==  $m->anio)
                                echo "<option selected value='$m->anio'>$m->anio</option>";
                            else
                                echo "<option value='$m->anio'>$m->anio</option>";
                        }
                        ?>
                    </select>




                </h4>
            </div>
            <div class="card-body card-block p-0">


                <div class="table-responsive" id="TABLA-CIERRE-MES-AJAX">
                    <?= view("movimientos/cierre_mes/ajax") ?>
                </div>
                <div class="row form-group">
                    <?php

                    $Parametro_Mensaje_ = (new Parametros_model())->first();
                    $MSJ_PANT_CIERRE_M =  !(is_null($Parametro_Mensaje_)) ? $Parametro_Mensaje_->MSJ_PANT_CIERRE_M :  "";
                    if ($MSJ_PANT_CIERRE_M !=  "") :
                    ?>
                        <div class="col-12">
                            <p style="text-align: center;color: #ae0000; font-weight: 600;"><?= $MSJ_PANT_CIERRE_M ?> </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <div id="loaderplace" class="col-12"></div>
          
        </div>
    </div>



</div>

<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }
    async function totales_cierre_html() {
        show_loader();

        //ruta 
        let mes_ = $("#month").val();
        let anio_ = $("#year").val();

        let Route_to = $("#info-totales-html").val() + "/" + mes_ + "/" + anio_;
        let req = await fetch(Route_to, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        let htm = await req.text();
        hide_loader();
        $("#TABLA-CIERRE-MES-AJAX").html(htm);
    }

    async function totales_cierre() {

        show_loader();

        //ruta 
        let mes_ = $("#month").val();
        let anio_ = $("#year").val();

        let Route_to = $("#info-totales").val() + "/" + mes_ + "/" + anio_;
        let req = await fetch(Route_to);
        let resp_json = await req.json();
        hide_loader();

        let compras_total_exe = parseInt(resp_json.compras_total_exe);
        let compras_total_10 = parseInt(resp_json.compras_total_10);
        let compras_total_5 = parseInt(resp_json.compras_total_5);

        let compras_iva10 = parseFloat(resp_json.compras_iva10);
        let compras_iva5 = parseFloat(resp_json.compras_iva5);

        let compras_total_iva = Math.round(compras_iva10 + compras_iva5);

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
        let saldo_descri =
            (s_fisco > s_contri) ? "A FAVOR DE LA SET " :
            ((s_fisco < s_contri) ? "A FAVOR DEL CONTRIBUYENTE" : (s_fisco == 0 ? "-" : "IVA C,F = IVA D.F"));



        //saldo anterior

        $("#saldo-anterior").text(dar_formato_millares(saldo_a));

        $("#compras-exenta").text(dar_formato_millares(compras_total_exe));
        $("#compras-10").text(dar_formato_millares(compras_total_10));
        $("#compras-5").text(dar_formato_millares(compras_total_5));
        $("#compras-tot").text(dar_formato_millares(compras_total_10 + compras_total_5));
        $("#compras-iva").text(dar_formato_millares(compras_total_iva));

        $("#ventas-exenta").text(dar_formato_millares(ventas_total_exe));
        $("#ventas-10").text(dar_formato_millares(ventas_total_10));
        $("#ventas-5").text(dar_formato_millares(ventas_total_5));
        $("#ventas-tot").text(dar_formato_millares(ventas_total_10 + ventas_total_5));
        $("#ventas-iva").text(dar_formato_millares(ventas_total_iva));


        //  $("#retencion-exenta").text(dar_formato_millares(retencion));
        $("#retencion-exenta").text("");
        $("#retencion-tot").text(dar_formato_millares(retencion));
        $("#retencion-iva").text(dar_formato_millares(retencion));

        $("#saldo").text(dar_formato_millares(saldo));

        $("#saldo-descri").text(saldo_descri);
        $("#saldo-row").removeClass("table-danger");
        $("#saldo-row").removeClass("table-success");

        if (s_fisco < (s_contri)) {
            $("#saldo-row").addClass("table-success");
            $("#saldo-descri").css("color", "green");
        } else {
            if (s_fisco > (s_contri)) {
                $("#saldo-row").addClass("table-danger");
                $("#saldo-descri").css("color", "red");
            }
        }

    }




    async function cerrar(ev) {
        ev.preventDefault();
        if (!confirm("Seguro que desea cerrar el mes?")) return;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        //PREPARAR RUTA
        //CONCAT MES Y ANIO
        let Mes_ = $("#month").val();
        let Anio_ = $("#year").val();
        let Route_to = ev.currentTarget.href + "/" + Mes_ + "/" + Anio_;
        let req = await fetch(Route_to);
        let resp_json = await req.json();
        $("#loaderplace").html("");
        if ("data" in resp_json) {
            alert("Mes cerrado exitosamente");
            window.location.reload();
        } else
            alert(resp_json.msj);

    }


    function dar_formato_millares(val_float) {

        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    window.onload = function() {
        //   totales_cierre();
    };
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>