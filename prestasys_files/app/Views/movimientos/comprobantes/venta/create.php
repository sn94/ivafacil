<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<style>
    h4.text-center {
        /* color: #272727; */
        color: #646464;
        font-weight: 600;
    }



    .offset-md-3>div:nth-child(1)>div:nth-child(1) {
        background-color: #d1d1d1;
    }

    .wrong-factura {
        border: 2px solid #ed2328;
        background-color: #ff9595;
    }
</style>
<!-- Menu de Usuario -->

<!-- VISTA IVA -->

<div class="row mt-3">


    <div class="col-12" id="loaderplace">
    </div>
    <div class="col-12  offset-md-3 col-md-6 ">
        <div class="container p-1 p-md-1">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <h4 class="text-center">Factura de venta</h4>
            </div>

            <form onsubmit="guardar_factura( event )" action="<?= base_url("venta/create") ?>" method="post" class="pt-2 bg-light" style="border: 1px solid #cecece;border-radius: 0px 0px 15px 15px ;">

                <?= view("movimientos/comprobantes/venta/form") ?>
            </form>
        </div>
    </div>
    <!--end second col -->

</div>



 
<?= $this->endSection() ?>