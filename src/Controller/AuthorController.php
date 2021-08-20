<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;


class AuthorController extends AbstractController
{
    #[Route('/authors/{id}', name: 'author_show')]
    public function showAction(SerializerInterface $serializer,Author $author)
    {
        $data = $serializer->serialize($author, 'json');
        
        $response = new Response($data);
        $response->headers->set('Content-type','application/json');

        return $response;
    }
}
