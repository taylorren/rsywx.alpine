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
    private $_client;

    public function __construct() {
        $dotenv=new Dotenv();
        
        $dotenv->load(__DIR__.'/../../.env');

        //$this->_base=getenv('APP_ENV');
        $this->_base=$_ENV['BASE'];
        $this->_client=new Client(['base_uri'=>$this->_base]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $promises=[
            'summary'=>$this->_client->getAsync('/books'),
            'latestbook'=>$this->_client->getAsync('/books/latest'),
        ];

        $res=Promise\Utils::unwrap($promises);
        $summary=json_decode((string)$res['summary']->getBody())->data;
        $latestbook=json_decode((string)$res['latestbook']->getBody())->data;
        
        $data['summary']=$summary;
        $data['latestbook']=$latestbook[0];

        return $this->render('index.html.twig', ['data'=>$data]);
    }
}