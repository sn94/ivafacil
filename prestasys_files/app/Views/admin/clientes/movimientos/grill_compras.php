<?php

use App\Helpers\Utilidades;

$compras_total_10 = 0;
$compras_total_5 = 0;
$compras_total_ex = 0;
$compra_total_iva= 0;
$compras_t = 0;
?>

<!--   GENERACION DE INFORME  --->


<script>
    function descarga_archivo_compras(ev) {
        let valor = ev.target.value;
        if (valor == "PDF") $('#compras-reports').submit();
        if (valor == "EXCEL") {
            let res_xls = "<?= base_url("admin/clientes/compras-informes/JSON") ?>";
            callToXlsGen_post_url(res_xls, 'IVA CRÉDITO FISCAL', '#compras-reports');
        }
    }
</script>
<form id="compras-reports" method="POST" action="<?= base_url("admin/clientes/compras-informes/PDF") ?>" target="_blank">


    <!--cargar anios -->
    <input type="hidden" name="cliente"  value="<?=$CLIENTE?>">
    <select onchange="$('#download-2').val('');informe_compras();" name="year" style="display:none;font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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
    <select onchange="$('#download-2').val('');informe_compras();" name="month" style="display:none;font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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

    <select id="download-2" onchange="descarga_archivo_compras(event)" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <option value="">Descargar como..</option>
        <option value="PDF"> PDF</option>
        <option value="EXCEL">EXCEL</option>
    </select>




</form>
<!--End generador de informes -->




<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-secondary ">
    <thead>
        <tr>
           
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">EX</th>
            <th class="p-0 text-right">5%</th>
            <th class="p-0 text-right">10%</th>
            <th class="p-0 text-right">IVA</th>
            <th class="p-0 text-right">TOTAL</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($compras as $it) : ?>
            <tr>
               
                <td class="pb-0"> <?= ($it->factura) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe3) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe2) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe1) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->iva1+ $it->iva2) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->total) ?></td>

            </tr>
        <?php
            $compras_total_10 +=  intval($it->importe1);
            $compras_total_5 +=  intval($it->importe2);
            $compras_total_ex +=  intval($it->importe3);
            $compra_total_iva+=  $it->iva1+ $it->iva2;
            $compras_t += intval($it->total);
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="bg-dark text-light">
    
            <td>TOTALES</td>
            <td class="text-right" id="compra-total-ex"> <?= $compras_total_ex ?> </td>
            <td class="text-right" id="compra-total-5"> <?= $compras_total_5 ?> </td>
            <td class="text-right" id="compra-total-10"> <?= Utilidades::number_f($compras_total_10) ?> </td>
            <td class="text-right" id="compra-total-iva"> <?= Utilidades::number_f($compra_total_iva) ?> </td>
            <td class="text-right" id="compra-total"> <?= Utilidades::number_f($compras_t) ?> </td>
        </tr>

    </tfoot>
</table>
<p style="color:black; font-weight: 600;font-size:11.5px;">Página(s)</p>
<?= (sizeof($compras) > 1) ? $compras_pager->links() : '' ?>



 