<?php

namespace AppBundle\Converter;

use AppBundle\Models\Article;
use AppBundle\Models\Device;
use AppBundle\Models\DeviceState;
use AppBundle\Models\Room;
use DateTime;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DeviceConverter
 * @package AppBundle\Converter
 */
class DeviceConverter implements ParamConverterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DeviceConverter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        if (!isset($decoded['id']) || !isset($decoded['serialNumber']) || !isset($decoded['inventoryNumber']) ||
            !isset($decoded['buyDate']) || !isset($decoded['dueDate']) || !isset($decoded['inUse']) ||
            !isset($decoded['comment']) || !isset($decoded['state']) || !isset($decoded['room']) ||
            !isset($decoded['article'])) {
            throw new \InvalidArgumentException('Device konnte nicht erstellt werden, fehlende Parameter', 400);
        }

        $device = Device::createDevice(
            $decoded['id'],
            $decoded['serialNumber'],
            $decoded['inventoryNumber'],
            new DateTime($decoded['buyDate']),
            new DateTime($decoded['dueDate']),
            $decoded['inUse'],
            $decoded['comment']
        );

        /** @var DeviceState $state */
        $state = $this->entityManager->find('AppBundle:DeviceState', $decoded['state']);
        if ($state !== null) {
            $device->setDeviceState($state);
        } else {
            throw new \InvalidArgumentException(
                'Devicestate ' . $decoded['state'] . ' konnte nicht gefunden werden',
                400
            );
        }

        /** @var Room $room */
        $room = $this->entityManager->find('AppBundle:Room', $decoded['room']);
        if ($room !== null) {
            $device->setRoom($room);
        } else {
            throw new \InvalidArgumentException('Room ' . $decoded['room'] . ' konnte nicht gefunden werden', 400);
        }

        /** @var Article $article */
        $article = $this->entityManager->find('AppBundle:Article', $decoded['article']);
        if ($room !== null) {
            $device->setArticle($article);
        } else {
            throw new \InvalidArgumentException(
                'Article ' . $decoded['article'] . ' konnte nicht gefunden werden',
                400
            );
        }

        $request->attributes->set($configuration->getName(), $device);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:Device';
    }
}
