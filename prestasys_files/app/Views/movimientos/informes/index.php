<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<input type="hidden" id="info-compras" value="<?= base_url("compra/index/N") ?>">
<input type="hidden" id="info-ventas" value="<?= base_url("venta/index/N") ?>">
<input type="hidden" id="info-retencion" value="<?= base_url("retencion/index/N") ?>">

<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 col-md-4">

        <h5>VENTAS</h5>
        <div id="tabla-ventas">
            <table style="font-size: 11px;" class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="p-0">N° COMP.</th>
                        <th class="p-0">EX</th>
                        <th class="p-0">5%</th>
                        <th class="p-0">10%</th>
                        <th class="p-0">TOTAL</th>
                        <th class="p-0">MOD/ELIM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">TOTAL VTA. XXXXXXX</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-12 col-md-4">

        <h5>COMPRA</h5>
        <div id="tabla-compras">
            <table style="font-size: 11px;" class="table table-bordered  ">
                <thead>
                    <tr>
                        <th class="p-0">N° COMP.</th>
                        <th class="p-0">EX</th>
                        <th class="p-0">5%</th>
                        <th class="p-0">10%</th>
                        <th class="p-0">TOTAL</th>
                        <th class="p-0">MOD/ELIM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">TOTAL CPRA. XXXXXXX</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-12 col-md-4">

        <h5>RETENCIONES</h5>
      <div id="tabla-retencion">
      <table style="font-size: 11px;" class="table table-bordered ">
            <thead>
                <tr>
                    <th class="p-0">N° COMP.</th>
                    <th class="p-0">IMPORTE</th>
                    <th class="p-0">MOD/ELIM</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">TOTAL RET. XXXXXXX</td>
                </tr>
            </tfoot>
        </table>
      </div>
    </div>

    <div class="col-12">
        <dl class="row">
            <dt class="col-12 col-md-3">SALDO A FAVOR DEL CONTRIBUYENTE </dt>
            <dd class="col-12 col-md-9">xxxx</dd>
            <dt class="col-12 col-md-3">SALDO A FAVOR DEL FISCO </dt>
            <dd class="col-12 col-md-9">xxxxx</dd>
        </dl>


    </div>

    <div class="col-12">
        <a href="#" class="btn btn-success mt-3 ">ACTUALIZAR</a>
        <a href="<?= base_url("/") ?>" class="btn btn-success mt-3 ">IR A MENÚ</a>
    </div>


</div>

<script>
    async function informe_compras() {
        //Obtener el resumen de compras
        let loader= "<img  src='<?=base_url("assets/img/loader.gif")?>'   />";
        $("#tabla-compras").html( loader);
        let req = await fetch($("#info-compras").val());
        let resp_html = await req.text();
        $("#tabla-compras").html(  resp_html );

    }

    async function informe_ventas() {
        //Obtener el resumen de ventas
        let loader= "<img  src='<?=base_url("assets/img/loader.gif")?>'   />";
        $("#tabla-ventas").html( loader);
        let req = await fetch($("#info-ventas").val());
        let resp_html = await req.text();
        $("#tabla-ventas").html(  resp_html );

    }
    async function informe_retencion() {
        //Obtener el resumen retencion
        let loader= "<img  src='<?=base_url("assets/img/loader.gif")?>'   />";
        $("#tabla-retencion").html( loader);
        let req = await fetch($("#info-retencion").val());
        let resp_html = await req.text();
        $("#tabla-retencion").html(  resp_html );

    }
    window.onload= function(){
      
        informe_ventas();
        informe_compras();
        informe_retencion();
    };
</script>





<?= $this->endSection() ?>