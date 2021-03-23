<?php

use App\Helpers\Utilidades;
?>


El cliente con RUC°: <?=$cliente?> ha cerrado el año <?=date("Y")?> 
<h4 style="text-decoration: underline;">Sumas y Saldos</h4>
<table>
<tr> <td>Saldo anterior</td> <td><?= Utilidades::number_f( $saldo_anterior)?></td> </tr>
<tr> <td>Compras</td> <td><?= Utilidades::number_f($compras) ?></td> </tr>
<tr> <td>Ventas</td> <td><?= Utilidades::number_f($ventas) ?></td> </tr>
<tr> <td>Retención</td> <td><?= Utilidades::number_f($retencion) ?></td> </tr>
<tr> <td>Saldo a favor del contribuyente</td> <td><?= Utilidades::number_f($s_contri) ?></td> </tr>
<tr> <td>(-)SALDO A FAVOR DE LA SET</td> <td> <?= Utilidades::number_f($s_fisco) ?> </td> </tr>
<tr> <td>Saldo:</td> <td> <?= Utilidades::number_f($saldo)  ?> </td> </tr>
</table>
 
