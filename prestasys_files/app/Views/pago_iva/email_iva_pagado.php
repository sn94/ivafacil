<?php

use App\Helpers\Utilidades;
?>
<h4>Sr/Sra <?=$cliente?>: </h4>
<p>Por este medio le comunicamos la liquidación del IVA correspondiente al mes de 
<span style="font-weight: 600;font-style: italic;"><?= Utilidades::monthDescr($mes)?></span> del ejercicio 
<span style="font-weight: 600;font-style: italic;"><?= $anio ?></span>. El pago se efectuó el 
<span style="font-weight: 600;font-style: italic;"><?= Utilidades::fechaDescriptiva($fecha_pago)?></span>

</p>

<p style="font-size: 14px;">
Atte.
<br>
El Equipo de IVA FÁCIL.
</p>
