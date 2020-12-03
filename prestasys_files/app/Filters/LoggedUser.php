<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoggedUser implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $session = \Config\Services::session();
        $request = \Config\Services::request();
         
        $uri = $request->uri;
        
        $accede_a_inicio=(  $uri->getSegment(1)=="");
       
       $no_acceso_a_login=  sizeof($uri->getSegments())==2  && $uri->getSegment(1) != "usuario" &&  $uri->getSegment(2)!= "sign_in";;

        if( !$session->has('usuario')  &&   $no_acceso_a_login )
       return redirect()->to(  base_url("usuario/sign_in")); 
       if( !$session->has('usuario')  && $accede_a_inicio)
       return redirect()->to(  base_url("usuario/sign_in")); 
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}