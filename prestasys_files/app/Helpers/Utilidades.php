<?php 


namespace App\Helpers;
use Exception;
use DateTime;
 

class Utilidades{


  public static function limpiar_numero( $ar){

    try{
      $cleaned= preg_replace("/(\.|,)+/",  "",  $ar);
      return $cleaned;
    }catch( Exception $err){
      return "";
    }
    }


    public static function number_f( $ar){

        try{
          $v= floatval( $ar);
          return number_format($v, 0, '', '.');  
        }catch( Exception $err){
          return "";
        }
        }

        /**From Timestamp */
      public static function  from_timestamp( $arg){
        // Fecha en formato yyyy/mm/dd   H 24hs   h 12 hs
      $fecha = DateTime::createFromFormat('Y-m-d H:i:s',  $arg);
      // Fecha en formato dd/mm/yyyy
      $fecha_="";
      if(! is_bool($fecha)){
        try{
          $fecha_= $fecha->format('d/m/Y');
        }catch (Exception $e) {}
      }
      return $fecha_;
      }


        /**Devuelde de yyyy-mm-dd a dd-mm-yyyy */
    public static function fecha_f( $fe){ 
        //convertir de d/m/Y a Y/m/d
       if( $fe==""  || $fe =="0000-00-00" ) return "";
      
       if( strlen($fe) > 10){
          $timestamp_= date_create_from_format("Y-m-j H:i:s",  $fe);
          return $timestamp_->format("d/m/Y");
       }else{
          $fecha= explode("-",  $fe);
          if( sizeof( $fecha) > 1){
            return   $fecha[2]."/".$fecha[1]."/". $fecha[0]; 
          }else
          return  $fe;//la fecha esta en otro formato
       }
    }

      
  //Formato numero decimal de coma  a punto
        public static function fromComaToDot( $ar){
          return str_replace( ",", ".", $ar);
        }



    public static function dropdown( $params){
        $resu=  array();
        foreach( $params as $ite):
             $nuevo=array_values( $ite);
                $resu[$nuevo[0]]= $nuevo[1];
             
        endforeach;
        return $resu;
    }




    
public static   function dayName( $Dia=""){
  $Dia=   $Dia=="" ?  date("N")  : $Dia;
$DiaH="";
switch( $Dia){
    case 1:  $DiaH= "lunes";break;
    case 2:  $DiaH= "martes";break;
    case 3:  $DiaH= "miercoles";break;
    case 4:  $DiaH= "jueves";break;
    case 5:  $DiaH= "viernes";break;
    case 6:  $DiaH= "sabado";break;
    case 7:  $DiaH= "domingo";break;
}  return   $DiaH;
}
      
public  static function monthDescr($m=""){
  $m=  $m== ""? date("n"): $m;
  $r="";
  switch( $m){
      case 1: return "Enero";break;
      case 2: return "Febrero";break;
      case 3: return "Marzo";break;
      case 4: return "Abril";break;
      case 5: return "Mayo";break;
      case 6: return "Junio";break;
      case 7: return "Julio";break;
      case 8: return "Agosto";break;
      case 9: return "Septiembre";break;
      case 10: return "Octubre";break;
      case 11: return "Noviembre";break;
      case 12: return "Diciembre";break;
  }  return $r;
}


    public static function fechaDescriptiva(){
      $dia= Utilidades::dayName();
      $mes= Utilidades::monthDescr();
      $anio= date("Y");
      $fechacompleta=  $dia.", ".(date("d"))." de $mes del $anio";
      return $fechacompleta;
    }



    public static function generar_password(){
      //Carácteres para la contraseña
      $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
      $password = "";
      //Reconstruimos la contraseña segun la longitud que se quiera
      for($i=0;$i<10;$i++) {
         //obtenemos un caracter aleatorio escogido de la cadena de caracteres
         $password .= substr($str,rand(0,62),1);
      }
      //Mostramos la contraseña generada
      return $password;
 }



 public static function formato_factura( $arg){
   if( $arg == "" ) return $arg;
   if( strlen( $arg)  == 15 ) return $arg;
   $p1=   str_pad(    substr( $arg, 0, 3 )  ,  3  , "0",  STR_PAD_LEFT);
   $p2=   str_pad(  substr(  $arg,  3, 3),  3,  "0", STR_PAD_LEFT  );
   $p3=  str_pad( substr( $arg, 6, 7 ),  3,  "0",  STR_PAD_LEFT);
   return "$p1-$p2-$p3";
 }
 
}


?>