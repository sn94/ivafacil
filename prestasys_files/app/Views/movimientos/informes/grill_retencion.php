<?php

use App\Helpers\Utilidades;

 
$retencion_t= 0;
?>

<table style="font-size: 12.5px;font-weight: 600 !important;" class="table table-bordered table-striped table-secondary ">
    <thead>
        <tr>
            <th class="p-0 text-center">N° COMP.</th> 
            <th class="p-0 text-right">IMPORTE</th>
             
        </tr>
    </thead>
    <tbody>
        <?php foreach ($retencion as $it) : ?>
            <tr>
                <td class="pb-0"> <?=   $it->retencion?> </td>
                <td class="pb-0 text-right"> <?= Utilidades::number_f($it->importe) ?></td> 
                 
            </tr>
        <?php
           
            $retencion_t+= intval($it->importe);
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>TOTALES</td> 
            <td  class="text-right" id="retencion-total">  <?=Utilidades::number_f(  $retencion_t )?>  </td>    
        </tr>
        
    </tfoot>
</table>

<?php echo sizeof($retencion) == 0 ? "SIN REGISTROS" : "" ;?>
    <?= (sizeof($retencion) > 1) ? $retencion_pager->links() : '' ?>
 