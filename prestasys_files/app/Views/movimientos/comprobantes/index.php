<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 offset-md-4 col-md-4">
    <a style="display: block;"  href="<?= base_url("compra/create/N") ?>"  class="btn btn-success mt-3 ">Factura de compra</a>
    <a  style="display: block;"  href="<?= base_url("venta/create/N") ?>"  class="btn btn-success mt-3 ">Factura de venta</a>
    
    <a  style="display: block;" href="<?= base_url("retencion/create/N") ?>"  class="btn btn-success mt-3 ">Retención</a>
    
       
    </div>

</div>

<script>

function marcado(){
    let rs= document.querySelectorAll("input[name=radios]");
    let selecc= "";
    Array.prototype.forEach.call(  rs,  function( ar){
        if(  $( ar ).prop("checked")){
            selecc=  $(ar).val(); 
        }
    });
    return selecc;
}
     
</script>

 



<?= $this->endSection() ?>