<!--   GENERACION DE INFORME  --->


<script>
    function descargar_archivo_informe(ev) {
     
        ev.preventDefault();
        var ID_DE_CLIENTE = $("input[name=cliente]").val();
        var JSON_PAGOS_URL = "<?= base_url("admin/clientes/informes/JSON") ?>/" + ID_DE_CLIENTE;
        var PDF_PAGOS_URL = "<?= base_url("admin/clientes/informes/PDF") ?>/" + ID_DE_CLIENTE;
        document.getElementById('pagos-reports').action = PDF_PAGOS_URL;
        let valor = ev.target.value;
        if (valor == "PDF") {
            $('#pagos-reports').submit();
        }
        if (valor == "EXCEL") {
            let res_xls = JSON_PAGOS_URL;
            callToXlsGen_post_url(res_xls, 'DETALLE DE PAGOS', '#pagos-reports');
        }
    }
</script>
<form id="pagos-reports" method="POST" action="" target="_blank">
    <!--cargar anios -->
    <select onchange="$('#download-2').val('');actualizar_grilla();" name="year" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <?php

        use App\Helpers\Utilidades;

        for ($m = 2019; $m <= date("Y"); $m++) {
            if ($year ==  $m)
                echo "<option selected value='$m'>$m</option>";
            else
                echo "<option value='$m'>$m</option>";
        }
        ?>
    </select>

    <!--cargar meses -->
    <select onchange="$('#download-2').val('');actualizar_grilla();" name="month" style="font-size: 11px; border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <?php
        for ($m = 1; $m <= 12; $m++) {
            $nom_mes = Utilidades::monthDescr($m);
            if ($month ==  $m)
                echo "<option selected value='$m'>$nom_mes</option>";
            else
                echo "<option value='$m'>$nom_mes</option>";
        }
        ?>
    </select>

    <button type="submit" style="display: none;"></button>

    <select id="download-2" onchange="descargar_archivo_informe(event)" style="font-size: 11px;border-radius: 15px;border: 0.5px solid #9f9f9f;color: #555;">
        <option value="">Descargar como..</option>
        <option value="PDF"> PDF</option>
        <option value="EXCEL">EXCEL</option>
    </select>




</form>
<!--End generador de informes -->