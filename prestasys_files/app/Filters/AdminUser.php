<?php namespace App\Filters;
 
use App\Models\Usuario_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminUser implements FilterInterface
{

 
 


    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $session = \Config\Services::session();
        $request = \Config\Services::request();

        $uri = $request->uri; 
        $principal= sizeof($uri->getSegments()) ==0  ; 
        if (!$session->has('nick')  ){
            if(  $principal )
            return redirect()->to(base_url("welcome/publico"));
            else
            return redirect()->to(base_url("admin/sign-in"));
        }   
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}