 <form action="" method="post" class="container">

     <div class="row form-group">



         <div class="col-12 col-md-6 pl-md-3 pl-0  ">
             <label for="nf-password" class=" form-control-label form-control-sm -label">
                 Saldo inicial:</label>
         </div>
         <div class="col-12 col-md-6 ">
             <input readonly type="text" id="saldo-inicial" class="text-right  form-control form-control-label form-control-sm ">
         </div>


         <div class="col-12 col-md-6 pl-md-3 pl-0">
             <label for="nf-password" class=" form-control-label form-control-sm -label">Total Compras:</label>
         </div>
         <div class="col-12 col-md-6">
             <input readonly type="text" id="t_compras" name="t_compras" class="text-right  form-control form-control-label form-control-sm ">
         </div>

         <div class="col-12 col-md-6 pl-md-3 pl-0">
             <label for="nf-password" class=" form-control-label form-control-sm -label">Total Ventas:</label>
         </div>
         <div class="col-12 col-md-6">
             <input readonly type="text" id="t_ventas" class=" text-right form-control form-control-label form-control-sm ">
         </div>


         <div class="col-12 col-md-6 pl-md-3 pl-0">
             <label for="nf-password" class=" form-control-label form-control-sm -label">Total Retenciones:</label>
         </div>
         <div class="col-12 col-md-6">
             <input readonly type="text" id="t_retencion" name="t_retencion" class="text-right  form-control form-control-label form-control-sm ">
         </div>







         <div class="col-12 col-md-6 pl-md-3 pl-0 bg-dark text-light pt-1">
             <label for="nf-password" class=" form-control-label form-control-sm -label"> Saldo:</label>
         </div>
         <div class="col-12 col-md-6 bg-dark text-light pt-1">
             <input readonly type="text" id="saldo" class=" text-right form-control form-control-label form-control-sm ">
         </div>
         <!--facturas anuladas -->

         <?php

            use App\Models\Parametros_model;

            $Parametro_Mensaje_ = (new Parametros_model())->first();
            $MSJ_PANT_CIERRE_A =  !(is_null($Parametro_Mensaje_)) ? $Parametro_Mensaje_->MSJ_PANT_CIERRE_A :  "";
            if ($MSJ_PANT_CIERRE_A !=  "") :
            ?>
             <div class="col-12">
                 <p style="color: #026804; font-weight: 600;"> <?= $MSJ_PANT_CIERRE_A ?></p>
             </div>

         <?php endif; ?>
     </div>




 </form>
