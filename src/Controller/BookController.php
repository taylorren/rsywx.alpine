<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class BookController extends AbstractController
{
    private $_base;

    public function __construct() {
        $dotenv=new Dotenv();
        
        $dotenv->load(__DIR__.'/../../.env');

        //$this->_base=getenv('APP_ENV');
        $this->_base=$_ENV['BASE'];
        
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detail($bookid)
    {
        $data=$bookid;
        return $this->render('layout/detail.html.twig', ['data'=>$data]);
    }
}