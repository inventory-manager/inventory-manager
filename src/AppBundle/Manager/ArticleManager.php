<?php

namespace AppBundle\Manager;

use AppBundle\Models\Article;
use AppBundle\Models\ArticleCategory;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ArticleManager
 * @package AppBundle\Manager
 */
class ArticleManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * ArticleManager constructor.
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     * @param TokenStorage $tokenStorage
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(
        EntityManager $entityManager,
        ValidatorInterface $validator,
        TokenStorage $tokenStorage,
        AuthorizationChecker $authorizationChecker
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }
    /**
     * @param string $artNo
     * @return Article
     * @throws ResourceNotFoundException
     */
    public function getArticleByArtNo($artNo)
    {
        /** @var Article $article */
        $article = $this->entityManager->find('AppBundle:Article', $artNo);

        if ($article === null) {
            throw new ResourceNotFoundException('Konnte Artikel mit article_number=' . $artNo . ' nicht finden.', 404);
        }

        return $article;
    }

    /**
     * @return Article[]
     */
    public function getAllArticles()
    {
        return $this->entityManager->getRepository('AppBundle:Article')->findAll();
    }

    /**
     * @return ArticleCategories[]
     */
    public function getAllArticleCategories()
    {
        return $this->entityManager->getRepository('AppBundle:ArticleCategory')->findAll();
    }

    /**
     * @param Article $newArticle
     * @throws ValidatorException
     */
    public function createArticle(Article $newArticle)
    {
        $newArticle->setCreatedBy($this->tokenStorage->getToken()->getUser());
        $newArticle->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($newArticle);

        if ($errors->count() === 0) {
            $this->entityManager->persist($newArticle);
            try {
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                throw new \LogicException('Artikelname bereits vergeben.', 400);
            }
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }
    }

    /**
     * @param Article $toBeDeletedArticle
     */
    public function deleteArticle(Article $toBeDeletedArticle)
    {
        $this->entityManager->remove($toBeDeletedArticle);
        $this->entityManager->flush();
    }

    /**
     * @param Article $updatedArticle
     * @throws ValidatorException
     * @return Article
     */
    public function updateArticle(Article $updatedArticle)
    {
        /** @var Article $oldArticle */
        $oldArticle = $this->entityManager->find('AppBundle:Article', $updatedArticle->getArticleNumber());

        if ($oldArticle === null) {
            throw new ResourceNotFoundException(
                'Artikel mit der article_number=' . $updatedArticle->getArticleNumber() . ' wurde nicht gefunden.',
                404
            );
        }

        $oldArticle->setArticleNumber($updatedArticle->getArticleNumber());
        $oldArticle->setCategory($updatedArticle->getCategory());

        $oldArticle->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($oldArticle);

        if ($errors->count() === 0) {
            $this->entityManager->persist($oldArticle);
            $this->entityManager->flush();
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }

        return $oldArticle;
    }
}
