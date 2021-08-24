<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use App\Representation\Articles;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Exception\ResourceValidationException;


class ArticleController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     */
    public function showArticleAction(Article $article)
    {    
        return $article;
    }

   /**
    * @Rest\Post(
    *    path = "/articles",
    *    name = "app_article_create"
    *)
    * @Rest\View(
    *     statusCode = 201,
    *     serializerGroups = {"POST_CREATE"}
    *)
    * @ParamConverter(
    *    "article", 
    *    converter="fos_rest.request_body",
    *    options = {
    *       "validator"={"groups"="Create"}
    *    }
    *)
    */
    public function createArticleAction(Article $article,EntityManagerInterface $em,ConstraintViolationList $violations)
    {
        /*$data = $serializer->deserialize($request->getContent(),'array','json');
        $article = new Article;
        $form = $this->createForm(ArticleType::class,$article);
        $form->submit($data);*/

        if(count($violations)){
            //return $this->view($violations,Response::HTTP_BAD_REQUEST);
            $message = 'The JSON sent contains invalid data. Here are the erros you need to correct: ';
            foreach ($violations as $violation) {
                $message.=sprintf(
                    "Field %s: %s ",$violation->getPropertyPath(),
                    $violation->getMessage()
                );
                throw new ResourceValidationException($message);
            }
        }

        $em->persist($article);
        $em->flush();

        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl("app_article_show", ["id" => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }

    /**
     * @Rest\Get("/articles", name="app_article_list")
     * 
     * @Rest\QueryParam(
     *      name="keyword",
     *      requirements="[a-aA-Z0-9]",
     *      nullable=true,
     *      description="The keyword to search for."
     * )
     * 
     * @Rest\QueryParam(
     *      name="order",
     *      requirements="asc|desc",
     *      default="asc",
     *      description="Sort order (asc or desc)."
     * )
     * 
     * @Rest\QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      default="15",
     *      description="Max of articles per page."
     * )
     * 
     * @Rest\QueryParam(
     *      name="offset",
     *      requirements="\d+",
     *      default="1",
     *      description="The pagination offset."
     * )
     * 
     * @Rest\View()
     * 
     */
    public function listArticleAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('App:Article')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Articles ($pager);
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *      path = "/articles/{id}",
     *      name= "app_article_update",
     *      requirements = {"id"="\d+"},
     * )
     * @ParamConverter("newArticle",converter="fos_rest.request_body")
     */
    public function updateArticleAction(Article $article,Article $newArticle,ConstraintViolationList $violations,EntityManagerInterface $em)
    {
        if(count($violations)){
            //return $this->view($violations,Response::HTTP_BAD_REQUEST);
            $message = 'The JSON sent contains invalid data. Here are the erros you need to correct: ';
            foreach ($violations as $violation) {
                $message.=sprintf(
                    "Field %s: %s ",$violation->getPropertyPath(),
                    $violation->getMessage()
                );
                throw new ResourceValidationException($message);
            }
        }

        $article->setTitle($newArticle->getTitle());
        $article->setContent($newArticle->getContent());

        $em->flush();

        return $article;
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *      path = "/articles/{id}",
     *      name = "app_article_delete",
     *      requirements = {"id"="\d+"}
     * )
     */
    public function deleteArticleAction(Article $article,EntityManagerInterface $em)
    {   
        $em->remove($article);

        return;
    }

}
