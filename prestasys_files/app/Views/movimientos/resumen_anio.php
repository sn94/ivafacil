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
                <strong>Resumen del año</strong>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <div class="row form-group">
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-email" class=" form-control-label form-control-sm -label">AÑO</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="date" id="nf-email" name="nf-email" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo anterior:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Compra:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Venta:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Retención:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo a pagar al fisco:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo a favor del contribuyente:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" id="nf-password" name="nf-password" class="  form-control form-control-label form-control-sm ">
                        </div>
                        
                        

                    </div>




                </form>
            </div>
            <div class="card-footer">
                
                <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> IR AL MENÚ
                </a>
              
            </div>
        </div>
    </div>

</div>





<?= $this->endSection() ?>