 <?= $this->extend("layouts/index_cliente") ?>

 <?= $this->section("estilos") ?>

 <?php

    use App\Libraries\Mobile_Detect;

if( ! (new Mobile_Detect())->isMobile()) :?>
 <style>

#right-panel{
         background-image: url(<?=base_url('assets/img/papers.jpg') ?>) !important;
         background-repeat: no-repeat;
         background-size: cover;
     }
 </style>
 <?php  endif; ?>

<?= $this->endSection() ?>

 

 <?= $this->section("contenido") ?>


 <h1 class="text-center m-5">Bienvenido</h1>
 <?= $this->endSection() ?>