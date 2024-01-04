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
    private $_client;

    public function __construct() {
        $dotenv=new Dotenv();
        
        $dotenv->load(__DIR__.'/../../.env');

        //$this->_base=getenv('APP_ENV');
        $this->_base=$_ENV['BASE'];
        $this->_client =new Client(['base_uri'=>$this->_base]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detail($bookid)
    {
        $promises=[
            'detail'=>$this->_client->getAsync("/books/$bookid"),
            'tags'=>$this->_client->getAsync("/books/tags/$bookid"),
            'reviews'=>$this->_client->getAsync("/books/reviews/$bookid"),
        ];
        

        //$res=Promise\Utils::unwrap($promises);
        // Using settle is more elegant as it will not throw 404 when "detail" of a "removed" book is not found.
        $res=Promise\Utils::settle($promises)->wait(); 
        
        if($res['detail']['state']=='rejected') //404 error, which means this book is not in stock
        {
            throw $this->createNotFoundException('The book is not in stock');
            
        }

        $detail=json_decode((string)$res['detail']['value']->getBody())->data;
        $tags=json_decode((string)$res['tags']['value']->getBody())->data;
        $reviews=json_decode((string)$res['reviews']['value']->getBody())->data;

        $data['detail']=$detail;
        $data['tags']=$tags;
        $data['reviews']=$reviews;

        return $this->render('layout/detail.html.twig', ['data'=>$data]);
    }
}
