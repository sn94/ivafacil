 <?= $this->extend("layouts/index_cliente") ?>
 <?php
// FONDO DE PANTALLA PERSONALIZADO

use App\Models\Usuario_model;

$wallpaper = "";
if (session("id") != "") {
    $_usu_ = (new Usuario_model())->find(session("id"));
    try {
        if ($_usu_->fondo != ""  &&  !is_null($_usu_->fondo))
            $wallpaper =  $_usu_->fondo;
        else {
            if ($_usu_->fondo == "")  $wallpaper= "none";
            else $wallpaper = $_usu_->fondo;
        }
    } catch (Exception $ex) {
        $wallpaper = "none";
    };
} else  $wallpaper = "none";
?>
 
 <?= $this->section("estilos") ?>



 

<style>
        #right-panel {
            background-image: url(<?= $wallpaper ?>) !important;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .right-panel header.header {
            margin-bottom: -10px;
        }

        html {
            line-height: unset !important;
            height: 100% !important;
            background-image: url(<?= $wallpaper ?>) !important;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>

<?= $this->endSection() ?>

 

 <?= $this->section("contenido") ?>


 <h1 class="text-center m-5">Bienvenido</h1>


  
 
 <?= $this->endSection() ?>