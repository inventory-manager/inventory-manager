<?php

namespace AppBundle\Manager;

use AppBundle\Models\Room;
use AppBundle\Models\RoomType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RoomManager
 * @package AppBundle\Manager
 */
class RoomManager
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
     * RoomManager constructor.
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
     * @param string $id
     * @return Room
     * @throws ResourceNotFoundException
     */
    public function getRoomById($id)
    {
        /** @var Room $room */
        $room = $this->entityManager->find('AppBundle:Room', $id);

        if ($room === null) {
            throw new ResourceNotFoundException('Konnte Raum mit id=' . $id . ' nicht finden.', 404);
        }

        return $room;
    }

    /**
     * @return Room[]
     */
    public function getAllRooms()
    {
        return $this->entityManager->getRepository('AppBundle:Room')->findAll();
    }

    /**
     * @return RoomType[]
     */
    public function getAllRoomTypes()
    {
        return $this->entityManager->getRepository('AppBundle:RoomType')->findAll();
    }

    /**
     * @param Room $newRoom
     * @throws ValidatorException
     */
    public function createRoom(Room $newRoom)
    {
        $newRoom->setCreatedBy($this->tokenStorage->getToken()->getUser());
        $newRoom->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($newRoom);

        if ($errors->count() === 0) {
            $this->entityManager->persist($newRoom);
            try {
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                throw new \LogicException('Raumname bereits vergeben.', 400);
            }
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }
    }

    /**
     * @param Room $toBeDeletedRoom
     */
    public function deleteRoom(Room $toBeDeletedRoom)
    {
        $this->entityManager->remove($toBeDeletedRoom);
        $this->entityManager->flush();
    }

    /**
     * @param Room $updatedRoom
     * @throws ValidatorException
     * @return Room
     */
    public function updateRoom(Room $updatedRoom)
    {
        /** @var Room $oldRoom */
        $oldRoom = $this->entityManager->find('AppBundle:Rppm', $updatedRoom->getId());

        if ($oldRoom === null) {
            throw new ResourceNotFoundException(
                'Raum mit der id=' . $updatedRoom->getId() . ' wurde nicht gefunden.',
                404
            );
        }

        $oldRoom->setRoomNumber($updatedRoom->getRoomNumber());
        $oldRoom->setType($updatedRoom->getType());

        $oldRoom->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($oldRoom);

        if ($errors->count() === 0) {
            $this->entityManager->persist($oldRoom);
            $this->entityManager->flush();
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }

        return $oldRoom;
    }
}
