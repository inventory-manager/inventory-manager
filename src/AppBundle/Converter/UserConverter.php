<?php

namespace AppBundle\Converter;

use AppBundle\Models\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserConverter
 * @package AppBundle\Converter
 */
class UserConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        $user = User::createUser(isset($decoded['id']) ? $decoded['id'] : null, $decoded['username'], $decoded['firstName'], $decoded['lastName'], $decoded['email'], $decoded['password']);

        $request->attributes->set($configuration->getName(), $user);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:User';
    }
}
