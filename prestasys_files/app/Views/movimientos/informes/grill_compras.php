<?php

use App\Helpers\Utilidades;



$compras_reportes_download_link_PDF =  ($MODO == "ADMIN")  ?  base_url("admin/clientes/compras-informes/PDF") :  base_url("compra/informes/PDF");
$compras_reportes_download_link_JSON =  ($MODO == "ADMIN")  ? base_url("admin/clientes/compras-informes/JSON")  :   base_url("compra/informes/JSON");
?>

<!--   GENERACION DE INFORME  --->


<script>
    function descarga_archivo_compras(ev) {
        let valor = ev.target.value;
        if (valor == "PDF") $('#compras-reports').submit();
        if (valor == "EXCEL") {
            let res_xls = "<?= $compras_reportes_download_link_JSON ?>";
            callToXlsGen_post_url(res_xls, 'IVA CRÉDITO FISCAL', '#compras-reports');
        }
    }
</script>
<form id="compras-reports" method="POST" action="<?= $compras_reportes_download_link_PDF  ?>" target="_blank">
    <!--cargar anios -->



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
            <th class="p-0 text-center"></th>
            <th class="p-0 text-center"></th>
            <th class="p-0 text-center">FECHA</th>
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">EX</th>
            <th class="p-0 text-right">5%</th>
            <th class="p-0 text-right">10%</th>
            <th class="p-0 text-right">IVA</th>
            <th class="p-0 text-right">TOTAL</th>

        </tr>
    </thead>
    <tbody>
        <?php
        if ($TotalRegistros  > 0) :
            foreach ($compras as $it) :

        ?>

                <tr>
                    <td class="pb-0"><a style="color:black;" onclick="borrar_opera(event, informe_compras )" href="<?= base_url("compra/delete/" . $it->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                    <td class="pb-0"> <a style="color:black;" href="<?= base_url("compra/update/" . $it->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>
                    <td class="pb-0"> <?= Utilidades::fecha_f($it->fecha) ?> </td>
                    <td class="pb-0"> <?= ($it->factura) ?> </td>
                    <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe3) ?></td>
                    <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe2) ?> </td>
                    <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe1) ?></td>
                    <td class="pb-0 text-right"> <?= Utilidades::number_f($it->iva1 + $it->iva2) ?> </td>
                    <td class="pb-0 text-right"> <?= Utilidades::number_f($it->total) ?></td>

                </tr>
        <?php
            endforeach;
        endif; ?>
    </tbody>

</table>



<?php

// (sizeof($compras) > 1) ?   $compras_pager->links('', 'paginador_1')  : '' 
?>

<?= view("paginadores/paginador") ?>





<?php

//( $compras_total > 0) ?    $compras_pager->only(['search', 'order'])->makeLinks( 1,10, $compras_total  ,   'paginador_g_compras')  : '' 

//(sizeof($compras) > 1) ? $compras_pager->links() : ''
?>


<script>


</script>