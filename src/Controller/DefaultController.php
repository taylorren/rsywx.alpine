<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class DefaultController extends AbstractController
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
    public function index()
    {
        $client =new Client(['base_uri'=>$this->_base]);

        $promises=[
            'summary'=>$client->getAsync('/books'),
            'latestbook'=>$client->getAsync('/books/latest'),
        ];

        $res=Promise\Utils::unwrap($promises);
        $summary=json_decode((string)$res['summary']->getBody())->data;
        $latestbook=json_decode((string)$res['latestbook']->getBody())->data;
        
        $data['summary']=$summary;
        $data['latestbook']=$latestbook[0];

        return $this->render('index.html.twig', ['data'=>$data]);
    }
}