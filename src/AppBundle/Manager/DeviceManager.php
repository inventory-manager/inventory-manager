<?php

namespace AppBundle\Manager;

use AppBundle\Models\Device;
use AppBundle\Models\DeviceState;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DeviceManager
 * @package AppBundle\Manager
 */
class DeviceManager
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
     * DeviceManager constructor.
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
     * @return Device
     * @throws ResourceNotFoundException
     */
    public function getDeviceById($id)
    {
        /** @var Device $device */
        $device = $this->entityManager->find('AppBundle:Device', $id);

        if ($device === null) {
            throw new ResourceNotFoundException('Konnte Device mit id=' . $id . ' nicht finden.', 404);
        }

        return $device;
    }

    /**
     * @return Device[]
     */
    public function getAllDevices()
    {
        return $this->entityManager->getRepository('AppBundle:Device')->findAll();
    }

    /**
     * @return DeviceState[]
     */
    public function getAllDeviceStates()
    {
        return $this->entityManager->getRepository('AppBundle:DeviceState')->findAll();
    }

    /**
     * @param Device $newDevice
     * @throws ValidatorException
     */
    public function createDevice(Device $newDevice)
    {
        $newDevice->setCreatedBy($this->tokenStorage->getToken()->getUser());
        $newDevice->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($newDevice);

        if ($errors->count() === 0) {
            $this->entityManager->persist($newDevice);
            try {
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                throw new \LogicException('Devicename bereits vergeben.', 400);
            }
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }
    }

    /**
     * @param Device $toBeDeletedDevice
     */
    public function deleteDevice(Device $toBeDeletedDevice)
    {
        $this->entityManager->remove($toBeDeletedDevice);
        $this->entityManager->flush();
    }

    /**
     * @param Device $updatedDevice
     * @throws ValidatorException
     * @return Device
     */
    public function updateDevice(Device $updatedDevice)
    {
        /** @var Device $oldDevice */
        $oldDevice = $this->entityManager->find('AppBundle:Device', $updatedDevice->getId());

        if ($oldDevice === null) {
            throw new ResourceNotFoundException(
                'Device mit der id=' . $updatedDevice->getId() . ' wurde nicht gefunden.',
                404
            );
        }

        $oldDevice->setSerialNumber($updatedDevice->getSerialNumber());
        $oldDevice->setInventoryNumber($updatedDevice->getInventoryNumber());
        $oldDevice->setBuyDate($updatedDevice->getBuyDate());
        $oldDevice->setDueDate($updatedDevice->getDueDate());
        $oldDevice->setInUse($updatedDevice->isInUse());
        $oldDevice->setComment($updatedDevice->getComment());
        $oldDevice->setArticle($updatedDevice->getArticle());
        $oldDevice->setDeviceState($updatedDevice->getDeviceState());
        $oldDevice->setRoom($updatedDevice->getRoom());

        $oldDevice->setEditedBy($this->tokenStorage->getToken()->getUser());

        $errors = $this->validator->validate($oldDevice);

        if ($errors->count() === 0) {
            $this->entityManager->persist($oldDevice);
            $this->entityManager->flush();
        } else {
            throw new ValidatorException('Validierung fehlgeschlagen: ' . (string) $errors, 400);
        }

        return $oldDevice;
    }
}
