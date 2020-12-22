<?php

namespace App\Models;

use CodeIgniter\Model;

class Cierre_mes_model extends Model {



   protected $table      = 'cierre_mes';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'codcliente', 'mes','anio','estado',  't_i_compras',  't_i_ventas', 't_retencion', 'saldo'
   ];

    
    

     
 

   

  

}


?>