<?php

namespace AppBundle\Controller;

use AppBundle\Models\Room;
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
 * Class RoomController
 * @package AppBundle\Controller
 */
class RoomController extends Controller
{
    /**
     * @Route("/rooms", name="get_all_rooms")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllRoomsAction()
    {
        $rooms = $this->get('room_manager')->getAllRooms();

        return new JsonResponse($rooms);
    }

    /**
     * @Route("/roomtypes", name="get_all_room_types")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllRoomTypesAction()
    {
        $roomTypes = $this->get('room_manager')->getAllRoomTypes();

        return new JsonResponse($roomTypes);
    }

    /**
     * @Route("/rooms/{id}", name="get_single_room")
     * @Method({"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getRoomByIdAction($id)
    {
        /** @var Room $room */
        $room = null;
        try {
            $room = $this->get('room_manager')->getRoomById($id);
        } catch (ResourceNotFoundException $e) {
            throw $e;
        }

        return new JsonResponse($room);
    }

    /**
     * @Route("/rooms", name="update_room")
     * @Method({"PUT"})
     * @ParamConverter("updatedRoom", class="AppBundle:Room", converter="room_converter")
     * @param Room $updatedRoom
     * @return JsonResponse
     */
    public function updateRoomAction(Room $updatedRoom)
    {
        $room = null;
        try {
            $room = $this->get('room_manager')->updateRoom($updatedRoom);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($room, 200);
    }

    /**
     * @Route("/rooms/{id}", name="delete_room")
     * @Method({"DELETE"})
     * @ParamConverter("toBeDeletedRoom", class="AppBundle:Room")
     * @param Room $toBeDeletedRoom
     * @return JsonResponse
     */
    public function deleteRoomAction(Room $toBeDeletedRoom)
    {
        $this->get('room_manager')->deleteRoom($toBeDeletedRoom);

        return new JsonResponse($toBeDeletedRoom, 200);
    }

    /**
     * @Route("/rooms", name="create_room")
     * @Method({"POST"})
     * @ParamConverter("newRoom", class="AppBundle:Room", converter="room_converter")
     * @param Room $newRoom
     * @return JsonResponse
     */
    public function createRoomAction(Room $newRoom)
    {
        try {
            $this->get('room_manager')->createRoom($newRoom);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($newRoom, 201);
    }
}
