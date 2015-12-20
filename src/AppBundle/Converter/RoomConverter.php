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

        if (!isset($decoded['roomNumber']) || !isset($decoded['type'])) {
            throw new \InvalidArgumentException('Raum konnte nicht erstellt werden, fehlende Parameter', 400);
        }

        $room = new Room();
        $room->setRoomNumber($decoded['roomNumber']);

        /** @var RoomType $type */
        $type = $this->entityManager->find('AppBundle:RoomType', $decoded['type']);
        if ($type !== null) {
            $room->setType($type);
        }

        $request->attributes->set($configuration->getName(), $room);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:Room';
    }
}
