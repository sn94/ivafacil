<?php

namespace App\Models;

use CodeIgniter\Model;

class Calendario_model extends Model {



   protected $table      = 'calendario_pagos';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'ultimo_d_ruc', 'dia_vencimiento'
   ];

   

  

}


?>