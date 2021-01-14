<?php

use App\Libraries\Mobile_Detect;

$adaptativo = new Mobile_Detect();
?>


<div id="loaderplace">

</div>


<table class="table table-secondary text-dark">
    <thead>
        <tr style="font-family: mainfont;">
            <th class="pb-0"></th>

            <th class="pb-0">RUC</th>
            <th class="pb-0">CÉDULA</th>
            <th class="pb-0">NOMBRES</th>
            <th class="pb-0">REGISTRADO</th>
            <th class="pb-0">ACTUALIZADO</th>
            <th class="pb-0">ESTADO DE PAGO</th>
            <th class="pb-0">NOVEDADES</th>

        </tr>
    </thead>

    <tbody>

        <?php


        use App\Helpers\Utilidades;

        foreach ($clientes as $mo) :

        ?>
            <tr class="<?= $mo->vencido == "1" ?  "pb-0 table-danger"   :  "pb-0"  ?>">

                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="<?= base_url("admin/clientes/update/" . $mo->regnro) ?>"> Editar</a>
                            <a class="dropdown-item" href="<?= base_url("admin/clientes/pagos/" . $mo->regnro) ?>">Gestionar Pagos</a>
                            <a class="dropdown-item" href="<?= base_url("admin/clientes/pagos-iva/" . $mo->regnro) ?>">Gestionar Pagos del IVA</a>
                            <a class="dropdown-item" href="<?= base_url("admin/clientes/movimientos/" . $mo->regnro) ?>">Detalles</a>
                            <a class="dropdown-item" onclick="borrar(event)" href="<?= base_url("admin/clientes/delete/" . $mo->regnro) ?>"> Eliminar </a>

                        </div>
                    </div>
                </td>

                <td class="pb-0"><?= $mo->ruc . "-" . $mo->dv ?></td>

                <td class="pb-0 text-right"><?= $mo->cedula ?></td>
                <td class="pb-0 text-right"><?= $mo->cliente ?></td>
                <td class="pb-0"> <?= Utilidades::fecha_f($mo->created_at) ?> </td>
                <td class="pb-0"> <?= Utilidades::fecha_f($mo->updated_at) ?> </td>


                <td class="pb-0">
                    <?php if ($mo->vencido == "1") : ?>

                        <a onclick="recordar_pago( event);" class="btn btn-danger btn-sm" href="<?= base_url("admin/recordar-pago/" . $mo->regnro) ?>">
                            Recordatorio de pago
                        </a>
                    <?php else : ?>
                        Al día
                    <?php endif; ?>

                </td>

                <td class="pb-0">
                    <?php if ($mo->novedad_c_mes != "1"  &&  $mo->novedad_c_anio != "1") :  echo 'Sin novedades';
                    else :  ?>
                        <div class="dropdown">
                            <button class="btn btn-danger btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Cierre de mes
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <?php if ($mo->novedad_c_mes == "1") : ?>
                                    <a class="dropdown-item" onclick="descargar_estado_cierre(event)" href="<?= base_url("admin/cierre-mes/$mo->regnro/$mo->c_mes/$mo->c_anio") ?>"> Exportar<i class="fa fa-download" aria-hidden="true"></i> </a>
                                    <a class="dropdown-item" onclick="reapertura_mes(event)" href="<?= base_url("admin/deshacer-cierre-mes/$mo->regnro/$mo->c_mes/$mo->c_anio") ?>"> Abrir<i class="fa fa-eye" aria-hidden="true"></i> </a>
                                <?php else : ?>
                                    Sin novedad
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endif;  ?>
                </td>

            </tr>
        <?php endforeach; ?>

    </tbody>
</table>



<?= isset($pager) ?   $pager->links() :  ""  ?>

<script>
    async function recordar_pago(ev) {
        ev.preventDefault();
        let res_ = ev.currentTarget.href;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);

        let req = await fetch(res_);
        let resp = await req.text();
        $("#loaderplace").html("");
        alert("Enviado!");
    }


    async function descargar_estado_cierre(ev) {
        ev.preventDefault();
        let res_xls = ev.currentTarget.href;
        let req = await fetch(res_xls);
        let resp = await req.json();
        callToXlsGen_with_data(resp.title, resp.data);
    }


    async function reapertura_mes(ev) {
        ev.preventDefault();
        if (!confirm("Reabrir período?")) return;
        let url__ = ev.currentTarget.href;
        let req = await fetch(url__);
        let resp = await req.json();
        if ("data" in resp) {
            alert(resp.data);
            if ("actualizar_grilla" in window)
                actualizar_grilla();
        } else alert(resp.msj);
    }
</script>