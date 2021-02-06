<?php

use App\Helpers\Utilidades;

$pagos_pendientes = isset($pagos_pendientes)  ? $pagos_pendientes :  [];
?>
<!-- TABLA PAGOS -->


<button onclick="actualizar_grilla_pagos_realizados()" class="btn btn-success">IR A PAGOS REALIZADOS</button>
<table class="table table-secondary text-dark">
    <thead>
        <tr style="font-family: mainfont;">
            <th class="pb-0"></th>
            <th class="pb-0">PERÍODO</th>
            <th class="pb-0">EJERCICIO</th>
            <th class="pb-0">TOTAL C.F</th>
            <th class="pb-0">TOTAL D.F</th>
            <th class="pb-0">TOTAL IVA</th>
            <th class="pb-0">TIPO SALDO</th>
        </tr>
    </thead>

    <tbody>

        <?php


        foreach ($pagos_pendientes as $mo) :
            $TotalCf = intval($mo->t_i_compras) + intval($mo->t_retencion);
            $TotalDf = intval($mo->t_i_ventas);
            $ElSaldo = $TotalCf - $TotalDf;
            $TipoSaldo =  $TotalCf > $TotalDf ? "A favor del contribuyente"  : ($TotalCf < $TotalDf ? "A FAVOR DE LA SET"  :  "C.F = D.F");
            //css
            $estilo =  $ElSaldo > 0 ?  "table-success"  : ($ElSaldo < 0  ? "table-danger" :  "table-secondary");
        ?>
            <tr class="pb-0 <?= $estilo ?>">
                <td class="pb-0">
                <a class="btn btn-danger btn-sm" onclick="mostrar_form(event)" href="<?= base_url("pagos-iva/create/" . $mo->regnro) ?>"> Registrar pago</a>
                 </td>
                <td class="pb-0"><?= Utilidades::monthDescr($mo->mes) ?></td>
                <td class="pb-0"><?= $mo->anio ?></td>
                <td class="pb-0"><?= Utilidades::number_f($TotalCf) ?></td>
                <td class="pb-0"><?= Utilidades::number_f($TotalDf) ?></td>
                <td class="pb-0"><?= Utilidades::number_f($ElSaldo) ?></td>
                <td class="pb-0"><?= $TipoSaldo ?></td>

            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<?php if (isset($pager)) :  ?>
    <?= $pager->links() ?>
<?php endif; ?>