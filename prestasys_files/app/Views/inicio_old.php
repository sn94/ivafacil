<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 offset-md-4 col-md-4">

        <div class="form-check">
            <div class="radio">
                <label for="radio1" class="form-check-label ">
                    <input type="radio" id="radio1" name="radios" value="<?=base_url("movimiento/index")?>" class="form-check-input">Registrar comprobantes
                </label>
            </div>
            <div class="radio">
                <label for="radio2" class="form-check-label ">
                    <input type="radio" id="radio2" name="radios"  value="<?=base_url("movimiento/informe_mes")?>"  class="form-check-input">Movimientos del mes
                </label>
            </div>
            <div class="radio">
                <label for="radio3" class="form-check-label ">
                    <input type="radio" id="radio3" name="radios"  value="<?=base_url("movimiento/r_cierre")?>" class="form-check-input">Cierre del mes
                </label>
            </div>
            <div class="radio">
                <label for="radio3" class="form-check-label ">
                    <input type="radio" id="radio3" name="radios" value="<?=base_url("movimiento/resumen_anio")?>" class="form-check-input">Resumen del año
                </label>
            </div>
        </div>
        <h5 class="mt-1" style="color: red; font-weight: 600;">PARA HACER EL CIERRE DEL MES, DEBE ESTAR AL DÍA CON EL PAGO DEL SERVICIO</h5>


        <a onclick="llevar( event) " href="#" class="btn btn-success mt-3 ">SIGUIENTE</a>
      
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

    function llevar(ev){
        ev.preventDefault();
        window.location= marcado();
    }
</script>

 

<?= $this->endSection() ?>



