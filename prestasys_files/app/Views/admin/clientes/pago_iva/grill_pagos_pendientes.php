<?php

use App\Helpers\Utilidades;

$pagos_pendientes = isset($pagos_pendientes)  ? $pagos_pendientes :  [];
?>
<!-- TABLA PAGOS -->



<table class="table table-secondary text-dark">
    <thead>
        <th></th>
       
        <th>PERÍODO</th>
        <th>EJERCICIO</th>
        <th>TOTAL C.F</th>
        <th>TOTAL D.F</th> 
        <th>TOTAL IVA</th>
        <th>TIPO SALDO</th>
    </thead>

    <tbody>

        <?php


        foreach ($pagos_pendientes as $mo) :
            $TotalCf= intval($mo->t_i_compras)+intval($mo->t_retencion);
            $TotalDf= intval($mo->t_i_ventas);
        $ElSaldo= $TotalCf - $TotalDf;
        $TipoSaldo=  $TotalCf > $TotalDf ? "A favor del contribuyente"  :  ( $TotalCf < $TotalDf ? "A favor del fisco"  :  "C.F = D.F" ) ;
        //css
        $estilo=  $ElSaldo > 0 ?  "table-success"  : (  $ElSaldo < 0  ? "table-danger" :  "table-secondary");
        ?>
            <tr class="pb-0 <?=$estilo?>">
                <td class="pb-0"><a onclick="mostrar_form(event)"  href="<?= base_url("admin/clientes/pagos-iva/procesar/" . $mo->regnro) ?>"> Procesar</a> </td>
                <td class="pb-0"><?= Utilidades::monthDescr($mo->mes )?></td>
                <td class="pb-0"><?= $mo->anio ?></td>
                <td class="pb-0"><?=Utilidades::number_f( $TotalCf) ?></td>
                <td class="pb-0"><?=Utilidades::number_f( $TotalDf) ?></td>
                <td class="pb-0"><?=Utilidades::number_f( $ElSaldo) ?></td>
                <td class="pb-0"><?= $TipoSaldo?></td>
                
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<?php if (isset($pager)) :  ?>
    <?= $pager->links() ?>
<?php endif; ?>