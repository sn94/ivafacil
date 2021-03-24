<div class="card-footer">



    <?php

   
    if (isset($error)    &&   $error ==  "") : ?>

        <a onclick="cerrar( event)" style="font-size: 10px;font-weight: 600;" href="<?= base_url("cierres/cierre-mes") ?>" class="btn btn-success">
            <i class="fa fa-dot-circle-o"></i> CERRAR EL MES
        </a>

    <?php
    endif;
    ?>

</div>