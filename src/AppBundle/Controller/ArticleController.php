<?php

namespace AppBundle\Controller;

use AppBundle\Models\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class ArticleController
 * @package AppBundle\Controller
 */
class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="get_all_articles")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllArticlesAction()
    {
        $articles = $this->get('article_manager')->getAllArticles();

        return new JsonResponse($articles);
    }
    /**
     * @Route("/articleCats", name="get_all_article_categories")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllArticleCategoriesAction()
    {
        $articleCat = $this->get('article_manager')->getAllArticleCategories();

        return new JsonResponse($articleCat);
    }
    /**
     * @Route("/articles/{articleNo}", name="get_single_article")
     * @Method({"GET"})
     * @param $articleNo
     * @return JsonResponse
     */
    public function getArticleByArticleNoAction($articleNo)
    {
        /** @var Article $article */
        $article = null;
        try {
            $article = $this->get('article_manager')->getArticleByArtNo($articleNo);
        } catch (ResourceNotFoundException $e) {
            throw $e;
        }

        return new JsonResponse($article);
    }

    /**
     * @Route("/articles", name="update_article")
     * @Method({"PUT"})
     * @ParamConverter("updatedArticle", class="AppBundle:Article", converter="article_converter")
     * @param Article $updatedArticle
     * @return JsonResponse
     */
    public function updateArticleAction(Article $updatedArticle)
    {
        $article = null;
        try {
            $article = $this->get('article_manager')->updateArticle($updatedArticle);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($article, 200);
    }

    /**
     * @Route("/articles/{id}", name="delete_article")
     * @Method({"DELETE"})
     * @ParamConverter("toBeDeletedArticle", class="AppBundle:Article")
     * @param Article $toBeDeletedArticle
     * @return JsonResponse
     */
    public function deleteArticlesAction(Article $toBeDeletedArticle)
    {
        $this->get('article_manager')->deleteArticle($toBeDeletedArticle);

        return new JsonResponse($toBeDeletedArticle, 200);
    }

    /**
     * @Route("/articles", name="create_article")
     * @Method({"POST"})
     * @ParamConverter("newArticle", class="AppBundle:Article", converter="article_converter")
     * @param Article $newArticle
     * @return JsonResponse
     */
    public function createArticleAction(Article $newArticle)
    {
        try {
            $this->get('article_manager')->createArticle($newArticle);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($newArticle, 201);
    }
}
