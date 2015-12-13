<?php

namespace AppBundle\Controller;

use AppBundle\Models\User;
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
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/users", name="get_all_users")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllUsersAction()
    {
        $users = $this->get('user_manager')->getAllUsers();

        return new JsonResponse($users);
    }

    /**
     * @Route("/users/{id}", name="get_single_user")
     * @Method({"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getUserByIdAction($id)
    {
        /** @var User $user */
        $user = null;
        try {
            $user = $this->get('user_manager')->getUserById($id);
        } catch (ResourceNotFoundException $e) {
            throw $e;
        }

        return new JsonResponse($user);
    }

    /**
     * @Route("/users", name="update_user")
     * @Method({"PUT"})
     * @ParamConverter("updatedUser", class="AppBundle:User", converter="user_converter")
     * @param User $updatedUser
     * @return JsonResponse
     */
    public function updateUserAction(User $updatedUser)
    {
        $user = null;
        try {
            $user = $this->get('user_manager')->updateUser($updatedUser);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($user, 200);
    }

    /**
     * @Route("/users/{id}", name="delete_user")
     * @Method({"DELETE"})
     * @ParamConverter("toBeDeletedUser", class="AppBundle:User")
     * @param User $toBeDeletedUser
     * @return JsonResponse
     */
    public function deleteUserAction(User $toBeDeletedUser)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Sie müssen Administrator sein um Benutzer zu löschen.');
        }
        $this->get('user_manager')->deleteUser($toBeDeletedUser);

        return new JsonResponse($toBeDeletedUser, 200);
    }

    /**
     * @Route("/users", name="create_user")
     * @Method({"POST"})
     * @ParamConverter("newUser", class="AppBundle:User", converter="user_converter")
     * @param User $newUser
     * @return JsonResponse
     */
    public function createUserAction(User $newUser)
    {
        try {
            $this->get('user_manager')->createUser($newUser);
        } catch (ValidatorException $e) {
            throw $e;
        }

        return new JsonResponse($newUser, 201);
    }
}
