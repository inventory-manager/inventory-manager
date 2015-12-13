<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class JsonExceptionListener
 *
 * Listener um alle in Controller hochgeworfenen Exceptions nach JSON zu konvertieren
 *
 * @package AppBundle\Listener
 */
class JsonExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        // Bei fehlender Authentifizierung in geschütztem Bereich Exception nach 401 konvertieren
        if ($exception instanceof AuthenticationCredentialsNotFoundException) {
            $exception = new UnauthorizedHttpException("", "Unauthorized", null, 401);
        }

        $data = [
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]
        ];
        $event->setResponse(new JsonResponse($data, $exception->getCode() == 0 ? 500 : $exception->getCode()));
    }
}
