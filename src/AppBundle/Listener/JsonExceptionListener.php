<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

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
        $data = [
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]
        ];
        $event->setResponse(new JsonResponse($data, $exception->getCode()));
    }
}
