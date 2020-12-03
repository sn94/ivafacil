<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuario_model extends Model {



   protected $table      = 'usuarios';
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'ruc','dv','pass','tipo','fechainicio','demo','tipoplan','estado','email','cliente','cedula','celular','telefono','domicilio','rubro','ciudad','saldo_IVA','pass_anterior'
   ];

    public function __construct(){
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request=  \Config\Services::request();
	 }



     public function add(){
        $datos= $this->input->post();
        $datos['fecha_alta']= date("yy-m-d");
        $datos['pass']=   password_hash($datos['pass'],  PASSWORD_DEFAULT );

        $this->db->table("usuarioz")->insert( $datos);   
     }

  

     public function get( $ci ){  
         $dts= $this->db->table('usuarioz')->where(  'cedula' ,$ci )->get()->getRowObject();
         return $dts;
     }


     public function getByName( $n ){  
      $dts= $this->db->table('usuarioz')->where('usuario', $n )->get()->getRowObject();
     
      return $dts;
  }


     public function list( $opc= "0"){
        $params= NULL;
        $mes= date("m"); 
        $datos= $this->db->table('usuarioz');
        if(  $opc != "0"){  $datos->where( "tipousuario", $opc); }
          
        return $datos->get()->getResultObject();;
     }
   
     public function list_array(){
      $mes= date("m");
      $resultados= $this->db->table('usuarioz')
      ->select( "cedula,nombres,usuario")
      ->where( 'month(fecha_alta)' , $mes );
      $datos=  $resultados->get();//sin parametros
      return $datos->getResultObject();
   }

   public function list_vendedores(){
      $resultados = $this->db->table('usuarioz')
      ->select("cedula, nombres")
      ->where("tipousuario" , "V")
      ->get()->getResultObject();
      return $resultados;
   }

   public function comision_acumulada( $ced ){
     $dt= $this->db->table("usuarioz")
      ->select("ifnull( round((SUM(clientez.monto_a)*usuarioz.comision)/100),0) as total")
      ->join("usuarioz", "clientez.vendedor=usuarioz.cedula")
      ->where( "clientez.estado", "A")
      ->where( "clientez.retirado", "S")
      ->where( "usuarioz.tipousuario", "V")
      ->where( "clientez.vendedor", $ced)
      ->get( )->getRowObject();
      return $dt;
   }


   public function passwordUpdate(){
      $datos= $this->request->getPost();
      $this->db->table("usuarioz")->set('pass', $datos['clave-n'])
      ->where('cedula',  $datos['cedula'])
      ->update(); 
      return $datos;
   }

     public function edit(){
      
        $datos= $this->request->getPost();
        if( isset( $datos['pass'] ) )
        $datos['pass']= password_hash($datos['pass'],  PASSWORD_DEFAULT );
        
        $this->db->table("usuarioz")
        ->set($datos)
        ->where("cedula", $datos['cedula'])
        ->update();


     }

     public function del( $ci){
      $resultado= $this->db->table("usuarioz")->where("cedula", $ci)->delete('usuarioz');
      return $resultado;
     }



     public function  correctPassword( $passinput, $nick ){
        $usr= $this->getByName(  $nick);
        return password_verify( $passinput,  $usr->pass  );
     }



     /** REGISTRA EL ACCESO DE UN USUARIO EN LA BD TRAS INICIAR SESION
      * POSTERIORMENTE ESTE REGISTRO ES BORRADO AL CERRAR LA SESION
      *SU UTILIDAD RADICA EN EVITAR VARIOS ACCESOS DE UN MISMO USUARIO A LA VEZ
      */
     public function accessLogged( $cedula ){
        //verificar si ya existe acceso
        $rr= $this->db->table("accesos")->where(  'id_usuario',  $cedula )->get()->getRowObject() ;
        if( !is_null($rr) ){
         return   array('error' => "Este usuario ya inicio sesion" ); 
        }else{
            $dts= array("id_usuario"=> $cedula);
            $r= $this->db->table("accesos")->insert( $dts);
            if($r){
               return   array('OK' => "Acceso libre" ); 
            }else{
               return   array('error' => "Error de red" ); 
            }   
        }
     }




     public function accessLoggedOut( $cedula ){
      //verificar si ya existe acceso
      $resultado= $this->db->table("accesos")
      ->where(   'id_usuario' , $cedula  )
      ->get()->getRowObject() ;

      if(  !is_null($resultado)  ){ 
          $r= $this->db->table("accesos")->delete( "id_usuario", $cedula);//liberar acceso
          if($r){
             return   array('OK' => "Acceso libre" ); 
          }else{
             return   array('error' => "Error de red" ); 
          }   
      } return array('error' => "no hay registro" ); 
   }


}


?>