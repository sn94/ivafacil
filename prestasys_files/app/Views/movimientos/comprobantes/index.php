 <?= $this->extend("layouts/iva") ?>



 

 <?= $this->section("section_presentacion") ?>
 <style>
     .principal_boton {
         text-align: center;
         font-weight: 600;
         font-size: 25px;
         color: #ecf2ec;
         background: #0000003d;
     }
 </style>


 <section id="home" class="home">
     <div class="home-overlay-fluid">
         <div class="container">
             <div class="row">
                 <div class="main_slider_area">
                     <div class="slider">
                         <div class="single_slider wow fadeIn" data-wow-duration="2s">
                         <img src="<?= base_url("assets/img/Logo.jpg") ?>" alt="">
                             <a class="principal_boton" href="<?= base_url("compra/create/N") ?>"  >Factura de compra</a>
                             <a class="principal_boton" href="<?= base_url("venta/create/N") ?>" >Factura de venta</a>

                             <a class="principal_boton" href="<?= base_url("retencion/create/N") ?>"  >Retención</a>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section><!-- End of Banner Section -->

 <?= $this->endSection() ?>






 <?= $this->section("section_footer") ?>
 <?= view("layouts/section_footer") ?>
 <?= $this->endSection() ?>