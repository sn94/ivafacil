<?php
$BaseUrlForRes = base_url() . "/assets/menu/";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
 
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>CodePen - Circle links</title>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $BaseUrlForRes ?>style.css">

  <style>
    /* style.css | file:///C:/Users/sonia/Desktop/plantilla%20ivafacil/circle-links/dist/style.css */

    html {
      /* background-image: linear-gradient(-170deg,#064997 20%,#105ba7); */
      background-image: linear-gradient(-170deg, #add7a7 20%, #298037);
    }

    body {
      /* background-image: linear-gradient(270deg,#2b67ac 3px,transparent 0),linear-gradient(#2b67ac 3px,transparent 0),linear-gradient(270deg,rgba(43,103,172,.4) 1px,transparent 0),linear-gradient(#2b67ac 1px,transparent 0),linear-gradient(270deg,rgba(43,103,172,.4) 1px,transparent 0),linear-gradient(#2b67ac 1px,transparent 0); */
      background-image: linear-gradient(270deg, #72df53 3px, transparent 0), linear-gradient(#69cc61 3px, transparent 0), linear-gradient(270deg, rgba(119, 204, 88, 0.4) 1px, transparent 0), linear-gradient(#72d75d 1px, transparent 0), linear-gradient(270deg, rgba(127, 208, 110, 0.4) 1px, transparent 0), linear-gradient(#76d06f 1px, transparent 0);
    }


/* Elemento | http://localhost/ivafacil/ */

.links__list {
  /* --item-total: 5; */
  --item-total: 4 !important;
}
.links__list > h3:nth-child(1) {
padding: 0;
margin: 0;
}
.links { 
  position: relative;
  left: 20%;
  --link-size: 110px;
  min-height: 60vh;
}


    /***PARA EL TITULO IVA FACIL */

    /* Elemento | http://localhost/ivafacil/ */

    .links__list>h3:nth-child(1) {
      position: relative !important;
      z-index: 100000;
      text-align: center;
      right: 50% !important;
      left: -80% !important;
      bottom: 60% !important;
      top: 60% !important;
      font-size: 30px; color: #002423;

    }
  </style>
</head>

<body>
 
  <!-- partial:index.partial.html -->
  <div class="links">
    <ul class="links__list" style="--item-total:5">
      <h3 >
        IVA fácil
     
    </h3>
      <li class="links__item" style="--item-count:1">
        <a class="links__link" style="text-align: center;" href="<?= base_url("movimiento/index") ?>">
          Registrar comprobantes
          <span class="links__text">Registrar comprobantes</span>
        </a>
      </li>
      <li class="links__item" style="--item-count:2">
        <a class="links__link" style="text-align: center;" href="<?= base_url("movimiento/informe_mes") ?>">
          Movimientos del mes
          <span class="links__text">Movimientos del mes</span></a>
      </li>
      <li class="links__item" style="--item-count:3">
        <a class="links__link" style="text-align: center;" href="<?= base_url("movimiento/r_cierre") ?>">
          Cierre del mes
          <span class="links__text">Cierre del mes</span></a>
      </li>
      <li class="links__item" style="--item-count:4">
        <a class="links__link" style="text-align: center;" href="<?= base_url("movimiento/resumen_anio") ?>">
          Resumen del año
          <span class="links__text">Resumen del año</span></a>
      </li>

    </ul>
  </div>
  <h5 class="mt-1" style="text-align: center; padding: 20px 3px; color: red; font-weight: 600;background-color: #add7a7;">PARA HACER EL CIERRE DEL MES, DEBE ESTAR AL DÍA CON EL PAGO DEL SERVICIO</h5>

  <!-- partial -->
</body>

</html>