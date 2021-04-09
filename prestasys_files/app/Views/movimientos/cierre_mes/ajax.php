<?php

use App\Helpers\Utilidades;

$compraExenta = isset($compras_total_exe) ?  $compras_total_exe :  0;
$compraI10 = isset($compras_total_10) ?  $compras_total_10 :  0;;
$compraI5 = isset($compras_total_5) ?  $compras_total_5 :  0;;
$compraTotal =  isset($compras_total_10) ?  ($compras_total_10 + $compras_total_5)  :  0;;
$compraTotalIva = isset($compras_total_iva) ?  $compras_total_iva :  0;;

$ventaExenta = isset($ventas_total_exe) ?  $ventas_total_exe :  0;;
$ventaI10 = isset($ventas_total_10) ?  $ventas_total_10 :  0;;
$ventaI5 = isset($ventas_total_5) ?  $ventas_total_5 :  0;;
$ventaTotal =  isset($ventas_total_10) ?  ($ventas_total_10 + $ventas_total_5) :  0;
$ventaTotalIva = isset($ventas_total_iva) ?  $ventas_total_iva :  0;

$retencionTotal = isset($retencion) ?  $retencion :  0;;

$saldo_anterior_verificado = isset($saldo) && isset($saldo_anterior) ?  ($saldo_anterior<0 ? 0 : $saldo_anterior) : 0;

$saldoMonto = isset($saldo) && isset($saldo_anterior) ? ($saldo + $saldo_anterior_verificado ) :  0;

//Determinar descripcion del saldo
$s_fisco = $ventaTotalIva;
$s_contri = $compraTotalIva + $retencionTotal;
$saldo_contri_fisco =  ($s_contri - $s_fisco) + $saldo_anterior_verificado;

//contextualizar colores para presentar saldo
//#saldo-row  [table-success | table-danger]
//#saldo-descri [red |  green]
$saldoDescripcion =
    ($saldo_contri_fisco < 0) ? "A FAVOR DE LA SET " : ($saldo_contri_fisco > 0 ? "A FAVOR DEL CONTRIBUYENTE"
        : "SALDO CERO");

$saldoDescripcionColor =   ($saldo_contri_fisco < 0) ? " style='color: red;' " : (($saldo_contri_fisco > 0) ? " style='color: green;' " : (" style='color: black;' "));

$saldoDescripcionFondo =   ($saldo_contri_fisco < 0) ? " class='table-danger' " : (
    ($saldo_contri_fisco > 0) ? " class='table-success' " : " class='table-secondary' ");





//Formatear numericos

$compraExenta =  Utilidades::number_f($compraExenta);
$compraI10 =  Utilidades::number_f($compraI10);
$compraI5 =  Utilidades::number_f($compraI5);
$compraTotal =  Utilidades::number_f($compraTotal);
$compraTotalIva =  Utilidades::number_f($compraTotalIva);

$ventaExenta =  Utilidades::number_f($ventaExenta);
$ventaI10 =  Utilidades::number_f($ventaI10);
$ventaI5 =  Utilidades::number_f($ventaI5);
$ventaTotal =  Utilidades::number_f($ventaTotal);
$ventaTotalIva =  Utilidades::number_f($ventaTotalIva);

$retencionTotal =  Utilidades::number_f($retencionTotal);
$saldoAnterior =  Utilidades::number_f($saldo_anterior_verificado);
$saldoMonto =  Utilidades::number_f($saldoMonto);


?>



<?php if (isset($error)    &&   $error !=  "") : ?>
    <?= view("plantillas/message") ?>
<?php endif; ?>




<table class="table table-light" id="TABLA-CIERRE-MES">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th class="text-center">Exenta</th>
            <th class="text-center">5%</th>
            <th class="text-center">10%</th>
            <th class="text-center">TOTALES</th>
            <th class="text-center">IVA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php if (isset($edicion_saldo_inicial)  &&  $edicion_saldo_inicial) : ?>
                    <a class="p-0 m-0" href="<?= base_url("usuario/actualizar-saldo") ?>">
                        <i class="fa fa-refresh m-0" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>

            </td>
            <td>Saldo Inicial</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="saldo-anterior" class="text-right">
                <?= $saldoAnterior ?>
            </td>
        </tr>

        <!--EDITAR DESDE AQUI -->
        <tr>
            <td></td>
            <td>Compras</td>
            <td id="compras-exenta" class="text-right"><?= $compraExenta ?></td>
            <td id="compras-5" class="text-right"><?= $compraI5 ?></td>
            <td id="compras-10" class="text-right"><?= $compraI10 ?></td>
            <td id="compras-tot" class="text-right"><?= $compraTotal ?></td>
            <td id="compras-iva" class="text-right"><?= $compraTotalIva ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Ventas</td>
            <td id="ventas-exenta" class="text-right"><?= $ventaExenta ?></td>
            <td id="ventas-5" class="text-right"><?= $ventaI5 ?></td>
            <td id="ventas-10" class="text-right"><?= $ventaI10 ?></td>
            <td id="ventas-tot" class="text-right"><?= $ventaTotal ?></td>
            <td id="ventas-iva" class="text-right"><?= $ventaTotalIva ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Retención</td>
            <td id="retencion-exenta" class="text-right"></td>
            <td></td>
            <td></td>
            <td id="retencion-tot" class="text-right"><?= $retencionTotal ?></td>
            <td id="retencion-iva" class="text-right"><?= $retencionTotal ?></td>
        </tr>

        <tr id="saldo-row" <?= $saldoDescripcionFondo ?>>
            <td></td>
            <td>Saldo final</td>
            <td <?= $saldoDescripcionColor ?> id="saldo-descri" colspan="4">
                <?= $saldoDescripcion ?>
            </td>
            <td id="saldo" class="text-right"> <?= $saldoMonto ?></td>
        </tr>
    </tbody>
</table>

<?=view("movimientos/cierre_mes/boton_cierre")?>