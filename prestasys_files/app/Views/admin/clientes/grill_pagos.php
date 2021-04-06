<?php

use App\Helpers\Utilidades;

$pagos = isset($pagos)  ? $pagos :  [];
?>
<!-- TABLA PAGOS -->



<table class="table table-secondary text-dark">
    <thead>
        <th></th>
        <th></th>
        <th>COMPROBANTE</th>
        <th>PLAN</th>
        <th>PAGO</th>
        <th>REGISTRADO</th>
    </thead>

    <tbody>

        <?php


        foreach ($pagos as $mo) : ?>
            <tr class="pb-0">
                <td class="pb-0"><a onclick="borrar(event)" href="<?= base_url("admin/monedas/delete/" . $mo->regnro) ?>"> <i class="fa fa-trash"></i></a> </td>
                <td class="pb-0"> <a onclick="cargar_form_edit(event)" href="<?= base_url("admin/monedas/update/" . $mo->regnro) ?>"><i class="fa fa-pencil"></i></a> </td>
                <td class="pb-0"><?= $mo->comprobante ?></td>
                <td class="pb-0"><?= $mo->plan ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->fecha) ?></td>
                <td class="pb-0"><?= Utilidades::fecha_f($mo->created_at) ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<?php if (isset($pager)) :  ?>
    <?= $pager->links() ?>
<?php endif; ?>