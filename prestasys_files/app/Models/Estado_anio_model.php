<?php

namespace App\Models;

use CodeIgniter\Model;

class Estado_anio_model extends Model {



   protected $table      = 'estado_anio';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'codcliente','anio','estado',  
      't_impo_compras',  't_impo_ventas', 't_impo_retencion',
      't_i_compras',  't_i_ventas', 't_retencion',
       'saldo', 'saldo_inicial', 'ruc', 'dv',
      'fecha_pago', 'pago'
   ];

    
    

     
 

   

  

}


?>