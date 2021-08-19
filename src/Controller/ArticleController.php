<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    #[Route('/articles/{id}', name: 'article_show')]
    public function showAction(SerializerInterface $serializer,Article $article)
    {    
        $data = $serializer->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-type','application/json');

        return $response;
    }

    #[
        Route('/articles', name: 'article_create',methods: ['POST'])
    ]
    public function createAction(Request $request,SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $data = $request->getContent();
        $article = $serializer->deserialize($data,' App\Entity\Article','json');

        $em->persist($article);
        $em->flush();

        return new Response('',Response::HTTP_CREATED);
    }

    #[
        Route('/articles',name: 'article_list',methods: ['GET'])
    ]
    public function listAction(SerializerInterface $serializer,ArticleRepository $articleRepository)
    {
        
        $data = $serializer->serialize($articleRepository->findAll(),'json');

        $response = new Response($data);
        
        $response->headers->set('Content-type','application/json');
        return $response;
    }
}
