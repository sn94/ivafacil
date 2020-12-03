<?php

namespace App\Models;

use CodeIgniter\Model;

class Monedas_model extends Model {



   protected $table = 'monedas';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';/** */

   protected $useTimestamps = true;
   protected $allowedFields = 
  [ 
   'moneda','prefijo','nombre','tcambio','fechacambio'
  ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }



     public function add(){ 

        $datos= $this->request->getPost();
        $datos['fecha_alta']= date("yy-m-d");
         //SUBIDA DE ARCHIVOS
        $this->load->library("upload_file"); 
        $filepath= "../cedulas-fotos/";
       
         $n_f1=  "ced-anverso-".$datos['cedula'] ;$n_f2= "ced-reverso-".$datos['cedula'];
         $subida1= $this->upload_file->do_upload("foto1", $filepath, $n_f1 ); 
         $subida2= $this->upload_file->do_upload("foto2", $filepath, $n_f2);
         //antes de grabar
         $datos['foto1']= $subida1;
         $datos['foto2']= $subida2;
         $this->db->table("clientez")->insert( $datos);
         
     }


   public function edit(){
      $datos= request()->getPost();   
      if( session("tipo")=="A"){//SOLO ADMINISTRATIVOS

         $fecha_apro_rech= date("yy-m-d");
         $this->db->set('empresa', $datos['empresa']);
         $this->db->set('monto_a', $datos['monto_a']);
         $this->db->set('estado', $datos['estado']);
         $this->db->set('fecha_a_r', $fecha_apro_rech);
         $this->db->set('observacion', $datos['observacion']);
         //dinero retirado
         if( isset($datos['retirado'])) $this->db->set('retirado', "S");
         //Donde
         $this->db->where('cedula', $datos['cedula']);
         $this->db->update("clientez"); 
          
      }else{ //EDICION POR EL VENDEDOR O SUPERVISOR
          $this->db->where("cedula", $datos['cedula']); 
          //ACTUALIZACION DE FOTO CEDULA
         $this->load->library("upload_file"); 
         $filepath= "../cedulas-fotos/";
         $n_f1=  "ced-anverso-".$datos['cedula']; $n_f2= "ced-reverso-".$datos['cedula'];
         $subida1= $this->upload_file->do_upload("foto1", $filepath, $n_f1 ); 
         $subida2= $this->upload_file->do_upload("foto2", $filepath, $n_f2);
         //antes de grabar
         if($subida1) $datos['foto1']= $subida1;
         if($subida2) $datos['foto2']= $subida2;
         $this->db->update('clientez', $datos);
      
      }//end else
   }


   
     public function get( $ci ){  
        $QUER=  $this->db->table("clientez")
         ->select("clientez.*,usuarioz.cedula as vendedor, usuarioz.nombres as nombrevend")
         ->join("usuarioz", 'usuarioz.cedula = clientez.vendedor')
         ->where("clientez.cedula",  $ci);
         $dts= $QUER->get()->getFirstRow();
         return $dts;
     }

//El cliente llego a retirar el dinero
     public function retirado( $cedu){
      $this->db->set('retirado', "S");  
      $this->db->where('cedula', $cedu);
      $this->db->update("clientez"); 
      return $this->db->affected_rows();
     }



     public function listCustom(  $estado="0", $vendedor="0", $m1="1",  $m2="12", $empresa_fondo="0", $year= "" ){
      $params= $this->request->getPost();
      $resultado= $this->db->table("clientez");
      /**Definir parametros de consulta  si la peticion NO fuera POST*/
      if( !sizeof($params) ){
         $params= array("estado"=> $estado , "vendedor"=> $vendedor,"mes1"=> $m1, "mes2"=> $m2, "empresa"=> $empresa_fondo, "anio"=>$year);  
      } 
      if( $params['estado'] !="0" ){ $resultado->where('estado', $params['estado']); }//todo

      if(   $params['vendedor'] !="0"){   $resultado->where('vendedor', $params['vendedor']); }//cedula de vendedor
     
      if(   $params['empresa'] !="0"){   $resultado->where('empresa', $params['empresa']); }//empresa de fondos
      //definir anio
      $year=  (isset($params['anio']) && $params['anio']!="")?$params['anio']: date("yy") ;
      $resultado->where('year(clientez.fecha_alta)', $year);
      // iniciar consultas
       $resultado->select("clientez.*,usuarioz.usuario as vendedor")
       ->join("usuarioz",  'usuarioz.cedula = clientez.vendedor' )
       ->where('month(clientez.fecha_alta)>=', $params['mes1'])
      ->where('month(clientez.fecha_alta)<=', $params['mes2']);
      //quien accede, filtrar si se trata de vendedor
      if( session("tipo") == "V"){
         $resultado->where('vendedor', session("id")  );
      } 
      $datos=  $resultado->get()->getResultObject(); 
      return $datos;
   }


     public function list(){//Obtiene una lista de clientes dados de alta en el mes y por el vendedor autenticado actualmente
        $mes= intval(   date("m")   ); 
        $anio= intval(   date("yy")   ); 
        $resultado= NULL;
        if(session("tipo")=="S" || session("tipo")=="A"){
           //$params= array('month(fecha_alta)' => $mes );
           //$datos=  $this->db->get_where('clientez' , $params  );

           $resultado = $this->db->table("clientez")
           ->select("clientez.cedula,clientez.nombres,clientez.apellidos,clientez.telefono,usuarioz.usuario as vendedor,clientez.estado")
           ->where('month(clientez.fecha_alta)', $mes)
           ->where('year(clientez.fecha_alta)', $anio)
           ->join('usuarioz', 'usuarioz.cedula = clientez.vendedor')
           ->get()->getResultObject();

        }else{    
                $vendd= session("id"); 
                 $resultado = $this->db->table("clientez")
                 ->select("clientez.cedula,clientez.nombres,clientez.apellidos,clientez.telefono,usuarioz.usuario as vendedor,clientez.estado")
                 ->where('month(clientez.fecha_alta)', $mes)
                 ->where('year(clientez.fecha_alta)', $anio)
                 ->where('clientez.vendedor', $vendd)
                 ->join('usuarioz', 'usuarioz.cedula = clientez.vendedor')
                 ->get()->getResultObject();
        } 
        //var_dump( $this->db->last_query());
        return $resultado;
     }


     public function listByName($nom){//lista de clientes, buscados por nombre
        $nombre= strtolower(  $nom );
        $mes= intval(   date("m")   ); 
        $datos= NULL;
        if(session("tipo")=="S" || session("tipo")=="A"){
           $params= array('month(fecha_alta)' => $mes );
           $this->db->select('*');
           $this->db->from('clientez');
           $this->db->where('month(fecha_alta)', $mes) ;
           $this->db->like('lcase(nombres)', "$nombre");
           $datos = $this->db->get(); 
          // var_dump( $this->db->last_query() );
        }else{    
        $params= array('month(fecha_alta)' => $mes );
           $this->db->select('*');
           $this->db->from('clientez');
           $this->db->where('month(fecha_alta)', $mes) ;
           $this->db->where('vendedor', session("id")  ) ;
           $this->db->like('lcase(nombres)', "$nombre");
           $datos = $this->db->get(); 
        } 
        return $datos->result();
     }


     public function totalizarClientesPorEstado(){
      $this->db->select("count(estado) as total, estado");
      $this->db->from("clientez");
      $this->db->group_by("estado");
      if(session("tipo")=="V" ){//FILTRAR POR VENDEDOR
         $this->db->where("vendedor", session("id") );
      }   
      $Resul= $this->db->get();
      return $Resul->result();
    }

     public function habilitadoParaConfirmar($ci){
     
        $cli= $this->get( $ci);
        if($cli->estado == "A" || $cli->estado == "R"){
            $datos= array("confirmado"=>"Este prestamo ya sido ". ($cli->estado == "A"?"APROBADO":"RECHAZADO") ) ;
            return json_encode( $datos );
        }else {//SI FUERA PENDIENTE
            $datos= array("OK"=>"Sin problemas") ;
            return json_encode( $datos );
        }
     }
   

     public function del( $ci){
      $this->db->where("cedula", $ci);
      return $this->db->delete('clientez');
     }
}


?>