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



.offset-md-3 > div:nth-child(1) > div:nth-child(1) {
  background-color: #d1d1d1;
}

 
.p-0 {
  border: 1px solid #c8c0c0;
  border-radius: 15px 15px 0px 0px;
}

</style>




<div class="row mt-3">


    <div class="col-12" id="loaderplace">
    </div>
    

    <div class="col-12  offset-md-3 col-md-6 ">
        <div class="container p-0 bg-light">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <h4  class="text-center">Retención</h4>
            </div>

            <form action="<?= base_url("retencion/update") ?>" method="post" class="container" onsubmit="guardar(event)">


            <?= view("movimientos/comprobantes/retencion/form")?>


            </form>
        </div>
    </div>
    <!--end second col -->

</div>




 

<?= $this->endSection() ?>