<?php


$regnro = isset($administrador) ?  $administrador->regnro : "";
$nick = isset($administrador) ?  $administrador->nick : "";
$email = isset($administrador) ?  $administrador->email : "";
?>


<?php if (isset($OPERACION)  && $OPERACION == "M") { ?>
<input type="hidden" name="regnro"  value="<?=$regnro?>">
<?php } ?>

<div class="col-3 col-md-3 pl-md-3 pl-0">
    <label for="nf-password" class=" form-control-label form-control-sm -label">Nick:</label>
</div>
<div class="col-9 col-md-9">
    <input oninput="control_campo_vacio( event)" value="<?= $nick ?>" type="text" maxlength="20" name="nick" class=" form-control form-control-label form-control-sm ">
    <p id="nick" style="font-size: 11px; color:red; font-weight: 600;"></p>
</div>
<div class="col-3 col-md-3 pl-md-3 pl-0">
    <label for="nf-password" class=" form-control-label form-control-sm -label">Email:</label>
</div>
<div class="col-9 col-md-9">
    <input oninput="control_campo_vacio(event)" value="<?= $email ?>" maxlength="120" type="text" name="email" class=" form-control form-control-label form-control-sm ">
    <p id="email" style="font-size: 11px; color:red; font-weight: 600;"></p>
</div>


<div class="col-3 col-md-3 pl-md-3 pl-0">
    <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
</div>
<div class="col-9 col-md-9">
    <input id="masterpass" <?= !isset($OPERACION) ? "" : "disabled" ?> oninput="control_campo_vacio( event)" value="" maxlength="80" type="password" name="pass" class=" form-control form-control-label form-control-sm ">
    <p id="pass" style="font-size: 11px; color:red; font-weight: 600;"></p>
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


 <!--Eleccion de fondo de pantalla -->
 <?php if (isset($administrador)) : ?>
        <div class="col-12">
            <label style="font-weight: 600;" for="nf-password" class=" form-control-label form-control-sm -label">Fondo de pantalla:</label>

            <div class="card-group">


                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="" alt="NINGUNO">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="none">
                    </div>
                </div>
                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo0.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo0.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo1.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo1.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo2.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>

                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo2.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo3.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo3.jpg") ?>">
                    </div>
                </div>

                <div class="card" style="width: 18rem;">

                    <img class="card-img-top" src="<?= base_url("wallpapers/fondo4.jpg") ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                    </div>
                    <div class="card-footer">
                        <input type="radio" name="fondo" value="<?= base_url("wallpapers/fondo4.jpg") ?>">
                    </div>
                </div>


            </div>
        </div>
    <?php endif; ?>



<script>
    function editar_pass(ev){
        if(ev.target.checked) document.getElementById('masterpass').disabled=false;
        else  document.getElementById('masterpass').disabled=true;
    }
</script>