 <?= $this->extend("layouts/index_cliente") ?>

 <?= $this->section("estilos") ?>

 
 <style>

#right-panel{
         background-image: url(<?=base_url('assets/img/papers.jpg') ?>) !important;
         background-repeat: no-repeat;
         background-size: cover;
     }
     
     html {
         line-height: unset !important;
         height: 100% !important;
         background-image: url(<?= base_url('assets/img/papers.jpg') ?>) !important;
         background-repeat: no-repeat;
         background-size: cover;
     }
 
 </style> 

<?= $this->endSection() ?>

 

 <?= $this->section("contenido") ?>


 <h1 class="text-center m-5">Bienvenido</h1>
 <?= $this->endSection() ?>