<?php

use App\Helpers\Utilidades;

 
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
   
 
    
  
    <select id="download-1" onchange="descarga_archivo_ventas(event)" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <option value="">Descargar como..</option>
        <option value="PDF"> PDF</option>
        <option value="EXCEL">EXCEL</option>
    </select>

    <button type="submit" style="display: none;"></button>

</form>




<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-secondary  ">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th  class="p-0 text-center">FECHA</th>
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
        if(  $TotalRegistros  > 0):
        foreach ($ventas as $it) : ?>
            <tr>
                <td class="pb-0"><a  style="color:black;" onclick="borrar_opera(event, informe_ventas)" href="<?= base_url("venta/delete/" . $it->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a  style="color:black;" href="<?= base_url("venta/update/" . $it->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"> <?= Utilidades::fecha_f($it->fecha) ?> </td>
                <td class="pb-0"> <?=($it->factura) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe3) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe2) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe1) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->iva1+ $it->iva2 ) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->total) ?></td>

            </tr>
        <?php
         
        endforeach;
    endif;
     ?>
    </tbody>
     
</table>
 
<?php 
// ( $ventas_total > 0) ?    $ventas_pager->makeLinks( 1,10, $ventas_total  ,   'paginador_g_ventas')  : '' ?>

<?=view( "paginadores/paginador")?>

 
 