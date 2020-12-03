<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 offset-md-3 col-md-6 ">
        <div class="card">
            <div class="card-header">
                <strong>Registro de factura de compra</strong>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <div class="row form-group">
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-email" class=" form-control-label form-control-sm -label">Fecha:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="date" id="nf-email" name="nf-email" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">N° de factura:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Importe:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">10%:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">5%:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Exenta:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3  pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">TOTAL:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-12">
                            <h6 class="mt-1 text-center" style="color: red; font-weight: 600;">VERIFICAR LOS DATOS REGISTRADOS</h6>
                        </div>

                    </div>




                </form>
            </div>
            <div class="card-footer">
                <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> IR AL MENÚ
                </a>
                <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("movimiento/index") ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> REGISTRAR OTROS COMPROBANTES
                </a>
                <button style="font-size: 10px;font-weight: 600;" type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> SEGUIR REGISTRANDO
                </button>
            </div>
        </div>
    </div>

</div>





<?= $this->endSection() ?>