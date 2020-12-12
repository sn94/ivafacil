<?php

use App\Helpers\Utilidades;

$compras_total_10 = 0;
$compras_total_5 = 0;
$compras_total_ex = 0;
$compras_t= 0;
?>

<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-dark ">
    <thead>
        <tr>
            <th class="p-0 text-center">N° COMP.</th>
            <th class="p-0 text-right">EX</th>
            <th class="p-0 text-right">5%</th>
            <th class="p-0 text-right">10%</th>
            <th class="p-0 text-right">TOTAL</th>
             
        </tr>
    </thead>
    <tbody>
        <?php foreach ($compras as $it) : ?>
            <tr>
                <td class="pb-0"> <?=Utilidades::formato_factura(  $it->factura  )?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe3) ?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe2) ?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe1 )?></td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->total) ?></td>
                 
            </tr>
        <?php
            $compras_total_10 +=  intval($it->importe1);
            $compras_total_5 +=  intval($it->importe2);
            $compras_total_ex +=  intval($it->importe3);
            $compras_t+= intval($it->total);
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>TOTALES</td>
            <td  class="text-right" > <?= $compras_total_ex?> </td>
            <td   class="text-right"> <?= $compras_total_5?> </td> 
            <td  class="text-right" >  <?=Utilidades::number_f(  $compras_total_10 )?>  </td>  
            <td  class="text-right" >  <?=Utilidades::number_f(  $compras_t )?>  </td>  
        </tr>
        
    </tfoot>
</table>
 
 
    <?php echo sizeof($compras) == 0 ? "SIN REGISTROS" : "" ;?>
    <?= (sizeof($compras) > 1) ? $compras_pager->links() : '' ?>