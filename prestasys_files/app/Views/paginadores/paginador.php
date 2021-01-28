
<?php

$request = \Config\Services::request();
$uri = $request->uri;

 
$Link=  base_url()."/".$uri->getPath();
//.( $uri->getQuery()== "" ?  ""  :   "?".$uri->getQuery()   );     


 
//calcular paginas 
$numero_de_paginas=  is_float( $TotalRegistros /  10 ) ?  ceil(   $TotalRegistros /  10)  : (  $TotalRegistros /  10 );
 
$links=  [];
$n_pagina= 0;
for( $nl= 0; $nl  < $numero_de_paginas ; $nl ++ ){
     array_push(  $links, [     "link"=> "$Link?page=$n_pagina", "label"=> ($nl+1) ,  "raw_index"=>  $n_pagina ]  );
     $n_pagina += 10;
}


?>

<nav aria-label="Page navigation">
    <ul class="pagination"  style="font-weight: 600;">
    

    <?php 
     
     
     $page= isset( $_REQUEST['page'] ) ?  $_REQUEST['page'] :  0;
    foreach ($links as $link) : ?>
        <li>

        <?php  

        //Pagina Seleccionada
       
        $fondoDistintivo=  $page == $link['raw_index']  ? "bg-warning text-dark": "";
        ?>
            <a class="btn btn-dark btn-sm <?= $fondoDistintivo?>   " onclick="<?=$EVENT_HANDLER?>"  href="<?= $link['link'] ?>">
                <?= $link['label'] ?>
            </a>
        </li>
    <?php 
    
    endforeach ?>

    
    </ul>
</nav>