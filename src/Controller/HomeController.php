<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    /**
     * Undocumented function
     *
     * @Route("/hello/{prenom}", name="hello")
     */
    public function hello($prenom){
       
        return new Response("Bonjour ". $prenom);
    }
    /**
     *
     * @Route("/", name="homepage")
     */
    public function home(){
        return $this->render(
            'home.html.twig',
            [ 'title' => "Bye bye",
            'age' => 12]
        );
 
    }
}

?>