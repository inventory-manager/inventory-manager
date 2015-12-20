<?php

namespace AppBundle\Controller;

use AppBundle\Models\Device;
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
 * Class DeviceController
 * @package AppBundle\Controller
 */
class DeviceController extends Controller
{
    /**
     * @Route("/devices", name="get_all_devices")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllDevicesAction()
    {
        $devices = $this->get('device_manager')->getAllDevices();

        return new JsonResponse($devices);
    }

    /**
     * @Route("/devicestates", name="get_all_device_states")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllDeviceStatesAction()
    {
        $deviceTypes = $this->get('device_manager')->getAllDeviceStates();

        return new JsonResponse($deviceTypes);
    }

    /**
     * @Route("/devices/{id}", name="get_single_device")
     * @Method({"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getDeviceByIdAction($id)
    {
        /** @var Device $device */
        $device = null;
        try {
            $device = $this->get('device_manager')->getDeviceById($id);
        } catch (ResourceNotFoundException $e) {
            throw $e;
        }

        return new JsonResponse($device);
    }

    /**
     * @Route("/devices", name="update_device")
     * @Method({"PUT"})
     * @ParamConverter("updatedDevice", class="AppBundle:Device", converter="device_converter")
     * @param Device $updatedDevice
     * @return JsonResponse
     */
    public function updateDeviceAction(Device $updatedDevice)
    {
        $device = null;
        try {
            $device = $this->get('device_manager')->updateDevice($updatedDevice);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($device, 200);
    }

    /**
     * @Route("/devices/{id}", name="delete_device")
     * @Method({"DELETE"})
     * @ParamConverter("toBeDeletedDevice", class="AppBundle:Device")
     * @param Device $toBeDeletedDevice
     * @return JsonResponse
     */
    public function deleteDeviceAction(Device $toBeDeletedDevice)
    {
        $this->get('device_manager')->deleteDevice($toBeDeletedDevice);

        return new JsonResponse($toBeDeletedDevice, 200);
    }

    /**
     * @Route("/devices", name="create_device")
     * @Method({"POST"})
     * @ParamConverter("newDevice", class="AppBundle:Device", converter="device_converter")
     * @param Device $newDevice
     * @return JsonResponse
     */
    public function createDeviceAction(Device $newDevice)
    {
        try {
            $this->get('device_manager')->createDevice($newDevice);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($newDevice, 201);
    }
}
