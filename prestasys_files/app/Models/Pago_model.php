<?php

namespace App\Models;

use CodeIgniter\Model;

class Pago_model extends Model {



   protected $table      = 'pagos';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'comprobante','fecha','validez',  'plan', 'concepto', 'precio', 'cliente', 'estado', 'ruc', 'dv'
   ];

    
    

     
 

   

  

}


?>