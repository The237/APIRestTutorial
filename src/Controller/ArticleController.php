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
use JMS\Serializer\SerializationContext;


class ArticleController extends AbstractController
{
    #[Route('/articles/{id}', name: 'article_show')]
    public function showArticleAction(SerializerInterface $serializer,Article $article)
    {    
        $data = $serializer->serialize($article, 'json',
        SerializationContext::create()->setGroups(array('detail')));

        $response = new Response($data);
        $response->headers->set('Content-type','application/json');

        return $response;
    }

    #[
        Route('/articles', name: 'article_create',methods: ['POST'])
    ]
    public function createArticleAction(Request $request,SerializerInterface $serializer, EntityManagerInterface $em)
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
    public function listArticleAction(SerializerInterface $serializer,ArticleRepository $articleRepository)
    {
        
        $data = $serializer->serialize($articleRepository->findAll(),'json',
        SerializationContext::create()->setGroups(array('list')));

        $response = new Response($data);
        
        $response->headers->set('Content-type','application/json');
        return $response;
    }
}
