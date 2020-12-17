<?php 
namespace App\Libraries; 

class Correo{



    private $destinatario=  "";
    private $asunto= "";
    private $mensaje="";


public function __construct(){

    

}


    public function setDestinatario($ar)
    {
        $this->destinatario = $ar;
    }

    public function setAsunto($ar)
    {
        $this->asunto = $ar;
    }

    public function setMensaje($ar)
    {
        $this->mensaje = $ar;
    }

public function enviar(){
    $email_co= \Config\Services::email();
    $imagelogo=  base_url("assets/img/Logo.jpg");
    $email_co->attach( $imagelogo);
    $cid = $email_co->setAttachmentCID($imagelogo);
 
    $email_co->setTo( $this->destinatario);
    $email_co->setSubject( $this->asunto);
    $email_co->setMessage( view( $this->mensaje,  ["cid"=>  $cid]));
    $email_co->send();
 
    //Visualizamos el estado de las cabeceras despues de enviar, verificamos tambien si todo ha ido bien
  //$data = $email_co->printDebugger(['headers']);
  
  
}










}

?>
