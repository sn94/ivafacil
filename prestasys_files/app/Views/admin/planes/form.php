<?php

use App\Helpers\Utilidades;

$regnro = isset($planes) ? $planes->regnro : '';
$descr = isset($planes) ? $planes->descr : '';
$precio = isset($planes) ? Utilidades::number_f($planes->precio) : '0';
$dias = isset($planes) ? $planes->dias : ''; 
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="text-center">Planes</h4>
        </div>
        <div class="card-body card-block p-3">
            <div class="row form-group">

                <div class="col-12">
                    <div class="row form-group">

                        <?php if (isset($OPERACION)  &&  $OPERACION == "M") : ?>
                            <input type="hidden" name="regnro" value="<?= $regnro?>">
                        <?php endif; ?>

                        <div class="col-12 col-md-4 pl-md-3 pl-0">
                            <label for="nf-password">Descripción:</label>
                        </div>
                        <div class="col-12 col-md-8 pl-0">
                            <input value="<?= $descr ?>" type="text" maxlength="100" name="descr" class=" form-control form-control-label form-control-sm ">

                        </div>
                        <div class="col-12 col-md-4 pl-md-3 pl-0">
                            <label for="nf-password">Precio:</label>
                        </div>
                        <div class="col-12 col-md-8 pl-0 ">
                            <input onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';"   oninput="formatear_entero( event)"  value="<?= $precio ?>" maxlength="14" type="text" name="precio" class=" form-control form-control-sm ">

                        </div>
                        <div class="col-12 col-md-4 pl-md-3 pl-0">
                            <label for="nf-password">Días de validez:</label>
                        </div>
                        <div class="col-12 col-md-8 pl-0 ">
                            <input onfocus="if(this.value=='0') this.value='';" onblur="if(this.value=='') this.value='0';"   oninput="formatear_entero( event)"  value="<?= $dias ?>" maxlength="3" type="text" name="dias" class=" form-control form-control-sm ">

                        </div>

                    </div>
                </div>
            </div>
            <!--end row form  -->


        </div>
        <div class="card-footer">
            <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-dot-circle-o"></i> GUARDAR
            </button>
        </div>

    </div>
</div>