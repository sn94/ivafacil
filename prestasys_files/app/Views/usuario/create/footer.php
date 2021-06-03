<?php

use App\Models\Parametros_model;

?>
<div class="row">
    <div class="col-12  col-md-6 mb-2">
        <input type="checkbox" name="aceptar-bases" value="S"> He leído y acepto las
        <a style="font-weight: 600; color: var(--color-primario) !important;" href="<?= base_url("TyC.pdf") ?>">bases y condiciones</a>
    </div>
    <div class="col-12  col-md-6  mb-2 ">
        <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
            <i class="fa fa-dot-circle-o"></i> REGISTRARME
        </button>
    </div>


    <?php
    $MSJ_PANT_REGISTRO = "";
    try{
        $Parametro_Mensaje_ = (new Parametros_model())->first();
    $MSJ_PANT_REGISTRO =  !(is_null($Parametro_Mensaje_)) ? $Parametro_Mensaje_->MSJ_PANT_REGISTRO :  "";
    }catch( Exception  $e){
    echo view("plantillas/message",  ['error'=>  $e->getMessage()]) ;
    }
    if ($MSJ_PANT_REGISTRO !=  "") :
    ?>
        <div class="col-12 col-md-12">

            <div class="alert alert-warning pb-1 pt-1 mb-1">
                <p class="mt-0 pt-0" style=" font-weight: 600;">
                    <?= $MSJ_PANT_REGISTRO ?>
                </p>
            </div>

        </div>
    <?php endif; ?>
</div>