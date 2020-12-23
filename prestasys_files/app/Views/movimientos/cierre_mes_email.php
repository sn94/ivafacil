<?php

use App\Helpers\Utilidades;
?>


El cliente con RUC°: <?=$cliente?> ha cerrado el mes de <?= Utilidades::monthDescr(  date("m"))?>. 
<h4 style="text-decoration: underline;">Sumas y Saldos</h4>
<table>

<tr> <td>Compras</td> <td><?=Utilidades::number_f($compras)?></td> </tr>
<tr> <td>Ventas</td> <td><?=Utilidades::number_f($ventas)?></td> </tr>
<tr> <td>Retención</td> <td><?=Utilidades::number_f($retencion)?></td> </tr>
<tr> <td>Saldo a favor del contribuyente</td> <td><?=Utilidades::number_f($s_contri)?></td> </tr>
<tr> <td>(-)Saldo a favor del fisco</td> <td> <?=Utilidades::number_f($s_fisco)?> </td> </tr>
<tr> <td>Saldo en este mes:</td> <td> <?=Utilidades::number_f($saldo)?> </td> </tr>
<tr> <td>Mas Saldo anterior (o inicial)</td> <td><?= Utilidades::number_f($saldo_anterior) ?> </td> </tr>
</table>
 
