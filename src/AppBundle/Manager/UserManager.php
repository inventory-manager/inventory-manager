<?php

namespace AppBundle\Manager;

use AppBundle\Models\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserManager
 * @package AppBundle\Manager
 */
class UserManager
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
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UserManager constructor.
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoder $encoder
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        EntityManager $entityManager,
        ValidatorInterface $validator,
        UserPasswordEncoder $encoder,
        TokenStorage $tokenStorage
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->passwordEncoder = $encoder;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $id
     * @return User
     * @throws ResourceNotFoundException
     */
    public function getUserById($id)
    {
        /** @var User $user */
        $user = $this->entityManager->find('AppBundle:User', $id);

        if ($user === null) {
            throw new ResourceNotFoundException('Konnte Benutzer mit id=' . $id . ' nicht finden.', 404);
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->entityManager->getRepository('AppBundle:User')->findAll();
    }

    /**
     * @param User $newUser
     * @throws ValidatorException
     */
    public function createUser(User $newUser)
    {
        $encoded = $this->passwordEncoder->encodePassword($newUser, $newUser->getPassword());
        $newUser->setPassword($encoded);
        $newUser->setCreatedBy($this->tokenStorage->getToken()->getUser());
        $newUser->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($newUser);

        if ($errors->count() === 0) {
            $this->entityManager->persist($newUser);
            try {
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                throw new \LogicException('Benutzername bereits vergeben.', 400);
            }
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }
    }

    /**
     * @param User $toBeDeletedUser
     */
    public function deleteUser(User $toBeDeletedUser)
    {
        $this->entityManager->remove($toBeDeletedUser);
        $this->entityManager->flush();
    }

    /**
     * @param User $updatedUser
     * @throws ValidatorException
     * @return User
     */
    public function updateUser(User $updatedUser)
    {
        /** @var User $oldUser */
        $oldUser = $this->entityManager->find('AppBundle:User', $updatedUser->getId());

        if ($oldUser === null) {
            throw new ResourceNotFoundException(
                'Benutzer mit der id=' . $updatedUser->getId() . ' wurde nicht gefunden.',
                404
            );
        }

        $oldUser->setUsername($updatedUser->getUsername());
        $oldUser->setFirstName($updatedUser->getFirstName());
        $oldUser->setLastName($updatedUser->getLastName());
        $oldUser->setEmail($updatedUser->getEmail());

        $encoded = $this->passwordEncoder->encodePassword($oldUser, $updatedUser->getPassword());
        $oldUser->setPassword($encoded);

        if ($updatedUser->getCurrentRole()) {
            $oldUser->setRole($updatedUser->getCurrentRole());
        }

        $oldUser->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($oldUser);

        if ($errors->count() === 0) {
            $this->entityManager->persist($oldUser);
            $this->entityManager->flush();
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }

        return $oldUser;
    }
}
