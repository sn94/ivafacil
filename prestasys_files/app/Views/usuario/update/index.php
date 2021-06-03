 

<input type="hidden" name="tipo" value="C"><!-- C= cliente  -->

<?php if (isset($OPERACION)  &&  $OPERACION == "M") : ?>
    <input type="hidden" name="regnro" value="<?= $usuario->regnro ?>">
<?php endif; ?>

<div class="row form-group">

    <div class="col-12 col-md-6">
        <?=view("usuario/forms/personales1")?>
    </div>

    <div class="col-12 col-md-6">
        <?=view("usuario/forms/personales2")?>
    </div>

    <div class="col-12 pb-2"  >
        <?= view("usuario/forms/parametros") ?>
    </div>



    <!--Eleccion de fondo de pantalla -->
    <?php if (isset($usuario)) : ?>
        <div class="col-12">
            <label style="font-weight: 600;" for="nf-password" class=" form-control-label form-control-sm -label">Fondo de pantalla:</label>

         <?=view("usuario/forms/wallpaper")?>
        </div>
    <?php endif; ?>

</div>
<!--end row form  -->
<script>
    function clean_number(arg) {
        try {

            arg.val(arg.val().replaceAll(/\.|,|\-/g, ""));
        } catch (er) {}
    }





    function solo_num_guiones(ev) {
        //0 48   9 57
        if (ev.data == null) return;
        if (ev.data.charCodeAt() != 45 && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
            let cad = ev.target.value;
            let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
            ev.target.value = cad_n;
        }
    }


    function wallpaper() {
        let selected = Array.prototype.filter.call(document.querySelectorAll("input[name=fondo]"),
            function(ar) {
                return $(ar).prop("checked");
            });

        if (selected.length > 0) return selected[0].value;
        else return "";

    }



    function editar_pass(ev) {
        if (ev.target.checked) document.getElementById('masterpass').disabled = false;
        else document.getElementById('masterpass').disabled = true;
    }
</script>