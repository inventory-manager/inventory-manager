<?php

namespace AppBundle\Converter;

use AppBundle\Models\Article;
use AppBundle\Models\ArticleCategory;
use AppBundle\Models\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleConverter
 * @package AppBundle\Converter
 */
class ArticleConverter implements ParamConverterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ArticleConverter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        if (!isset($decoded['article_number']) || !isset($decoded['category']) || !isset($decoded['name'])
            || !isset($decoded['description']) || !isset($decoded['comment'])) {
            throw new \InvalidArgumentException('Artikel konnte nicht erstellt werden, fehlende Parameter', 400);
        }

        $article = new Article();
        $article->setArticleNumber($decoded['article_number']);
        $article->setName($decoded['name']);

        $article->setComment($decoded['comment']);
        $article->setDescription($decoded['description']);

        /** @var ArticleCategory $artCat */
        $artCat = $this->entityManager->find('AppBundle:ArticleCategory', $decoded['category']);
        if ($artCat !== null) {
            $article->setCategory($artCat);
        }

        $request->attributes->set($configuration->getName(), $article);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:Article';
    }
}
