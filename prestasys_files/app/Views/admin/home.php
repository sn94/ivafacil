<?= $this->extend("admin/layout/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>


<?php
// FONDO DE PANTALLA PERSONALIZADO

use App\Models\Admin_model;

$wallpaper = "";
if (session("id") != "") {
    $_usu_ = (new Admin_model())->find(session("id"));
    try {
        if ($_usu_->fondo != "" &&  $_usu_->fondo != "none"  &&  !is_null($_usu_->fondo))
            $wallpaper =  $_usu_->fondo;
        else {
            if ($_usu_->fondo == "")
                $wallpaper = base_url('assets/img/papers.jpg');
            else
                $wallpaper = "none";
        }
    } catch (Exception $ex) {
        $wallpaper = base_url('assets/img/papers.jpg');
    };
} else $wallpaper = base_url('assets/img/papers.jpg');
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
<h4 class="text-center mt-5" style="background-color: #ffffff90;">Bienvenido</h4>
<?= $this->endSection() ?>


<?= $this->section("scripts") ?>
<script>
    if ('serviceWorker' in navigator) {
 
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/ivafacil/sw_novedades_clientes.js', {
                        scope: "/ivafacil/admin/"
                    }).then(function(registration) {
                        // Registration was successful
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        // registration failed :(
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
           
        } else {
            alert("No soportado");
        }
</script>
<?= $this->endSection() ?>