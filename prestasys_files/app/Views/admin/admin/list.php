<?php

use App\Libraries\Mobile_Detect;

$adaptativo= new Mobile_Detect();
?>

 



<table class="table table-secondary text-dark">
    <thead>
        <th></th>
        <th></th>
        <th>Nick</th>
        <th>Creación</th>
        <th>Últ. actualización</th> 
    </thead>

    <tbody>

        <?php

        use App\Helpers\Utilidades;

        foreach ($adminis as $mo) : ?>
            <tr class="pb-0">
                <td class="pb-0"><a onclick="borrar(event)" href="<?=base_url("admin/delete/".$mo->regnro)?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a onclick="cargar_form_edit(event)" href="<?=base_url("admin/update/".$mo->regnro)?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"><?= $mo->nick ?></td> 
                <td class="pb-0"><?= Utilidades::fecha_f($mo->created_at) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->updated_at) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>


  
<?=  $pager->links()?>
 
 
 