<?php

namespace AppBundle\Controller;

use AppBundle\Models\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class SecurityController
 *
 * Controller zur Authentifizierung von Usern
 *
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login_status", name="login_status")
     */
    public function loginStatusAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse([
                'authenticated' => false
            ]);
        } else {
            /** @var User $user */
            $user = $this->getUser();
            return new JsonResponse([
                'authenticated' => true,
                'user' => $user
            ]);
        }
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginAction()
    {
        // Leer, Route wird vom Framework abgefangen
    }
}
