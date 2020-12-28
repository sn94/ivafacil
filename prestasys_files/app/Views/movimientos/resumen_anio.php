<?php

use App\Helpers\Utilidades;
?>
<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("estilos") ?>

<style>
 

/* Elemento | http://localhost/ivafacil/usuario/view-cierre-mes */

.card-header > h4:nth-child(1) {
 
  font-weight: 600;
  font-size: 1.5rem;
  color: #646464; 
}

 
.card-header { 
  background-color: rgba(0, 0, 0, 0.16);
  border-radius: 15px 15px 0px 0px;
}



</style>
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<input type="hidden" id="info-totales" value="<?= base_url("cierres/totales-anio") ?>"> 

<!-- Menu de Usuario -->



<div class="row">

      <div id="loaderplace"  class="col-12"></div>
    <div class="col-12 offset-md-3 col-md-6 ">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Resumen del año: <?=date("Y") ?></h4>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <div class="row form-group">
                        
                         
                       
                       
                        <div class="col-12 col-md-6 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Total IVA Compra:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input readonly type="text" id="t_compras" name="t_compras" class="text-right  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-12 col-md-6 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Total en Retención:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input readonly type="text" id="t_retencion" name="t_retencion" class="text-right  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-12 col-md-6 pl-md-3 pl-0">
                            <label style="color: blue; font-weight: 600;" for="nf-password" class=" form-control-label form-control-sm -label">Saldo a favor del contribuyente:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input readonly type="text" id="s_contri" name="s_contri" class=" text-right form-control form-control-label form-control-sm ">
                        </div>


                        <div class="col-12 col-md-6 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Total IVA Venta:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input  readonly type="text" id="t_ventas"   class=" text-right form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-12 col-md-6 pl-md-3 pl-0">
                            <label style="color: red; font-weight: 600;"  for="nf-password" class=" form-control-label form-control-sm -label">Saldo a pagar al fisco:</label>
                        </div>
                        <div class="col-12 col-md-6">
                            <input readonly type="text" id="s_fisco" name="s_fisco" class="text-right  form-control form-control-label form-control-sm ">
                        </div>
                      
                        <div class="col-12 col-md-6 pl-md-3 pl-0 bg-dark text-light ">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo en el año:</label>
                        </div>
                        <div class="col-12 col-md-6 pt-1 bg-dark text-light">
                            <input  readonly type="text" id="saldo-liquido"    class=" text-right form-control form-control-label form-control-sm ">
                        </div>
                        
                        <div class="col-12 col-md-6 pl-md-3 pl-0 bg-dark text-light">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">
                                (+)Saldo inicial:</label>
                        </div>
                        <div class="col-12 col-md-6 bg-dark text-light">
                            <input readonly type="text" id="saldo-inicial"   class="text-right  form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-12 col-md-6 pl-md-3 pl-0 bg-dark text-light">
                            <label for="nf-password" class=" form-control-label form-control-sm -label"> (=) Saldo:</label>
                        </div>
                        <div class="col-12 col-md-6 bg-dark text-light">
                            <input  readonly type="text" id="saldo"    class=" text-right form-control form-control-label form-control-sm ">
                        </div>


                        <div class="col-12">
                           <p  style="color: #026804; font-weight: 600;">El costo del servicio es de Gs. 60.000 mensuales, pagos por mes adelantado, se abona en cualquier ventanilla de pagos de servicios </p>
                        </div>

                    </div>




                </form>
            </div>
            <div class="card-footer">
                 
                <a onclick="cerrar( event)" style="font-size: 10px;font-weight: 600;" href="<?= base_url("cierres/cierre-anio") ?>" class="btn btn-success">
                    <i class="fa fa-dot-circle-o"></i> CERRAR EL AÑO
                </a>
              
            </div>
        </div>
    </div>



</div>

<script>
    

 
 async   function saldo_anterior_anio(){
    let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch(  "<?=base_url("cierres/saldo-anterior-anio")?>"  );
        let resp_json = await req.json();
        $("#loaderplace").html("");
        $("#saldo-anterior").val(  dar_formato_millares( resp_json.data )  );
 }

    async function totales_cierre() {
        
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch($("#info-totales").val());
        let resp_json = await req.json();
        $("#loaderplace").html("");
        let saldo_ini=  parseInt(   resp_json.saldo_inicial);
         let compras=  parseInt(   resp_json.compras);
         let ventas=  parseInt(   resp_json.ventas);
         let retencion=  parseInt(   resp_json.retencion);
         let s_fisco=   ventas;
         let s_contri=  compras + retencion;

         let saldo_=  s_contri -  s_fisco; 

         //saldo anterior
         $("#saldo-inicial").val(  dar_formato_millares( saldo_ini));
         $("#saldo-liquido").val(  dar_formato_millares( saldo_));
         $("#saldo").val(  dar_formato_millares(  saldo_ + saldo_ini));
        $("#t_compras").val(  dar_formato_millares( compras));
        $("#t_ventas").val(  dar_formato_millares( ventas));
        $("#t_retencion").val(  dar_formato_millares( retencion));
        $("#s_fisco").val(  dar_formato_millares( s_fisco));
        $("#s_contri").val(  dar_formato_millares( s_contri));

    }
    



    async function cerrar( ev) {
        ev.preventDefault();
        if( ! confirm("Seguro que desea cerrar el año?") )   return;
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch(   ev.currentTarget.href  );
        let resp_json = await req.json();
        $("#loaderplace").html("");
        if(  "data"  in  resp_json)
        alert( "Año cerrado");
         else
         alert(  resp_json.msj );

    }
    

    function dar_formato_millares(val_float) {
        console.log(  val_float);
        return new Intl.NumberFormat("de-DE").format(val_float);
    }


    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }

 

 window.onload= function(){
//saldo_anterior_anio();
 totales_cierre();
 };
</script>





<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script src="<?= base_url("assets/xls_gen/xls.js") ?>"></script>
<script src="<?= base_url("assets/xls_gen/xls_ini.js") ?>"></script>


<?= $this->endSection() ?>