<?php

namespace AppBundle\Converter;

use AppBundle\Models\Room;
use AppBundle\Models\RoomType;
use AppBundle\Models\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoomConverter
 * @package AppBundle\Converter
 */
class RoomConverter implements ParamConverterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * RoomConverter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        if (!isset($decoded['roomNumber']) || !isset($decoded['type']) || !isset($decoded['user'])) {
            throw new \InvalidArgumentException('Raum konnte nicht erstellt werden, fehlende Parameter', 400);
        }

        $room = new Room();
        $room->setRoomNumber($decoded['roomNumber']);
        if (!isset($decoded['oldRoomNumber'])) {
            $room->setOldRoomNumber($decoded['roomNumber']);
        } else {
            $room->setOldRoomNumber($decoded['oldRoomNumber']);
        }

        /** @var RoomType $type */
        $type = $this->entityManager->find('AppBundle:RoomType', $decoded['type']);
        if ($type !== null) {
            $room->setType($type);
        } else {
            throw new \InvalidArgumentException('Raumtyp ' . $decoded['type'] . ' konnte nicht gefunden werden', 400);
        }

        /** @var User $user */
        $user = $this->entityManager->find('AppBundle:User', $decoded['user']);
        if ($user !== null) {
            $room->setUser($user);
        } else {
            throw new \InvalidArgumentException('User ' . $decoded['user'] . ' konnte nicht gefunden werden', 400);
        }

        $request->attributes->set($configuration->getName(), $room);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:Room';
    }
}
