<?php

use App\Helpers\Utilidades;



$TIMBRADO =  $CLIENTE->timbrado == "" ?  "****" :    $CLIENTE->timbrado;


$VENTAS_10 = $ventas_total_10;
$VENTAS_BASE_10 = round($ventas_total_10 / 1.1);
$VENTAS_IMP_10 = round($ventas_total_10 / 11);
$VENTAS_5 = $ventas_total_5;
$VENTAS_BASE_5 = round($ventas_total_5 / 1.05);
$VENTAS_IMP_5 = round($ventas_total_5 / 21);
$VENTAS_EXE = $ventas_total_exe;


$COMPRAS_10 = $compras_total_10;
$COMPRAS_BASE_10 = round($compras_total_10 / 1.1);
$COMPRAS_IMP_10 = round($compras_total_10 / 11);
$COMPRAS_5 = $compras_total_5;
$COMPRAS_BASE_5 = round($compras_total_5 / 1.05);
$COMPRAS_IMP_5 = round($compras_total_5 / 21);
$COMPRAS_EXE = $compras_total_exe;


$RETENCION =  $retencion;

$SALDO_REAL = $saldo + $saldo_anterior;
?>




<style>
    .text-right {
        text-align: right;
    }
</style>


El cliente con RUC°: <?= $CLIENTE->ruc . "-" . $CLIENTE->dv ?> 
ha cerrado el mes de <?= Utilidades::monthDescr( $MES) ?>.

<h4 style="text-decoration: underline;">Sumas y Saldos</h4>

<table style="border: 1px solid black;">

    <thead>
        <tr style="background-color: green;color: white;">
            <th>%IVA</th>
            <th>TOTAL</th>
            <th>BASE IMP.</th>
            <th>IMPUESTO</th>
        </tr>
    </thead>
    <tr style="background-color: green;color: white;">
            <td colspan="3">Saldo inicial:</td>
            <td class="text-right"> <?= Utilidades::number_f($saldo_anterior) ?></td>
        </tr>
    <tbody>
        <tr style="background-color: green;color: white;">
            <td colspan="4">VENTAS</td>
        </tr>
        <tr>
            <td>10%</td>
            <td class="text-right"><?= Utilidades::number_f($VENTAS_10) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($VENTAS_BASE_10) ?></td>
            <td class="text-right"><?= Utilidades::number_f($VENTAS_IMP_10) ?></td>
        </tr>
        <tr>
            <td>5%</td>
            <td class="text-right"><?= Utilidades::number_f($VENTAS_5) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($VENTAS_BASE_5) ?></td>
            <td class="text-right"><?= Utilidades::number_f($VENTAS_IMP_5) ?></td>
        </tr>
        <tr>
            <td>EXENTA</td>
            <td class="text-right"><?= Utilidades::number_f($VENTAS_EXE) ?></td>
            <td> -</td>
            <td>-</td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td class="text-right"> <?= Utilidades::number_f($VENTAS_10 +  $VENTAS_5 + $VENTAS_EXE) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($VENTAS_BASE_10 +  $VENTAS_BASE_5) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($VENTAS_IMP_10 +  $VENTAS_IMP_5) ?></td>
        </tr>

        <tr style="background-color: green;color: white;">
            <td colspan="4">COMPRAS</td>
        </tr>
        <tr>
            <td>10%</td>
            <td class="text-right"><?= Utilidades::number_f($COMPRAS_10) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($COMPRAS_BASE_10) ?></td>
            <td class="text-right"><?= Utilidades::number_f($COMPRAS_IMP_10) ?></td>
        </tr>
        <tr>
            <td>5%</td>
            <td class="text-right"><?= Utilidades::number_f($COMPRAS_5) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($COMPRAS_BASE_5) ?></td>
            <td class="text-right"><?= Utilidades::number_f($COMPRAS_IMP_5) ?></td>
        </tr>
        <tr>
            <td>EXENTA</td>
            <td class="text-right"><?= Utilidades::number_f($COMPRAS_EXE) ?></td>
            <td> -</td>
            <td>-</td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td class="text-right"> <?= Utilidades::number_f($COMPRAS_10 +  $COMPRAS_5 + $COMPRAS_EXE) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($COMPRAS_BASE_10 +  $COMPRAS_BASE_5) ?></td>
            <td class="text-right"> <?= Utilidades::number_f($COMPRAS_IMP_10 +  $COMPRAS_IMP_5) ?></td>
        </tr>

        <tr style="background-color: green;color: white;">
            <td colspan="4">RETENCIÓN</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right"><?= Utilidades::number_f($RETENCION) ?></td>
            <td> -</td>
            <td>-</td>
        </tr>


        

        <tr>
            <?php if ($SALDO_REAL < 0) :    ?>
                <td colspan="3" style="color: red; font-weight:bold;">A FAVOR DE LA SET:</td>
            <?php else : ?>
                <td colspan="3" style="color: green; font-weight:bold;">A FAVOR DEL CONTRIBUYENTE:</td>
            <?php endif; ?>
            <td class="text-right"> <?= Utilidades::number_f($SALDO_REAL) ?> </td>
        </tr>

    </tbody>
</table>

<h5>Facturas anuladas</h5>
<table>

    <tr>
        <th>N° Factura</th>
        <th>Timbrado N°</th>
    </tr>



    <?php

    foreach ($ANULADAS as $anu) :
        echo "<tr><td>$anu->factura</td> <td>$anu->timbrado</td></tr>";
    endforeach;
    ?>



</table>