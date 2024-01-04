<?php
// src/Controller/ExceptionController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ExceptionController extends AbstractController
{
    public function show(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        if ($statusCode === 404) {
            // Handle "No route found" error
            return $this->render('404.html.twig');
        }
        
        // Handle other types of exceptions
        return new Response(
            'An error occurred.',
            $statusCode
        );
    }
}
