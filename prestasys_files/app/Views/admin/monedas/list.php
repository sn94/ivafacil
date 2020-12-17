<?php

use App\Libraries\Mobile_Detect;

$adaptativo= new Mobile_Detect();
?>

 



<table class="table table-secondary text-dark">
    <thead>
        <th></th>
        <th></th>
        <th>DIVISA</th>
        <th>CÓDIGO</th>
        <th>CAMBIO ACTUAL</th>
        <th>CREADO</th>
        <th>ACTUALIZADO</th>
    </thead>

    <tbody>

        <?php

        use App\Helpers\Utilidades;

        foreach ($monedas as $mo) : ?>
            <tr class="pb-0">
                <td class="pb-0"><a onclick="borrar(event)" href="<?=base_url("admin/monedas/delete/".$mo->regnro)?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a onclick="cargar_form_edit(event)" href="<?=base_url("admin/monedas/update/".$mo->regnro)?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"><?= $mo->moneda ?></td>
                <td class="pb-0"><?= $mo->prefijo ?></td>
                <td class="pb-0 text-right"><?= Utilidades::number_f($mo->tcambio) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->created_at) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->updated_at) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>


  
<?=  $pager->links()?>
 
 

<script>
   
   window.onload= function(){
    $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
   };

</script>