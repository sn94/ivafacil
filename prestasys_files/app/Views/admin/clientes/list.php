<?php

use App\Libraries\Mobile_Detect;

$adaptativo = new Mobile_Detect();
?>





<table class="table table-secondary text-dark">
    <thead>
        <th></th>
        <th></th>
        <th>RUC</th>
        <th>CÉDULA</th>
        <th>NOMBRES</th>
        <th>REGISTRADO</th>
        <th>ACTUALIZADO</th>
        <th></th>
    </thead>

    <tbody>

        <?php


        use App\Helpers\Utilidades;

        foreach ($clientes as $mo) : ?>
            <tr class="pb-0">
                <td class="pb-0"><a onclick="borrar(event)" href="<?= base_url("admin/clientes/delete/" . $mo->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a onclick="cargar_form_edit(event)" href="<?= base_url("admin/clientes/update/" . $mo->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"><?= $mo->ruc . "-" . $mo->dv ?></td>

                <td class="pb-0 text-right"><?= $mo->cedula ?></td>
                <td class="pb-0 text-right"><?= $mo->cliente ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->created_at) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->updated_at) ?></td>
                <td>
                <a class="btn btn-dark" href="<?=base_url("admin/clientes/pagos/".$mo->regnro)?>">Pagos</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>



<?= $pager->links() ?>