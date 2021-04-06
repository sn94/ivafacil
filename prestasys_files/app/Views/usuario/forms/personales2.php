<?php 

$telefono =  isset($usuario) ?  $usuario->telefono :  "";
$celular =  isset($usuario) ?  $usuario->celular :  "";

$ciudad =  isset($usuario) ?  $usuario->ciudad :  "";
$tipoplan =  isset($usuario) ?  $usuario->tipoplan :  "";
$rubro =  isset($usuario) ?  $usuario->rubro :  "";


?>
<div class="row form-group">

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Teléfono:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $telefono ?>" oninput="phone_input(event)" maxlength="20" type="text" name="telefono" class=" form-control form-control-label form-control-sm ">
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Celular:</label>
            </div>
            <div class="col-9 col-md-9">
                <input value="<?= $celular ?>" oninput="phone_input(event)" maxlength="20" type="text" name="celular" class=" form-control form-control-label form-control-sm ">
            </div>

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Ciudad:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $ciudad ?>" name="ciudad" class=" form-control form-control-label form-control-sm "></select>
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Elegir plan:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $tipoplan ?>" name="tipoplan" class=" form-control form-control-label form-control-sm "></select>
            </div>
            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Rubro:</label>
            </div>
            <div class="col-9 col-md-9">
                <select valor="<?= $rubro ?>" name="rubro" class=" form-control form-control-label form-control-sm "></select>
            </div>

            <div class="col-3 col-md-3 pl-md-3 pl-0">
                <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
            </div>
            <div class="col-9 col-md-9">
                <input id="masterpass" <?= !isset($OPERACION) ? "" : "disabled" ?> value="" maxlength="80" type="password" name="pass" class=" form-control form-control-label form-control-sm ">
            </div>

            <?php if (isset($OPERACION)  && $OPERACION == "M") : ?>
                <div class="col-12 text-right">
                    <span style="font-size: 11px;"> Editar password <input onclick="editar_pass(event); " type="checkbox" name="" id="switch-pass"></span>
                </div>
            <?php endif; ?>

            <?php if (!isset($OPERACION)) : ?>
                <div class="col-3 col-md-3 pl-md-3 pl-0">
                    <label for="nf-password" class=" form-control-label form-control-sm -label">Repetir contraseña:</label>
                </div>
                <div class="col-9 col-md-9">
                    <input oninput="clave_no_coincide(event)" id="pass2" value="" maxlength="80" type="password" class=" form-control form-control-label form-control-sm ">
                </div>
            <?php endif; ?>


        </div>