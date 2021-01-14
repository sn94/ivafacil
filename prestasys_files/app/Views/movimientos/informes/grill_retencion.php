<?php

use App\Helpers\Utilidades;

$retencion_t = 0;
?>



<!--   GENERACION DE INFORME  --->


<script>
    function descarga_archivo_retencion(ev) {
        let valor = ev.target.value;
        if (valor == "PDF") $('#retencion-reports').submit();
        if (valor == "EXCEL") {
            let res_xls = "<?= base_url("retencion/informes/JSON") ?>";
            callToXlsGen_post_url(res_xls, 'RETENCIONES', '#retencion-reports');
        }
    }
</script>
<form id="retencion-reports" method="POST" action="<?= base_url("retencion/informes/PDF") ?>" target="_blank">
    <!--cargar anios -->
    <select onchange="$('#download-3').val('');informe_retencion()" name="year" style="display: none;font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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
    <select onchange="$('#download-3').val('');informe_retencion();" name="month" style="display: none;font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
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

    <select id="download-3" onchange="descarga_archivo_retencion(event)" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <option value="">Descargar como..</option>
        <option value="PDF"> PDF</option>
        <option value="EXCEL">EXCEL</option>
    </select>

</form>



<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-secondary ">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">IMPORTE</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($retencion as $it) : ?>
            <tr>
                <td class="pb-0"><a style="color:black;" onclick="borrar_opera(event, informe_retencion )" href="<?= base_url("retencion/delete/" . $it->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a style="color:black;"  href="<?= base_url("retencion/update/" . $it->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>

                <td class="pb-0"> <?= $it->retencion ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe) ?></td>

            </tr>
        <?php

            $retencion_t += intval($it->importe);
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="bg-dark text-light">
            <td></td>
            <td></td>
            <td>TOTALES</td>
            <td class="text-right" id="retencion-total"> <?= Utilidades::number_f($retencion_t) ?> </td>
        </tr>

    </tfoot>
</table>

<p style="color:black; font-weight: 600;font-size:11.5px;">Página(s)</p>
<?= (sizeof($retencion) > 1) ? $retencion_pager->links() : '' ?>