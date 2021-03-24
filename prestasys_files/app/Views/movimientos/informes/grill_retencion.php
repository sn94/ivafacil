<?php

use App\Helpers\Utilidades;

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


    <?php if (isset($CLIENTE)) : ?>
        <input type="hidden" name="cliente" value="<?= $CLIENTE ?>">
    <?php endif; ?>

    <input type="hidden" name="month" value="<?= date("m") ?>">
    <input  type="hidden"  name="year" value="<?= date("Y") ?>">



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
            <th class="p-0 text-center">FECHA</th>
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">IMPORTE</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($retencion as $it) : ?>
            <tr>
                <td class="pb-0"><a style="color:black;" onclick="borrar_opera(event, informe_retencion )" href="<?= base_url("retencion/delete/" . $it->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a style="color:black;" href="<?= base_url("retencion/update/" . $it->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"> <?= Utilidades::fecha_f($it->fecha) ?> </td>
                <td class="pb-0"> <?= $it->retencion ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe) ?></td>

            </tr>
        <?php

        endforeach; ?>
    </tbody>

</table>



<?= view("paginadores/paginador") ?>



<?php
//(sizeof($retencion) > 1) ? $retencion_pager->links() : '' 
?>