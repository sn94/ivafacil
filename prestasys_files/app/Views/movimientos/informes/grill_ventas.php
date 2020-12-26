<?php

use App\Helpers\Utilidades;

$ventas_total_10 = 0;
$ventas_total_5 = 0;
$ventas_total_ex = 0;
$ventas_t = 0;
?>

<!--   GENERACION DE INFORME  --->


<script>
    function descarga_archivo_ventas(ev) {
        let valor = ev.target.value;
        if (valor == "PDF") $('#ventas-reports').submit();
        if (valor == "EXCEL") {
            let res_xls = "<?= base_url("venta/informes/JSON") ?>";
            callToXlsGen_post_url(res_xls, 'IVA DÉBITO FISCAL', '#ventas-reports');
        }
    }
</script>
<form id="ventas-reports" method="POST" action="<?= base_url("venta/informes/PDF") ?>" target="_blank">
    <!--cargar anios -->
    <select onchange="$('#download-1').val('');informe_ventas();" name="year" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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
    <select onchange="$('#download-1').val('');informe_ventas();" name="month" style="font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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

    <select id="download-1" onchange="descarga_archivo_ventas(event)" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <option value="">Descargar como..</option>
        <option value="PDF"> PDF</option>
        <option value="EXCEL">EXCEL</option>
    </select>

</form>




<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-secondary  ">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">EX</th>
            <th class="p-0 text-right">5%</th>
            <th class="p-0 text-right">10%</th>
            <th class="p-0 text-right">TOTAL</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($ventas as $it) : ?>
            <tr>
                <td class="pb-0"><a  style="color:black;" onclick="borrar_opera(event, informe_ventas)" href="<?= base_url("venta/delete/" . $it->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a  style="color:black;" href="<?= base_url("venta/update/" . $it->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>

                <td class="pb-0"> <?= Utilidades::formato_factura($it->factura) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe3) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe2) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe1) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->total) ?></td>

            </tr>
        <?php
            $ventas_total_10 +=  intval($it->importe1);
            $ventas_total_5 +=  intval($it->importe2);
            $ventas_total_ex +=  intval($it->importe3);
            $ventas_t += intval($it->total);
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="bg-dark text-light">
            <td></td>
            <td></td>
            <td>TOTALES</td>
            <td class="text-right" id="venta-total-ex"> <?= $ventas_total_ex ?> </td>
            <td class="text-right" id="venta-total-5"> <?= $ventas_total_5 ?> </td>
            <td class="text-right" id="venta-total-10"> <?= Utilidades::number_f($ventas_total_10) ?> </td>
            <td class="text-right" id="venta-total"> <?= Utilidades::number_f($ventas_t) ?> </td>
        </tr>

    </tfoot>
</table>
<p style="color:black; font-weight: 600;font-size:11.5px;">Página(s)</p>
<?= (sizeof($ventas) > 1) ? $ventas_pager->links() : '' ?>

<script>
    async function filtrar_ventas() {

    }
</script>