<?php

use App\Helpers\Utilidades;
use App\Models\Parametros_model;


//mONTOS
$SALDO_INICIAL=  $totales['saldo_anterior'];
$IMPORTE_COMPRAS_EXE= Utilidades::number_f($totales['compras_total_exe']);
$IMPORTE_COMPRAS_10= Utilidades::number_f($totales['compras_total_10']);
$IMPORTE_COMPRAS_5=  Utilidades::number_f($totales['compras_iva5']); 
$TOTAL_IVA_COMPRAS=  Utilidades::number_f(intval($totales['compras_total_10']) + intval($totales['compras_iva5']));

$IMPORTE_VENTAS_EXE= Utilidades::number_f($totales['ventas_total_exe']);
$IMPORTE_VENTAS_10=  Utilidades::number_f($totales['ventas_total_10']);
$IMPORTE_VENTAS_5=  Utilidades::number_f( $totales['ventas_iva5']); 
$TOTAL_IVA_VENTAS=  Utilidades::number_f(intval($totales['ventas_total_10']) + intval($totales['ventas_iva5']));

$RETENCION=Utilidades::number_f($totales['retencion']);

$SALDO=  Utilidades::number_f($totales['saldo'] );
$SALDO_DESCR= (  intval($totales['saldo']) < 0  ) ? "A Favor del fisco" :  (    intval($totales['saldo']) > 0 ?  "A Favor del contribuyente":  "IVA C.F= IVA D.F");
$SALDO_COLOR= (  intval($totales['saldo']) < 0  ) ? "table-danger" :  (    intval($totales['saldo']) > 0 ?  "table-success":  "table-secondary");



?>
 

<style>
    .card-header>h4:nth-child(1) {

        font-weight: 600;
        font-size: 1.5rem;
        color: #646464;
    }


    .card-header {
        background-color: rgba(0, 0, 0, 0.16);
        border-radius: 15px 15px 0px 0px;
    }
</style>
 

 


<div class="row">

    <div id="loaderplace" class="col-12"></div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Cierre del mes:
                    <!--cargar meses -->
                    <input  value="<?=Utilidades::monthDescr($mes)?>" id="month" type="text"  readonly style="font-size: 16px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #000;"> 

                    <input value="<?=$anio?>" type="text" id="year" readonly style="font-size: 16px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #000;"> 
                </h4>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <table class="table table-light">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Exenta</th>
                                <th>5%</th>
                                <th>10%</th>
                                <th>IVA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>
                                    <?php if (isset($edicion_saldo_inicial)  &&  $edicion_saldo_inicial) : ?>
                                        <a href="<?= base_url("usuario/actualizar-saldo") ?>">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>

                                </th>
                                <td>Saldo Inicial</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id="saldo-anterior" class="text-right">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Compras</td>
                                <td id="compras-exenta" class="text-right"> <?= $IMPORTE_COMPRAS_EXE ?> </td>
                                <td id="compras-5" class="text-right">  <?= $IMPORTE_COMPRAS_5 ?> </td>
                                <td id="compras-10" class="text-right">  <?= $IMPORTE_COMPRAS_10 ?> </td>
                                <td id="compras-iva" class="text-right">  <?= $TOTAL_IVA_COMPRAS ?>  </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Ventas</td>
                                <td id="ventas-exenta" class="text-right">  <?= $IMPORTE_VENTAS_EXE ?> </td>
                                <td id="ventas-5" class="text-right"> <?= $IMPORTE_VENTAS_5 ?> </td>
                                <td id="ventas-10" class="text-right">  <?= $IMPORTE_VENTAS_10 ?> </td>
                                <td id="ventas-iva" class="text-right"> <?= $TOTAL_IVA_VENTAS ?>  </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Retención</td>
                                <td id="retencion-exenta" class="text-right"></td>
                                <td></td>
                                <td></td>
                                <td id="retencion-iva" class="text-right"> <?= $RETENCION ?>   </td>
                            </tr>

                            <tr id="saldo-row"  class="<?=$SALDO_COLOR?>" >
                                <td></td>
                                <td>Saldo final</td>
                                <td id="saldo-descri" colspan="3"> <?= $SALDO_DESCR ?>  </td>
                                <td id="saldo" class="text-right">   <?= $SALDO ?> </td>
                            </tr>
                        </tbody>
                    </table>
                     




                </form>
            </div>
            <div class="card-footer">
 

            </div>
        </div>
    </div>



</div>
 



 