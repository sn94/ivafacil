<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 col-md-4">

    <h5>VENTAS</h5>
    <table style="font-size: 11px;" class="table table-bordered ">
        <thead> <tr><th  class="p-0">N° COMP.</th><th  class="p-0">EX</th><th  class="p-0">5%</th><th  class="p-0">10%</th><th  class="p-0">TOTAL</th> <th  class="p-0">MOD/ELIM</th></tr></thead>
        <tbody>
            <tr><td></td><td></td><td></td> <td></td><td></td><td></td></tr>
        </tbody>
        <tfoot>
            <tr><td colspan="6">TOTAL VTA. XXXXXXX</td></tr>
        </tfoot>
    </table>
    </div>

    <div class="col-12 col-md-4">

<h5>COMPRA</h5>
<table style="font-size: 11px;" class="table table-bordered  ">
    <thead> <tr><th  class="p-0">N° COMP.</th><th  class="p-0">EX</th><th  class="p-0">5%</th><th  class="p-0">10%</th><th  class="p-0">TOTAL</th> <th  class="p-0">MOD/ELIM</th></tr></thead>
    <tbody>
        <tr><td></td><td></td><td></td> <td></td><td></td><td></td></tr>
    </tbody>
    <tfoot>
        <tr><td colspan="6">TOTAL CPRA. XXXXXXX</td></tr>
    </tfoot>
</table>
</div>

<div class="col-12 col-md-4">

<h5>RETENCIONES</h5>
<table style="font-size: 11px;" class="table table-bordered ">
    <thead> <tr><th  class="p-0">N° COMP.</th><th  class="p-0">IMPORTE</th>   <th  class="p-0">MOD/ELIM</th></tr></thead>
    <tbody>
        <tr><td></td>  <td></td > <td></td>  </tr>
    </tbody>
    <tfoot>
        <tr><td colspan="6">TOTAL RET. XXXXXXX</td></tr>
    </tfoot>
</table>
</div>

<div class="col-12">
    <dl class="row">
        <dt class="col-12 col-md-3">SALDO A FAVOR DEL CONTRIBUYENTE </dt><dd  class="col-12 col-md-9">xxxx</dd>
        <dt  class="col-12 col-md-3">SALDO A FAVOR DEL FISCO </dt><dd  class="col-12 col-md-9">xxxxx</dd>
    </dl>


</div>

<div class="col-12">
<a  href="#" class="btn btn-success mt-3 ">ACTUALIZAR</a>
<a  href="<?=base_url("/")?>" class="btn btn-success mt-3 ">IR A MENÚ</a>
</div>


</div>

<script>

 
    
</script>

 



<?= $this->endSection() ?>