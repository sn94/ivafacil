<table class="table   table-striped" >
    <thead style="font-size: 13.5px;">
        <tr style="font-family: mainfont;" >
            <th colspan="8">Año <?= $ANIO?></th>
        </tr>
        <tr   style="font-family: mainfont;" >
            <th class="text-center p-0">MES</th>
            <th class="text-center p-0">SALDO INICIAL</th>
            <th class="text-center p-0">VENTA</th>
            <th class="text-center p-0">COMPRA</th>
            <th class="text-center p-0">RETENCIÓN</th>
            <th class="text-center p-0">IVA</th>
            <th class="text-center p-0">PAGO</th>
            <th class="text-center p-0">SALDO</th>
        </tr>

    </thead>
    <tbody style="font-size: 11px;">
        <?php

        use App\Helpers\Utilidades;


        //T O T A L E S
        $T_1= 0; $T_2=0; $T_3=0;$T_4= 0; $T_5=0; $T_6=0;$T_7= 0; 

        foreach ($comparativo1 as $cmp) :
            $mes = Utilidades::monthDescr($cmp['mes']);
            $saldo_ini =  Utilidades::number_f($cmp['saldo_inicial']);
            $venta = Utilidades::number_f($cmp['t_impo_ventas']);
            $compra = Utilidades::number_f($cmp['t_impo_compras']);
            $retenci = Utilidades::number_f($cmp['t_impo_retencion']);
            $i_venta = Utilidades::number_f($cmp['t_i_ventas']);
            $i_compra = Utilidades::number_f($cmp['t_i_compras']);
            $i_retencion = Utilidades::number_f($cmp['t_retencion']);
          
            $total_iva = Utilidades::number_f(($cmp['t_i_compras']   + $cmp['t_retencion']) -  $cmp['t_i_ventas']);
            $pago =   Utilidades::number_f($cmp['pago']);
            $saldo =   Utilidades::number_f($cmp['saldo'] +  $cmp['saldo_inicial']);


            //$T_1+= $cmp['saldo_inicial'];
            $T_2+= $cmp['t_impo_ventas'];
            $T_3+= $cmp['t_impo_compras'];
            $T_4+= $cmp['t_impo_retencion'];
            $T_5+= ($cmp['t_i_compras']   + $cmp['t_retencion']) -  $cmp['t_i_ventas'];
            $T_6+= $cmp['pago'];
           // $T_7+= $cmp['saldo'] +  $cmp['saldo_inicial'];
        ?>

            <tr>
                <td class="text-left p-0"><?= $mes ?></td>
                <td class="text-right p-0"><?= $saldo_ini ?></td>
                <td class="text-right p-0"><?= $venta ?></td>
                <td class="text-right p-0" ><?= $compra ?></td>
                <td class="text-right p-0"><?= $retenci ?></td>
                <td class="text-right p-0"><?= $total_iva ?></td>
                <td class="text-right p-0"><?= $pago ?></td>
                <td class="text-right p-0"><?= $saldo ?></td>

            </tr>
        <?php endforeach;

                                   // $T_7 = Utilidades::number_f($T_1 + $T_5);
                                    //formateo
                                  //  $T_1 = Utilidades::number_f($T_1);
                                    $T_2 = Utilidades::number_f($T_2);
                                    $T_3 = Utilidades::number_f($T_3);
                                    $T_4 = Utilidades::number_f($T_4);
                                    $T_5 = Utilidades::number_f($T_5);
                                    $T_6 = Utilidades::number_f($T_6);
       
        ?>

    </tbody>
    <tfoot>
        <tr class="bg-dark text-light">
            <td class="text-left p-0">Totales</td>
            <td class="text-right p-0"> -  </td>
            <td class="text-right p-0"><?= $T_2?></td>
            <td class="text-right p-0"><?= $T_3?></td>
            <td class="text-right p-0"><?= $T_4?></td>
            <td class="text-right p-0"><?= $T_5?></td>
            <td class="text-right p-0"><?= $T_6?></td>
            <td class="text-right p-0">  -</td> 
        </tr>
    </tfoot>
</table>