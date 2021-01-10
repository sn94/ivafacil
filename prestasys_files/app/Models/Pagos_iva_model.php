<?php

namespace App\Models;

use CodeIgniter\Model;

class Pagos_iva_model extends Model {



   protected $table      = 'pagos_iva';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
     'ruc', 'dv', 'codcliente',   'comprobante','importe','fecha',  'mes', 'anio'
   ];

    
    

     
 

   

  

}


?>