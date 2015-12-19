<?php

namespace AppBundle\Converter;

use AppBundle\Models\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserConverter
 * @package AppBundle\Converter
 */
class UserConverter implements ParamConverterInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserConverter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        $user = User::createUser(
            isset($decoded['id']) ? $decoded['id'] : null,
            $decoded['username'],
            $decoded['firstName'],
            $decoded['lastName'],
            $decoded['email'],
            isset($decoded['password']) ? $decoded['password'] : null
        );
        if (isset($decoded['role'])) {
            $role = $this->entityManager->find('AppBundle:Role', $decoded['role']);
            if ($role !== null) {
                $user->addToRoles($role);
            }
        }

        $request->attributes->set($configuration->getName(), $user);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle:User';
    }
}
