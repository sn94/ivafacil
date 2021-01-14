<table class="table   table-striped" style="font-size: 11px;">
    <thead>

        <tr>
            <th>AÑO</th>
            <th>SALDO INICIAL</th>
            <th>VENTA</th>
            <th>COMPRA</th>
            <th>RET.</th>
            <th>IVA</th>
            <th>PAGO</th>
            <th>SALDO</th>
        </tr>

    </thead>
    <tbody>
        <?php

        use App\Helpers\Utilidades;

        $T_1= 0; $T_2=0; $T_3=0;$T_4= 0; $T_5=0;

        foreach ($comparativo2 as $cmp) :
            $anio =  $cmp['anio'];
            $saldo_ini =  Utilidades::number_f($cmp['saldo_inicial']);
            $venta = Utilidades::number_f($cmp['importe_ventas']);
            $compra = Utilidades::number_f($cmp['importe_compras']);
            $retenci = Utilidades::number_f($cmp['importe_retenc']);
            $i_venta = Utilidades::number_f($cmp['ventas']);
            $i_compra = Utilidades::number_f($cmp['compras']);
            $i_retencion = Utilidades::number_f($cmp['retencion']);
            $total_iva = Utilidades::number_f( (  $cmp['compras']  + $cmp['retencion']) -  $cmp['ventas']);
            $pago=  Utilidades::number_f(  $cmp['pago']);
            $saldo =   Utilidades::number_f($cmp['saldo'] + $cmp['saldo_inicial']);

            $T_1 += $cmp['importe_ventas'];
            $T_2+= $cmp['importe_compras'];
            $T_3+= $cmp['importe_retenc'];
            $T_4+= (  $cmp['compras']  + $cmp['retencion']) -  $cmp['ventas'];
            $T_5+=   $cmp['pago'];
        ?>

            <tr>
                <td class="text-right"><?= $anio ?></td>
                <td class="text-right"><?= $saldo_ini ?></td>
                <td class="text-right"><?= $venta ?></td>
                <td class="text-right"><?= $compra ?></td>
                <td class="text-right"><?= $retenci ?></td>
                <td class="text-right"><?= $total_iva ?></td> 
                <td class="text-right"><?= $pago ?></td>
                <td class="text-right"><?= $saldo ?></td>

            </tr>
        <?php endforeach;
         $T_1 = Utilidades::number_f($T_1);
         $T_2 = Utilidades::number_f($T_2);
         $T_3 = Utilidades::number_f($T_3);
         $T_4 = Utilidades::number_f($T_4);
         $T_5 = Utilidades::number_f($T_5);
        ?>

    </tbody>
    <tfoot>
    <tr class="bg-dark text-light"><td colspan="2">Totales</td><td class="text-right"><?=$T_1?></td><td class="text-right"><?=$T_2?></td> 
    <td class="text-right"><?=$T_3?></td><td class="text-right"><?=$T_4?></td><td class="text-right"><?=$T_5?></td>
    <td class="text-right">-</td>
    </tr>
    </tfoot>
</table>