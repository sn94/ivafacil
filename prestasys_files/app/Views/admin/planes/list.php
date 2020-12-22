<?php

use App\Libraries\Mobile_Detect;

$adaptativo= new Mobile_Detect();
?>

 



<table class="table table-secondary text-dark">
    <thead>
        <th></th>
        <th></th>
        <th>DESCRIPCIÓN</th>
        <th>PRECIO</th> 
        <th>VÁLIDEZ</th>
        <th>CREADO</th>
        <th>ACTUALIZADO</th>
    </thead>

    <tbody>

        <?php

        use App\Helpers\Utilidades;

        foreach ($planes as $mo) : ?>
            <tr class="pb-0">
                <td class="pb-0"><a onclick="borrar(event)" href="<?=base_url("admin/planes/delete/".$mo->regnro)?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a onclick="cargar_form_edit(event)" href="<?=base_url("admin/planes/update/".$mo->regnro)?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"><?= $mo->descr ?></td>
                <td class="pb-0"><?= Utilidades::number_f(  $mo->precio ) ?></td> 
                <td class="pb-0"><?=  $mo->dias. " dias." ?></td> 
                <td class="pb-0"><?= Utilidades::fecha_f($mo->created_at) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->updated_at) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>


  
<?=  $pager->links()?>
 
 
 